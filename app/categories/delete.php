<?php
/*******************************************************
 * Project:     hsst-cc-portfolio
 * File:        delete.php
 * Author:      Hilary Soong
 * Date:        2020-06-16
 * Version:     1.0.0
 * Description:
 *******************************************************/

include '../../config/Database.php';
include_once '../../classes/Utils.php';
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
            <li class="nav-item">
                <a class="nav-link" href="../">Home</a>
            </li>
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="../product" id="navbarDropdown"
                   role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    Product  <span class="sr-only">(current)</span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../product/browse.php">Browse</a>
                    <a class="dropdown-item" href="../product/create.php">Add</a>
                </div>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="../categories" id="navbarDropdown"
                   role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    Category
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../categories/browse.php">Browse</a>
                    <a class="dropdown-item" href="../categories/create.php">Add</a>
                </div>
            </li>
    </div>
</nav>

<!-- container -->
<main role="main" class="container">

    <div class="row">
        <div class="col-sm">
            <h1>Delete Category</h1>
        </div>
    </div>

<?php
//Before performing delete query, read the data you are about to delete
//get passed the parameter value of record id.
    $messages = [];
    $database = new Database();
    $conn = $database->getConnection();

    if(isset($_POST['id'])){

        $id = $_POST['id'];
        $performDelete = $_POST['performDelete']??false;

        if(!$performDelete){
            try{
                $query = "select id, code, name, description from 
                            cc_store.categories where id = :ID
                            limit 0,1";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":ID",$id, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_OBJ);
?>
<!--html from here will display the data we wanted to delete-->
<table class="table table-hover table-responsive table-bordered">
    <tr>
        <td>Id</td>
        <td><?= Utils::sanitize($row->id) ?></td>
    </tr>
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
            <form action="delete.php" method="post">
                <input type="hidden" id="id" name="id" value="<?= $row->id?>"/>
                <input type="hidden" id="performDelete" name="performDelete" value="<?= $row->id?>"/>
                <button type="submit" value="submit" class="btn btn-danger">
                    Confirm Delete
                </button>
            </form>
        </td>
    </tr>
</table>
   <?php
            }
            catch (PDOException $exception){
                $messages[] = ["Danger"=>"Error locating the product. Please contact Admin"];
                $messages[] = ["Secondary"=>$exception->getMessage()];
            }

        }else if ($id === $performDelete){
//            delete record
            try {
                $query = "Delete from cc_store.categories where id = :deleteThis";
                $stmt = $conn->prepare($query);
                $stmt->execute();
            }
            catch (PDOException $exception){
                $messages[] = ["Danger"=>"Error deleting the product. Please contact Admin"];
                $messages[] = ["Secondary"=>$exception->getMessage()];
            }
        }
    }
    else{
        $messages[] = ["Danger"=>"Sorry, no direct access to this page"];

    }
    if (count($messages)>0){
        Utils::messages($messages);
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
