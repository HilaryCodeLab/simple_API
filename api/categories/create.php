<?php
/*******************************************************
 * Project:     hsst-cc-portfolio-p2
 * File:        categories/create.php
 * Author:      Hilary Soong
 * Date:        2020-05-30
 * Version:     1.0.0
 * Description:
 *******************************************************/

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//include database and object class
include_once '../config/Database.php';
include_once '../classes/Category.php';

//create database connection and category object
$database = new Database();
$db = $database->getConnection();
$category = new Category($db);

//get posted data
$data = json_decode(file_get_contents("php://input"), false);

//make sure all data required is displayed
if(!empty($data->code)&&
    !empty($data->name)&&
    !empty($data->description)
)
{
    //set object property values
    $category->code = $data->code;
    $category->name = $data->name;
    $category->description = $data->description;
    $category->createdAt = date('Y-m-d H:i:s');
    $category->updatedAt = date('Y-m-d H:i:s');


    // create the product
    if ($category->create()) {
        // set response code - 200 created
        http_response_code(200);

        // tell the user
        echo json_encode(array("message" => "Product was created."));
    } // if unable to create the product, tell the user
    else {
        // set response code - 500 Internal Server Error
        http_response_code(500);

        // tell the user
        echo json_encode(array("message" => "Unable to create product."));
    }

}
else {
    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}