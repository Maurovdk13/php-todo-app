<?php

session_start();

require_once("../includes/User.php");
require_once("../includes/Transaction.php");

if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

function h($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

$error = "";
$success = "";
$currentUserId = $_SESSION['user']['id'];

if(!empty($_POST)) {
    try {
        $transaction = new Transaction();

        $transaction->setSenderId($currentUserId);
        $transaction->setReceiverId($_POST['receiver_id']);
        $transaction->setAmount($_POST['amount']);
        $transaction->setReason($_POST['reason']);
        $transaction->create();

        $success = "Transfer sent successfully.";
    } catch(Exception $e) {
        $error = $e->getMessage();
    }
}

$user = User::getById($currentUserId);
$transactions = Transaction::getAllByUser($currentUserId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XD Wallet</title>
    <link rel="stylesheet" href="../assets/css/app.css">
</head>
<body>

    <main class="wallet-page">
        <nav class="topbar">
            <a class="brand" href="dashboard.php">
                <span class="brand-coin">XD</span>
                XD Wallet
            </a>
            <a class="logout-link" href="logout.php">Logout</a>
        </nav>

        <section class="hero-panel">
            <div>
                <p class="eyebrow">Hello <?php echo h($user['firstname']); ?></p>
                <h1>Manage your XD coins like a crypto wallet</h1>
                <p class="hero-text">Send, receive and track your virtual currency in real time.</p>
            </div>

            <div class="balance-box">
                <span>Balance</span>
                <strong id="balanceAmount"><?php echo h(number_format($user['balance'], 2)); ?> XD</strong>
                <small>Live update every 10 seconds</small>
            </div>
        </section>

        <?php if($error): ?>
            <p class="message error"><?php echo h($error); ?></p>
        <?php endif; ?>

        <?php if($success): ?>
            <p class="message success"><?php echo h($success); ?></p>
        <?php endif; ?>

        <section class="dashboard-grid">
            <div class="panel">
                <h2>Send XD</h2>

                <form method="post" class="transfer-form" autocomplete="off">
                    <label for="userSearch">Recipient</label>
                    <input type="text" id="userSearch" placeholder="Search user..." required>
                    <input type="hidden" id="receiverId" name="receiver_id">
                    <div id="searchResults" class="search-results"></div>

                    <label for="amount">Amount</label>
                    <input type="number" id="amount" name="amount" min="1" step="0.01" placeholder="5" required>

                    <label for="reason">Reason</label>
                    <textarea id="reason" name="reason" maxlength="255" placeholder="Thanks for helping!" required></textarea>

                    <button type="submit">Send XD</button>
                </form>
            </div>

            <div class="panel">
                <h2>Recent transactions</h2>

                <?php if(empty($transactions)): ?>
                    <p class="empty-state">No transactions yet.</p>
                <?php endif; ?>

                <div class="transaction-list">
                    <?php foreach($transactions as $transaction): ?>
                        <?php
                            $isIncoming = $transaction['receiver_id'] == $currentUserId;
                            $otherName = $isIncoming
                                ? $transaction['sender_firstname'] . " " . $transaction['sender_lastname']
                                : $transaction['receiver_firstname'] . " " . $transaction['receiver_lastname'];
                            $sign = $isIncoming ? "+" : "-";
                            $typeClass = $isIncoming ? "incoming" : "outgoing";
                        ?>

                        <a class="transaction-item <?php echo $typeClass; ?>" href="transaction.php?id=<?php echo h($transaction['id']); ?>">
                            <div>
                                <strong><?php echo h($otherName); ?></strong>
                                <span><?php echo h(date("d/m/Y H:i", strtotime($transaction['created_at']))); ?></span>
                            </div>
                            <em><?php echo $sign . h(number_format($transaction['amount'], 2)); ?> XD</em>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
