<?php
/*******************************************************
 * Project:     hsst-cc-portfolio-p2
 * File:        config/Database.php
 * Author:      Hilary Soong
 * Date:        2020-05-30
 * Version:     1.0.0
 * Description:
 *******************************************************/
class Database
{
    private $dbType = 'mysql';
    private $dbName = 'cc_store';
    private $dbHost = 'localhost';
//    private $dbHost = '127.0.0.1';
    private $dbPort = '3306';
    private $dbCharSet = 'utf8';
    private $dbUser = 'cc_store_user';
    private $dbPassword = 'Secret1';
    private $dsn = '';
    public $connection;

    public function __construct()
    {
       $this->dsn = "{$this->dbType}:dbName={$this->dbName}".
            "host={$this->dbHost};port={$this->dbPort};charset={$this->dbCharSet}";
    }

    // get the database connection

    public function getConnection(){
        $this->connection = new PDO($this->dsn,$this->dbUser,$this->dbPassword);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $this->connection;

    }

}