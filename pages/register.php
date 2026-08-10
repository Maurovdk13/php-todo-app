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

        $success = "Account created successfully! You received 10 XD tokens.";

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
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>

    <main class="auth-page">
    <section class="panel auth-panel">
    <h1>Register</h1>

    <?php if($error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if($success): ?>
        <p><?php echo htmlspecialchars($success); ?></p>
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

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>
    </section>
    </main>

</body>
</html>
