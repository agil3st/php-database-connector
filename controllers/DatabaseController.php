<?php

namespace controllers;

use PDO;

class DatabaseController {

    private $providerName;
    private $serverName = "localhost";
    private $userName = "root";
    private $password = "";
    private $databaseName;
    private $connection = null;
    private $tableRows = null;
    public $result, $conn, $stmt;

    public function __construct($providerName, $serverName, $userName, $password, $databaseName) {
        $this->providerName = $providerName;
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->password = $password;
        $this->databaseName = $databaseName;
    }

    //call this method to start connection
    public function connect() {
        try {
            $this->connection = new PDO($this->providerName.":host=".$this->serverName.";dbname=".$this->databaseName."", $this->userName, $this->password);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            return $this->connection;
        } catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    //call this method to close connection
    public function disconnect() {
        $this->connection = null;
        $this->tableRows = null;
    }

    public function select($query) {
        try {
            $this->connect();
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            // set the resulting array to associative
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // $tableRows = new TableRows(new RecursiveArrayIterator($result->fetchAll()));
            // var_dump($stmt->fetchAll());
            $data = $stmt->fetchAll();
            // var_dump($object->name);
            // echo $data[0]["name"];
            // foreach ($data as $row) {
            //     echo $row["name"]."<br />\n";
            // }
            $this->result = $data;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $this->disconnect();
    }

    public function insert($query) {
        try {
            $this->connect();
            // use exec() because no results are returned
            $connection->exec($query);
            echo "New record created successfully";
        } catch(PDOException $e) {
            echo $query . "<br>" . $e->getMessage();
        }

        $this->disconnect();
    }

    public function insertMultiple($multiQuery = array()) {
        try {
            $this->connect();
            // begin the transaction
            $connection->beginTransaction();
            // our SQL statements
            for ($i=0; $i<count($multiQuery); $i++) {
                $connection->exec($multiQuery[$i]);
            }
            // commit the transaction
            $connection->commit();
            echo "New records created successfully";
        } catch(PDOException $e) {
            // roll back the transaction if something failed
            $connection->rollback();
            echo "Error: " . $e->getMessage();
        }

        $this->disconnect();
    }

    public function delete($query) {
        try {
            $this->connect();
            // use exec() because no results are returned
            $connection->exec($query);
            echo "Record deleted successfully";
        } catch(PDOException $e) {
            echo $query . "<br>" . $e->getMessage();
        }

        $this->disconnect();
    }

    public function update($query) {
        try {
            $this->connect();
            // Prepare statement
            $stmt = $connection->prepare($query);
            // execute the query
            $stmt->execute();
            // echo a message to say the UPDATE succeeded
            echo $stmt->rowCount() . " records UPDATED successfully";
        } catch(PDOException $e) {
            echo $query . "<br>" . $e->getMessage();
        }

        $this->disconnect();
    }

    public function getResultArray() {
        return $this->result;
    }


}