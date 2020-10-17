<?php
/*******************************************************
 * Project:     hsst-cc-portfolio
 * File:        delete.php
 * Author:      Hilary Soong
 * Date:        2020-06-16
 * Version:     1.0.0
 * Description:
 *******************************************************/

include '../../api/config/Database.php';
include '../../api/classes/Utils.php';
?>

<!DOCTYPE HTML>
<html lang="en-AU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Retail App | Categories | Delete</title>

    <!-- CSS required -->
    <!-- Bootstrap 4.x -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- FontAwesome 5.x -->
    <link rel="stylesheet" href="/app/assets/fa/css/all.min.css">

</head>
<body>
<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="../">Retail App</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="../../app/">Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>

<!-- container -->
<main role="main" class="container">

    <div class="row">
        <div class="col-sm">
            <h1>Delete a Category</h1>
        </div>
    </div>

<?php
    $messages = [];
    $database = new Database();
    $conn = $database->getConnection();
    if (!isset($_POST['id']) && !isset($_GET['id'])) {
        $messages[] = ['Danger' => 'Record Id not found.'];
    }
    else {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        $performDelete = $_POST['performDelete'] ?? false;
        if (!$performDelete) {
            try {
                $query = "SELECT id, code, name, description FROM cc_store.categories WHERE id = :ID LIMIT 0,1";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":ID", $id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_OBJ);
                ?>
                <!--we have our html table here where the record will be displayed-->
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Code</td>
                        <td><?= Utils::sanitize($row->code) ?></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td><?= Utils::sanitize($row->name) ?></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><?= Utils::sanitize($row->description) ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <form action='delete.php' method='post'>
                                <input type='hidden' id='id' name='id'
                                       value="<?= $row->id ?>"/>
                                <input type='hidden' id='performDelete' name='performDelete'
                                       value="<?= $row->id ?>"/>
                                <button type='submit' value='submit' class='btn btn-danger'>
                                    Confirm Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                </table>
    <?php
            } // show error
            catch (PDOException $exception) {
                $messages[] = ['Danger' => 'Error locating the category. Please contact Admin.'];
                $messages[] = ['Secondary ' => $exception->getMessage()];
            }
        }
        try {
            $query = "DELETE FROM cc_store.categories WHERE id = :deleteThis";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':deleteThis', $id, PDO::PARAM_INT);
            $stmt->execute();
            $messages[] = ['Success' => "Category {$performDelete} deleted."];
        } // show error
        catch (PDOException $exception) {
            $messages[] = ['Danger' => 'Error deleting the category. Please contact Admin.'];
            $messages[] = ['Secondary ' => $exception->getMessage()];
        }

        if (count($messages) > 0) {
            Utils::messages($messages);
        }

    }

    ?>
    <p>
        <a href="browse.php" class="btn btn-info">Back</a>
    </p>
</main> <!-- end .container -->

<!-- JavaScript that is required -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>


</body>
</html>
