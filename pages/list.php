<?php

session_start();

require_once("../includes/TodoList.php");
require_once("../includes/Task.php");

if(!isset($_SESSION['user'])) {
    header("Location: login.php");
}

if(!isset($_GET['id'])) {
    die("No list selected");
}

$list = TodoList::getById($_GET['id']);

$error = "";

if(!empty($_POST)) {

    try {

        $task = new Task();

        $task->setTitle($_POST['title']);
        $task->setPriority($_POST['priority']);
        $task->setListId($_GET['id']);

        $task->create();

    } catch(Exception $e) {

        $error = $e->getMessage();

    }

}

$tasks = Task::getTasksByList($_GET['id']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
</head>
<body>

    <h1>
        <?php echo htmlspecialchars($list['title']); ?>
    </h1>

    <?php if($error): ?>

        <p>
            <?php echo $error; ?>
        </p>

    <?php endif; ?>

    <h2>Add task</h2>

    <form action="" method="post">

        <input
            type="text"
            name="title"
            placeholder="Paspoort aanvragen"
        >

        <br><br>

        <select name="priority">

            <option value="low">
                Low
            </option>

            <option value="medium">
                Medium
            </option>

            <option value="high">
                High
            </option>

        </select>

        <br><br>

        <button type="submit">
            Add Task
        </button>

    </form>

    <hr>

    <h2>Tasks</h2>

    <?php foreach($tasks as $task): ?>

    <p>

        <?php if($task['status'] === 'done'): ?>

            ✅

        <?php else: ?>

            ⬜

        <?php endif; ?>

        <?php echo htmlspecialchars($task['title']); ?>

        -

        <?php echo htmlspecialchars($task['priority']); ?>

        <a href="toggle-task.php?id=<?php echo $task['id']; ?>">
            Toggle
        </a>
        <a href="delete-task.php?id=<?php echo $task['id']; ?>">
    Delete
</a>

    </p>

<?php endforeach; ?>
</body>
</html>