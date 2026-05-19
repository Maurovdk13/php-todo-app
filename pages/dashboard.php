<?php

session_start();

if(!isset($_SESSION['user'])) {

    header("Location: login.php");

}

?>

<h1>Dashboard</h1>

<p>
    Welcome
    <?php echo $_SESSION['user']['firstname']; ?>
</p>