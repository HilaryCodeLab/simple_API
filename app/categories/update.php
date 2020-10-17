<?php
/*******************************************************
 * Project:     hsst-cc-portfolio
 * File:        update.php
 * Author:      Hilary Soong
 * Date:        2020-06-16
 * Version:     1.0.0
 * Description:
 *******************************************************/

include '../../api/config/Database.php';
include '../../api/classes/Utils.php';

$database = new Database();
$conn = $database->getConnection();
/**
 * Initialise the common variables for the update page
 */
$messages = [];
$name = "";
$description = "";
$price = "";
//$targetDirectory = "../../{$targetDirectory}/";
$allowedFileTypes = array(
    "jpg", "jpeg", "png", "gif", "JPG", "JPEG", "PNG", "GIF", "svg", "SVG"
);
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Retail App | Categories | Update</title>
    <link rel="stylesheet" href="/app/assets/bs/css/bootstrap.min.css">
    <link rel="stylesheet" href="/app/assets/fa/css/all.min.css">
</head>
<body>
<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="../">Demo APP</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
            aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="../">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="../products" id="navbarDropdown"
                   role="button" data-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    Product
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="../products/browse.php">Browse</a>
                    <a class="dropdown-item" href="../products/create.php">Add</a>
                </div>
            </li>
            <li class="nav-item dropdown active">
                <a class="nav-link dropdown-toggle" href="../categories/browse.php" id="navbarDropdown"
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
<div class="container">
    <div class="row">
        <div class="col-sm">
            <h1>Update a Category</h1>
        </div>
    </div>
    <!-- PHP read record by ID -->
    <?php
    $messages = [];
    if (!isset($_POST['id']) && !isset($_GET['id'])) {
        $messages[] = ['Danger' => 'Record ID not found.'];
    } else {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
// check if form was submitted
        if ($_POST) {
// posted values
            $nameUpdate = Utils::sanitize($_POST['name']);
            $descriptionUpdate = Utils::sanitize($_POST['description']);
            $codeUpdate = Utils::sanitize($_POST['code']);
            $id = $idUpdate = Utils::sanitize($_POST['id']);
//  check if the input is valid
            if(empty($nameUpdate)||empty($descriptionUpdate)||empty($codeUpdate)){
                $messages[] = ["Warning"=>"field(s) cannot be empty"];
            }
            if(empty($messages)){
                try {
                    $query = "UPDATE cc_store.categories SET code = :newCode, name = :newName, description = :newDescription WHERE id =:id";
                    $stmt = $conn->prepare($query);
                    $stmt->bindParam(':newName', $nameUpdate);
                    $stmt->bindParam(':newDescription', $descriptionUpdate);
                    $stmt->bindParam(':newCode', $codeUpdate);
                    $stmt->bindParam(':id', $idUpdate);

                    // Execute the query
                    if ($stmt->execute()) {
                        $messages[] = ['Success' => "Record saved."];
                    }
                    else {
                        $messages[] = ['Danger' => "Unable to update record."];
                    }
                } catch (PDOException $exception) {
                    $messages[] = ['Danger' => 'System Error: Contact Admin.'];
                    $messages[] = ['Secondary' => $exception->getMessage()];
                }
            }

        }

        $id = Utils::sanitize($id);

// read current record's data
        try {
            $query = "SELECT id, code, name, description FROM cc_store.categories WHERE id = :ID LIMIT 0,1";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":ID", $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            $name = Utils::sanitize($row->name);
            $description = Utils::sanitize($row->description);
            $code = Utils::sanitize($row->code);
        } // show error
        catch (PDOException $exception) {
            $messages[] = ['Danger' => 'System Error: Contact Admin.'];
            $messages[] = ['Secondary' => $exception->getMessage()];
        }
    }
    Utils::messages($messages);
    ?>
    <form action='<?= Utils::sanitize($_SERVER["PHP_SELF"]) ?>'
          method="post"
          enctype="multipart/form-data">
        <input type="hidden" name="id" id="id" value="<?= $id ?>">

        <div class="form-group">
            <label for="code">Code</label>
            <input type='text'
                   name='code' id="code"
                   class='form-control'
                <?= ($code !== '' ? "value='{$code}'" : '') ?>
                   placeholder="Enter category code"/>
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type='text'
                   name='name' id="name"
                   class='form-control'
                <?= ($name !== '' ? "value='{$name}'" : '') ?>
                   placeholder="Enter category name"/>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                name='description' id="description"
                class='form-control'
                placeholder="Enter category description"><?= $description ?></textarea>
        </div>

        <div class="form-group">
            <label for="submit"></label>
            <input type='submit' value='Save'
                   name="submit" id="submit"
                   class='btn btn-primary'/>
        </div>
        <div class="form-group">
            <a href='browse.php'
               class='btn btn-secondary'>Browse Categories</a>
        </div>
    </form>
</div> <!-- end .container -->
<script src="/app/assets/jquery/jquery-3.5.1.min.js"></script>
<script src="/app/assets/popper/popper.min.js"></script>
<script src="/app/assets/bs/js/bootstrap.min.js"></script>
</body>
</html>