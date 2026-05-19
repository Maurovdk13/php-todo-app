<?php

require_once("../includes/User.php");

$error = "";
$success = "";

if(!empty($_POST)) {

    try {

        $user = new User();

        $user->setFirstname($_POST['firstname']);
        $user->setLastname($_POST['lastname']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);

        $user->register();

        $success = "Account created successfully!";

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
    <title>Register</title>
</head>
<body>

    <h1>Register</h1>

    <?php if($error): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if($success): ?>
        <p><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="" method="post">

        <input type="text" name="firstname" placeholder="Firstname">

        <br><br>

        <input type="text" name="lastname" placeholder="Lastname">

        <br><br>

        <input type="email" name="email" placeholder="Email">

        <br><br>

        <input type="password" name="password" placeholder="Password">

        <br><br>

        <button type="submit">
            Register
        </button>

    </form>

</body>
</html>