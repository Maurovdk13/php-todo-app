<?php

session_start();

require_once("../includes/TodoList.php");

if(!isset($_SESSION['user'])) {
    header("Location: login.php");
}

$error = "";

if(!empty($_POST)) {

    try {

        $list = new TodoList();

        $list->setTitle($_POST['title']);
        $list->setUserId($_SESSION['user']['id']);

        $list->create();

    } catch(Exception $e) {

        $error = $e->getMessage();

    }
}

$lists = TodoList::getAllByUser(
    $_SESSION['user']['id']
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>

    <h1>
        Welcome
        <?php echo $_SESSION['user']['firstname']; ?>
    </h1>

    <?php if($error): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <h2>Create new list</h2>

    <form action="" method="post">

        <input
            type="text"
            name="title"
            placeholder="Portugal Trip"
        >

        <button type="submit">
            Create
        </button>

    </form>

    <hr>

    <h2>Your lists</h2>

    <?php foreach($lists as $list): ?>

        <p>
            <?php echo htmlspecialchars($list['title']); ?>
        </p>

    <?php endforeach; ?>

</body>
</html>