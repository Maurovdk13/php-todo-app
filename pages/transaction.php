<?php

session_start();

require_once("../includes/Transaction.php");

if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Transaction not found");
}

$currentUserId = $_SESSION['user']['id'];
$transaction = Transaction::getByIdForUser($_GET['id'], $currentUserId);

if(!$transaction) {
    die("You are not allowed to view this transaction");
}

$isIncoming = $transaction['receiver_id'] == $currentUserId;
$type = $isIncoming ? "Incoming" : "Outgoing";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction detail</title>
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>

    <main class="wallet-page narrow-page">
        <nav class="topbar">
            <a class="brand" href="dashboard.php">XD Wallet</a>
            <a class="logout-link" href="logout.php">Logout</a>
        </nav>

        <section class="panel detail-panel">
            <p class="eyebrow"><?php echo h($type); ?> transaction</p>
            <h1><?php echo h(number_format($transaction['amount'], 2)); ?> XD</h1>

            <dl class="detail-list">
                <dt>Sender</dt>
                <dd><?php echo h($transaction['sender_firstname'] . " " . $transaction['sender_lastname']); ?></dd>

                <dt>Receiver</dt>
                <dd><?php echo h($transaction['receiver_firstname'] . " " . $transaction['receiver_lastname']); ?></dd>

                <dt>Reason</dt>
                <dd><?php echo h($transaction['reason']); ?></dd>

                <dt>Date</dt>
                <dd><?php echo h(date("d/m/Y H:i", strtotime($transaction['created_at']))); ?></dd>
            </dl>

            <a class="button-link" href="dashboard.php">Back to dashboard</a>
        </section>
    </main>

</body>
</html>
