<?php
define('DB_HOST', "localhost");
define("DB_SERVER", "mysql");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_DATABASE", "chatbotapp");

function connectToMysql()
{
    try {
        $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($conn->connect_error)
            die("Error connecting to database. " . $conn->connect_error);
        return $conn;
    } catch (Exception $ex) {
        return false;
    }
}
