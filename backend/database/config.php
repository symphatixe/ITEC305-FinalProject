<?php
    const DB_SERVER = "LOCALHOST";
    const DB_USERNAME = "root";
    const DB_PASSWORD = "ROOT8594";
    const DB_NAME = "master_quiz";


    function dbConnect() {
        try {
            $pdo = new PDO("mysql:host=" . DB_SERVER .
                ";dbname=" . DB_NAME,
                DB_USERNAME,
                DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        }

        catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }