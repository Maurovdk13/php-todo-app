<?php

class Db {

    private static $conn;

    public static function getConnection() {

        if(!self::$conn) {

            self::$conn = new PDO(
                "mysql:host=localhost;dbname=todo_app",
                "root",
                ""
            );

        }

        return self::$conn;
    }
}