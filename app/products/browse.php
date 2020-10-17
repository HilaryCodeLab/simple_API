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

    <title>Retail App | Products | Browse</title>

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
            <h1>Browse Products</h1>
        </div>
    </div>

    <?php
    include '../../api/config/Database.php';
    include '../../api/classes/Utils.php';

    $database = new Database();
    $conn = $database->getConnection();
    $query = "SELECT 
                    c.name as category_name, p.id, p.name, p.description, 
                p.price,  p.category_id, p.created_at, p.updated_at
            FROM cc_store.products AS p
                LEFT JOIN cc_store.categories AS c
                    ON p.category_id = c.id
            ORDER BY p.created_at DESC;";
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
                    <th>Category</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <?php
                while($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                ?>
                    <tr>
                        <td><?=$row->id?></td>
                        <td><?=$row->category_name?></td>
                        <td><?=$row->name?></td>
                        <td><?=$row->price?></td>
                        <td><?=$row->description?></td>
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

