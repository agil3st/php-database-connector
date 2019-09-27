<?php

namespace controllers;

class DatabaseController {

    private $providerName;
    private $serverName = "localhost";
    private $userName = "root";
    private $password = "";
    private $databaseName;
    private $connection = null;
    private $tableRows = null;
    public $conn, $stmt;

    public function setConnection($providerName, $serverName, $userName, $password, $databaseName) {
        $this->providerName = $providerName;
        $this->serverName = $serverName;
        $this->userName = $userName;
        $this->password = $password;
        $this->databaseName = $databaseName;
    }

    //call this method to start connection
    public function connect() {
        try {
            $connection = new PDO($providerName.":host=".$this->serverName.";dbname=".$this->databaseName."", $this->userName, $this->password);
            // set the PDO error mode to exception
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo "Connected successfully";
            return $connection;
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
            connect();
            $stmt = $connection->prepare($query);
            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $tableRows = new TableRows(new RecursiveArrayIterator($stmt->fetchAll()));
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        disconnect();
    }

    public function insert($query) {
        try {
            connect();
            // use exec() because no results are returned
            $connection->exec($query);
            echo "New record created successfully";
        } catch(PDOException $e) {
            echo $query . "<br>" . $e->getMessage();
        }

        disconnect();
    }

    public function insertMultiple($multiQuery = array()) {
        try {
            connect();
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

        disconnect();
    }

    public function delete($query) {
        try {
            connect();
            // use exec() because no results are returned
            $connection->exec($query);
            echo "Record deleted successfully";
        } catch(PDOException $e) {
            echo $query . "<br>" . $e->getMessage();
        }

        disconnect();
    }

    public function update($query) {
        try {
            connect();
            // Prepare statement
            $stmt = $connection->prepare($query);
            // execute the query
            $stmt->execute();
            // echo a message to say the UPDATE succeeded
            echo $stmt->rowCount() . " records UPDATED successfully";
        } catch(PDOException $e) {
            echo $query . "<br>" . $e->getMessage();
        }

        disconnect();
    }

    public function getResult() {
        return $tableRows;
    }


}