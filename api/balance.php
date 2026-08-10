<?php

session_start();

require_once("../includes/User.php");

header("Content-Type: application/json");

if(!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

$user = User::getById($_SESSION['user']['id']);

if(!$user) {
    http_response_code(404);
    echo json_encode(["error" => "User not found"]);
    exit;
}

echo json_encode([
    "balance" => number_format($user['balance'], 2)
]);
