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
        exit;

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
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>

    <main class="auth-page">
    <section class="panel auth-panel">
    <div class="auth-brand">
        <span class="brand-coin">XD</span>
        <strong>XD Wallet</strong>
    </div>

    <h1>Login</h1>

    <?php if($error): ?>
        <p><?php echo htmlspecialchars($error); ?></p>
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

    <p>
        No account yet?
        <a href="register.php">Register</a>
    </p>
    </section>
    </main>

</body>
</html>
