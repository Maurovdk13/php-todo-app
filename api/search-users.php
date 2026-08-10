<?php

session_start();

require_once("../includes/User.php");

header("Content-Type: application/json");

if(!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$query = isset($_GET['q']) ? trim($_GET['q']) : "";

if(strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$users = User::searchByName($query, $_SESSION['user']['id']);

echo json_encode($users);
