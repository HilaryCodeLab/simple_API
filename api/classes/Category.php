<?php
/*******************************************************
 * Project:     hsst-cc-portfolio-p2
 * File:        classes/Category.php
 * Author:      Hilary Soong
 * Date:        2020-05-30
 * Version:     1.0.0
 * Description:
 *******************************************************/
class Category
{
    /** @var */
    private $conn;

    /**
     * @var Categories object properties
     */
    /** @var */
    public $id;
    /** @var */
    public $code;
    /** @var */
    public $description;
    /** @var */
    public $name;
    /** @var */
    public $createdAt;
    /** @var */
    public $updatedAt;
    /** @var */
    public $deletedAt;

    /**
     * Category constructor.
     * @param $db
     * Take database connection as an argument
     */

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * @param string $text
     * @return string get rid of the tags, convert text to html special characters
     */
    private function sanitize(string $text): string
    {
        return htmlspecialchars(strip_tags($text));
    }

    public function read()
    {
        //id,code,name,description,created_at,updated_at
        // select all query
        $query = "SELECT id, code, name, description, created_at, updated_at, deleted_at       
            FROM cc_store.categories";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readOne($id)
    {
        // select ONE query
        $query = "SELECT id, code, name, description, created_at, updated_at, deleted_at   
            FROM cc_store.categories 
            WHERE id = :categoryID";

        // prepare, bind named parameter, and execute query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryID', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt;
    }

    public function create()
    {
        //add an item to table
        $query = "INSERT INTO cc_store.categories(`id`,`code`,`name`,`description`,`created_at`,`updated_at`,`deleted_at`)
                VALUES(null,:code,:name,:description,:created_at,:updated_at,null);";

        $stmt = $this->conn->prepare($query);

        //sanitize code,name,description
        $this->code = $this->sanitize($this->code);
        $this->name = $this->sanitize($this->name);
        $this->description = $this->sanitize($this->description);
        $this->createdAt = $this->sanitize($this->createdAt);
        $this->updatedAt = $this->createdAt;

        //bind values to the placeholders
        $stmt->bindParam(":code", $this->code, PDO::PARAM_STR);
        $stmt->bindParam(":name", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":description", $this->description, PDO::PARAM_STR);
        $stmt->bindParam(":created_at", $this->createdAt, PDO::PARAM_STR);
        $stmt->bindParam(":updated_at", $this->updatedAt, PDO::PARAM_STR);
//        $stmt->bindParam(":deleted_at",$this->deletedAt,PDO::PARAM_STR);

        //execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        // select ONE query
        $query = "
            DELETE FROM cc_store.categories
            WHERE id = :categoryID
            ";

        // prepare, bind named parameter, and execute query
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':categoryID', $id, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt;
    }

    public function search(string $searchText)
    {
        $searchDescription = $searchName = '%' . $this->sanitize($searchText) . '%';
        //query data by name and description from product table using search text
        $query = "
            SELECT 
                c.name as category_name, p.id, p.name, p.description, 
                p.price,  p.category_id, p.created_at, p.updated_at
            FROM cc_store.products AS p
                LEFT JOIN cc_store.categories AS c
                    ON p.category_id = c.id
            WHERE p.name LIKE :prodName
            OR p.description LIKE :prodDescription
            ORDER BY p.created_at DESC;";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':prodName', $searchName, PDO::PARAM_STR);
        $stmt->bindParam(':prodDescription', $searchDescription, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt;
    }

    public function update()
    {
        // query to insert record
        $query = "
            UPDATE cc_store.categories
            SET               
                code = :cCode,
                name = :cName, 
                description = :cDescription,
                updated_at = now()
             WHERE id=:cID;";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->code = htmlspecialchars(strip_tags($this->code));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // bind values
        $stmt->bindParam(":cID", $this->id, PDO::PARAM_INT);
        $stmt->bindParam(":cCode", $this->code, PDO::PARAM_STR);
        $stmt->bindParam(":cName", $this->name, PDO::PARAM_STR);
        $stmt->bindParam(":cDescription", $this->description, PDO::PARAM_STR);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


}