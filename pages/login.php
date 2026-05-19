<?php

require_once("../includes/User.php");

$error = "";

if(!empty($_POST)) {

    try {

        $user = new User();

        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);

        $user->login();

        header("Location: dashboard.php");

    } catch(Exception $e) {

        $error = $e->getMessage();

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

    <h1>Login</h1>

    <?php if($error): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="" method="post">

        <input type="email" name="email" placeholder="Email">

        <br><br>

        <input type="password" name="password" placeholder="Password">

        <br><br>

        <button type="submit">
            Login
        </button>

    </form>

</body>
</html>