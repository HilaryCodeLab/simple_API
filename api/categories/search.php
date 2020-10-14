<?php
/*******************************************************
 * Project:     hsst-cc-portfolio-p2
 * File:        categories/search.php
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

// get passed parameter value, in this case, the record ID
// isset() is a PHP function used to verify if a value is there or not
if (isset($_GET['search'])) {
    $searchText = $_GET['search'];

    $database = new Database();
    $db = $database->getConnection();
    $category = new Category($db);

    // code for search products starts here
    $stmt = $category->search($searchText);

    $numRecords = $stmt->rowCount();

    if ($numRecords > 0) {
        // products array
//        $category_arr["records"] = [];
        $productList["records"] = [];


        // retrieve our table contents
        // fetch() is faster than fetchAll()
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
//            $categoryItem = array(
//                "id" => $row->id,
//                "code" => $row->code,
//                "name" => $row->name,
//                "description" => html_entity_decode($row->description),
//                "created_at" => $row->createdAt,
//                "updated_at" => $row->updatedAt,
//                "deleted_at" => $row->deletedAt
//
//
//            );
            $productItem = array(
                "id" => $row->id,
                "name" => $row->name,
                "description" => html_entity_decode($row->description),
                "price" => $row->price,
                "categoryID" => $row->category_id,
                "categoryName" => $row->category_name

            );

             array_push($productList["records"], $productItem);
            // is 2x slower than:
            $productList['records'][] = $productItem;
//            $category_arr['records'][] = $categoryItem;
        }

        // set response code - 200 OK
        http_response_code(200);

        // show products data in json format
//        echo json_encode($category_arr);
        echo json_encode($productList);
    } else {
        // set response code - 404 Not found
        http_response_code(404);

        // tell the user no products found
        echo json_encode(
            array("message" => "No products found.")
        );
    }
}
