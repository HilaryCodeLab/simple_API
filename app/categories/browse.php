<?php
/**********************************************************
 * Project:     hsst-cc-portfolio
 * File:        app/categories/browse.php
 * Author:      Hilary Soong <J160174@tafe.wa.edu.au>
 * Date:        2020-06-14
 * Version:     1.0.0
 * Description: Browse page
 **********************************************************/

?>
<!DOCTYPE HTML>
<html lang="en-AU">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Retail App | Categories | Browse</title>

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
                    <a class="dropdown-item" href="/">Browse</a>
                    <a class="dropdown-item" href="/">Add</a>
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
            <h1>Browse Categories</h1>
        </div>
    </div>

    <?php
    include '../../config/Database.php';
    include '../../classes/Utils.php';

    $database = new Database();
    $conn = $database->getConnection();
    $query = "select id, code, name, description 
                  from cc_store.categories 
                  order by id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $num = $stmt->rowCount();

    if ($num > 0){?>
    <div class="row">
        <div class="col-sm">
            <table class="table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                ?>
                    <tr>
                        <td><?=$row->id?></td>
                        <td><?=$row->code?></td>
                        <td><?=$row->name?></td>
                        <td><?=$row->description?></td>
                        <td>
                            <a href="read.php?id=<?$row->id?>" class="btn btn-info mr-1">Read</a>
                            <a href="../categories/update.php?id=<?$row->id?>" class="btn btn-warning mr-1">Update</a>
                            <a href="delete.php?id=<?=$row->id?>" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php
                  }
                ?>
                </tbody>
            </table>
                <?php }else{
                $messages[] = ['info'=>'no info found'];
                Utils::messages($messages);
                 }?>

    </div>
    </div>





</main> <!-- end .container -->

<!-- JavaScript that is required -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

</body>
</html>

