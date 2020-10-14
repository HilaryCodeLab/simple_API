<?php
/*******************************************************
 * Project:     hsst-cc-portfolio
 * File:        read.php
 * Author:      Hilary Soong
 * Date:        2020-06-16
 * Version:     1.0.0
 * Description:
 *******************************************************/
include '../../config/Database.php';
include '../../classes/Utils.php';
?>
<!DOCTYPE HTML>
<html lang="en-AU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Retail App | Categories | Read </title>

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
                <a class="nav-link dropdown-toggle" href="../category" id="navbarDropdown"
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
            <h1>Show One Category Page</h1>
        </div>
    </div>

    <!--check for category id-->
    <?php

    $message =[];
    if(isset($_GET['id'])){
        $id = isset($_GET['id'])?$_GET['id']:die('Error: Record Id Not found');

    }

    try {
        $query = "SELECT id, code, name, description, created_at, updated_at FROM cc_store.categories WHERE id = :ID LIMIT 0,1;";

        $database = new Database();
        $conn = $database->getConnection();
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':ID',$id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        if(!$row){
            $message[] = ['Error'=>'no record found'];
        }
        else{
            $id = Utils::sanitize($row->id);
            $code = Utils::sanitize($row->code);
            $name = Utils::sanitize($row->name);
            $description = Utils::sanitize($row->description);
            $createdAt = Utils::sanitize($row->created_at);
            $updatedAt = Utils::sanitize($row->updated_at);
        }

    }catch (PDOException $exception){
        $message[] = ['Danger'=>'Error!Please contact admin'];
        $message[] = ['Secondary' => $exception->getMessage()];
    }
    if(count($message) == 0){
    ?>

    <div class="row">
        <div class="col-sm-8 col-md-6">
            <div class="row">
                <p class="col-sm-6 col-md-3"><strong>Id:</strong></p>
                <p class="col"><?=$id?></p>
            </div>
            <div class="row">
                <p class="col-sm-6 col-md-3"><strong>Code:</strong></p>
                <p class="col"><?=$code?></p>
            </div>
            <div class="row">
                <p class="col-sm-6 col-md-3"><strong>Name:</strong></p>
                <p class="col"><?=$name?></p>
            </div>
            <div class="row">
                <p class="col-sm-6 col-md-3"><strong>Description:</strong></p>
                <p class="col"><?=$description ?$description:"<i>No Description</i>"?></p>
            </div>
            <div class="row">
                <p class="col-sm-6 col-md-3"><strong>Created At:</strong></p>
                <p class="col"><?=$createdAt?></i></p>
            </div>
            <div class="row">
                <p class="col-sm-6 col-md-3"><strong>Updated At:</strong></p>
                <p class="col"><?=$updatedAt?></p>
            </div>
            <div class="row">
                <div class="col">
                    <form action="#" method="post">
                        <input type='hidden' id='id' name='id' value='<?=$row->id?>'/>
                        <a href="#" class="btn btn-info mr-1">Edit</a>
                        <button type="submit" value="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <?php
            }
            if($message>0){
                Utils::messages($message);
            }
            ?>
        </div>
    </div>
    <p class="mt-3"><a href="browse.php">Back</a></p>

</main> <!-- end .container -->

<!-- JavaScript that is required -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>


</body>
</html>

