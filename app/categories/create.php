<?php
/*******************************************************
 * Project:     hsst-cc-portfolio
 * File:        create.php
 * Author:      Hilary Soong
 * Date:        2020-06-13
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

    <title>Retail App | Categories | Create</title>

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
            <h1>Create a Category</h1>
        </div>
    </div>

<?php
//initialise the variables code, name, descriptions, messages[]
$code = '';
$name = '';
$description = '';
$messages = [];

//Post get input, sanitize the text, validate the data,show messages
if($_POST){
    $code = Utils::sanitize($_POST['code']);
    $name = Utils::sanitize($_POST['name']);
    $description = Utils::sanitize($_POST['description']);

//  check if the input is valid
    if(empty($code)||empty($name)||empty($description)){
        $messages[] = ["Warning"=>"field(s) cannot be empty"];
    }
//    if no validation error, prepare the query and connect to database
//connect to database, try and catch, bind Param, show messages
    if (empty($messages)){
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $query = "insert into cc_store.categories set code=:code, name=:name, description=:description, created_at=:created";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':code',$code);
            $stmt->bindParam(':name',$name);
            $stmt->bindParam(':description',$description);

            $created = date('Y-m-d H:i:s');
            $stmt->bindParam(':created',$created);

            if($stmt->execute()){
                $messages[] = ['Success'=>"Record saved"];
            }
            else{
                $messages[] = ['Error'=>"Unable to save the record"];
            }
        }
        catch (PDOException $exception){
            die('ERROR:'. $exception->getMessage());
        }
    }

    Utils::messages($messages);


    if(isset($messages[0]['success'])){
        $code = "";
        $name = "";
        $description = "";

    }
}
?>
<!--html from here where data will be entered-->
    <form action="<?=Utils::sanitize($_SERVER['PHP_SELF'])?>"
            method="post">
        <div class="form-group">
            <label for="code">Code</label>
            <input type="text"
                    name="code"
                    id="code"
                    class="form-control"
                   <?=($code !== '' ? "value = '{$code}'" : '')?>
                    placeholder="Enter code here"/>
        </div>
        <div class="form-group">
            <label for = 'name'>Name</label>
            <input type="text"
                    name="name"
                    id="name"
                    class="form-control"
                    <?=( $name !== '' ? "value = '{$name}'" : '')?>
                   placeholder="Enter name here"/>
        </div>
        <div class="form-group">
            <label for = "description">description</label>
            <input type="text"
                   name="description"
                   id="description"
                   class="form-control"
                   <?= ($description !== '' ? "value = '{$description}'" : '') ?>
                   placeholder="Enter description here"/>
        </div>
        <div class="form-group">
            <input type="submit"
                   value="Save"
                   name="submit"
                   id="submit"
                   class="btn btn-success"
            />
        </div>
        <div class="form-group">
            <a href="browse.php" class="btn btn-info">Browse Categories</a>
        </div>
    </form>
</main> <!-- end .container -->

<!-- JavaScript that is required -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>


</body>
</html>
