<?php
/*******************************************************
 * Project:     hsst-cc-portfolio-p2
 * File:        categories/read.php
 * Author:      Hilary Soong
 * Date:        2020-05-29
 * Version:     1.0.0
 * Description:
 *******************************************************/

//required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//include the database and object files
include_once '../config/Database.php';
include_once '../classes/Category.php';

//instantiate database and get connection
$database = new Database();
$db = $database->getConnection();

//initiliaze category object
$category = new Category($db);

//query data
$stmt = $category->read();
$num = $stmt->rowCount();


if($num > 0){
    //put the items into an array
    $category_arr['records'] = [];

    while($row = $stmt->fetch(PDO::FETCH_OBJ))
    {
        $category_item = array(
            "id"=>$row->id,
            "code"=>$row->code,
            "name"=>$row->name,
            "description"=>html_entity_decode($row->description),
            "created_at"=>$row->created_at,
            "updated_at"=>$row->updated_at,
            "deleted_at"=>$row->deleted_at
        );
        $category_arr['records'][] = $category_item;
    }
    http_response_code(200);

    echo json_encode($category_arr);
}
else{

    http_response_code(404);

    echo json_encode(
        array("message"=> "No data found.")
    );
}