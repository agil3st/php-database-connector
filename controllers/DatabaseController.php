<?php

namespace controllers;

class DatabaseController {

    private $name;
    private $host;
    private $username;
    private $password;

    public function __construct($host, $username, $password, $name, $startConnection = 1) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;

        if ($startConnection == 1) {
            connect();
        }
    }

    public function connect() {
        
    }

    public function disconnect() {

    }

}