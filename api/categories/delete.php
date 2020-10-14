<?php
/*******************************************************
 * Project:     hsst-cc-portfolio-p2
 * File:        categories/delete.php
 * Author:      Hilary Soong
 * Date:        2020-06-01
 * Version:     1.0.0
 * Description:
 *******************************************************/

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/Database.php';
include_once '../classes/Category.php';


$data = json_decode(file_get_contents("php://input"), false);

if (isset($data->id)) {
    $id = $data->id;

// instantiate database and get database connection
    $database = new Database();
    $db = $database->getConnection();

// initialize product object
    $category = new Category($db);

// code for Read products starts here
// query products
    $stmt = $category->delete($id);
    $num = $stmt->rowCount();

// check if more than 0 records found
    if ($num > 0) {
        // set response code - 200 OK
        http_response_code(200);

        // show products data in json format
        echo json_encode(array("message" => "Product deleted."));
    } else {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the user no products found
        echo json_encode(
            array("message" => "Product not deleted.")
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
