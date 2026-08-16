<?php

class Db {

    private static $conn;

    public static function getConnection() {

        if(!self::$conn) {

            $host = "localhost";
            $dbname = "currency_app";
            $username = "root";
            $password = "";

            $isOnline = isset($_SERVER['HTTP_HOST'])
                && !str_contains($_SERVER['HTTP_HOST'], "localhost")
                && !str_contains($_SERVER['HTTP_HOST'], "127.0.0.1");

            if($isOnline) {
                $host = "sql200.infinityfree.com";
                $dbname = "if0_42671018_currency_app";
                $username = "if0_42671018";
                $password = "VUL_HIER_JE_INFINITYFREE_WACHTWOORD_IN";
            }

            self::$conn = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

        }

        return self::$conn;
    }
}
