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
    <a class="navbar-brand" href="./">Demo APP</a>
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
            <h1>Browse Categories</h1>
            <p><a href="../categories/create.php" class="btn btn-success">Create</a></p>
        </div>
    </div>

    <?php
    include '../../api/config/Database.php';
    include '../../api/classes/Utils.php';

    $database = new Database();
    $conn = $database->getConnection();
    $query = "select id, code, name, description 
                  from cc_store.categories 
                  order by id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $totalRecords = $stmt->rowCount();

    // set the page records to 5
    $pageRecords = 5;
    $displayPages = (int)ceil($totalRecords/$pageRecords);

    $activePage = 1;

    // If user request a page number greater than $displayPages, set $activePage to the maximum pages number
    if (isset($_GET['page']) && is_numeric($_GET['page']) && (int)$_GET['page'] >0 ){
        if ((int)$_GET['page'] > $displayPages){
            $activePage = $displayPages;
        } else {$activePage = (int)$_GET['page']; }
    }

    // exclude records depending on which page the user is
    $skipRecords = ($activePage - 1) * $pageRecords;


    if ($pageRecords > 0 && $skipRecords >= 0) {

        $query2 = "SELECT id, code, name, description FROM cc_store.categories ORDER BY id DESC LIMIT :skipRecords, :pageRecords";

        $stmt2 = $conn->prepare($query2);
        $stmt2->bindParam(':skipRecords', $skipRecords, PDO::PARAM_INT);
        $stmt2->bindParam(':pageRecords', $pageRecords, PDO::PARAM_INT);
    } else{
        $query2 = "SELECT id, code, name, description FROM categories ORDER BY id DESC";
        $stmt2 = $conn->prepare($query2);
    }

    $stmt2->execute();

    $num = $stmt2->rowCount();

    if ($num > 0){
    ?>
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
                while($row = $stmt2->fetch(PDO::FETCH_OBJ)) {
                ?>
                    <tr>
                        <td><?=$row->id?></td>
                        <td><?=$row->code?></td>
                        <td><?=$row->name?></td>
                        <td><?=$row->description?></td>
                        <td>
                            <a href="../categories/read.php?id=<?=$row->id?>" class="btn btn-info mr-1">Read</a>
                            <a href="../categories/update.php?id=<?=$row->id?>" class="btn btn-warning mr-1">Update</a>
                            <a href="../categories/delete.php?id=<?=$row->id?>" class="btn btn-danger">Delete</a>
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
    <div class="row">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?= $activePage <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= "browse.php?page=".($activePage - 1 < 1 ? $activePage : $activePage - 1) ?>">Previous</a></li>

                <?php
                for($i = 0; $i < $displayPages; $i++){?>
                    <li class="<?= ($activePage === $i+1) ? "page-item active" : "page-item" ?>">
                        <a class="page-link" href="<?= "browse.php?page=".($i+1)?>"><?= $i+1 ?></a>
                    </li>
                    <?php
                }
                ?>

                <li class="page-item <?= $activePage >= $displayPages ? 'disabled' : ''?>">
                    <a class="page-link" href="<?= "browse.php?page=".($displayPages) ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</main> <!-- end .container -->

<!-- JavaScript that is required -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

</body>
</html>

