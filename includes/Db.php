<?php

class Db {

    private static $conn;

    public static function getConnection() {

        if(!self::$conn) {

            self::$conn = new PDO(
                "mysql:host=localhost;dbname=todo_app;charset=utf8mb4",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

        }

        return self::$conn;
    }
}
