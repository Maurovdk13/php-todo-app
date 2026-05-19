<?php

require_once("../includes/Task.php");

if(isset($_GET['id'])) {

    Task::delete($_GET['id']);

}

header("Location: " . $_SERVER['HTTP_REFERER']);