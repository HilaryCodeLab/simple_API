<?php
/*******************************************************
 * Project:     hsst-cc-portfolio-p2
 * File:        categories/readOne.php
 * Author:      Hilary Soong
 * Date:        2020-05-30
 * Version:     1.0.0
 * Description:
 *******************************************************/

// required headers (output is JSON)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/Database.php';
include_once '../classes/Category.php';

// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // instantiate database and get database connection
    $database = new Database();
    $db = $database->getConnection();

    // initialize product object
    $category = new Category($db);

    // code for readOne product starts here
    // query products with the ID that was sent
    $stmt = $category->readOne($id);
    $num = $stmt->rowCount();

    // check if more than 0 records found
    if ($num > 0) {
        // products array
        $categoryList["records"] = [];

        // retrieve our table contents
        // fetch() is faster than fetchAll()
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            $categoryItem = array(
                "id" => $row->id,
                "code"=> $row->code,
                "name" => $row->name,
                "description" => html_entity_decode($row->description),
                "created_at"=>$row->created_at,
                "updated_at"=>$row->updated_at,
                "deleted_at"=>$row->deleted_at

            );

            // array_push($productList["records"], $productItem);
            // is 2x slower than:
            $categoryList['records'][] = $categoryItem;
        }

        // set response code - 200 OK
        http_response_code(200);

        // show products data in json format
        echo json_encode($categoryList);
    } else {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the user no products found
        echo json_encode(
            array("message" => "No product found.")
        );
    }
} else {
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no id provided
    echo json_encode(
        array("message" => "No id provided.")
    );
}
