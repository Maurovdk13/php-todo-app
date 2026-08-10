<?php

require_once(__DIR__ . "/Db.php");

class Transaction {

    private $senderId;
    private $receiverId;
    private $amount;
    private $reason;

    public function setSenderId($senderId) {
        if(!is_numeric($senderId)) {
            throw new Exception("Sender is not valid");
        }

        $this->senderId = (int)$senderId;
    }

    public function getSenderId() {
        return $this->senderId;
    }

    public function setReceiverId($receiverId) {
        if(!is_numeric($receiverId)) {
            throw new Exception("Receiver is not valid");
        }

        $this->receiverId = (int)$receiverId;
    }

    public function getReceiverId() {
        return $this->receiverId;
    }

    public function setAmount($amount) {
        if(!is_numeric($amount)) {
            throw new Exception("Amount must be a valid number");
        }

        if($amount < 1) {
            throw new Exception("A transfer must be at least 1 XD");
        }

        $this->amount = number_format((float)$amount, 2, ".", "");
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setReason($reason) {
        $reason = trim($reason);

        if(empty($reason)) {
            throw new Exception("Reason cannot be empty");
        }

        if(strlen($reason) > 255) {
            throw new Exception("Reason can be maximum 255 characters");
        }

        $this->reason = $reason;
    }

    public function getReason() {
        return $this->reason;
    }

    public function create() {

        if($this->senderId === $this->receiverId) {
            throw new Exception("You cannot send tokens to yourself");
        }

        $conn = Db::getConnection();

        try {
            $conn->beginTransaction();

            $sender = self::getUserForUpdate($this->senderId);
            $receiver = self::getUserForUpdate($this->receiverId);

            if(!$sender || !$receiver) {
                throw new Exception("User not found");
            }

            if((float)$sender['balance'] < (float)$this->amount) {
                throw new Exception("You do not have enough balance");
            }

            $removeBalance = $conn->prepare("
                UPDATE users
                SET balance = balance - :amount
                WHERE id = :sender_id
            ");

            $removeBalance->bindValue(":amount", $this->amount);
            $removeBalance->bindValue(":sender_id", $this->senderId, PDO::PARAM_INT);
            $removeBalance->execute();

            $addBalance = $conn->prepare("
                UPDATE users
                SET balance = balance + :amount
                WHERE id = :receiver_id
            ");

            $addBalance->bindValue(":amount", $this->amount);
            $addBalance->bindValue(":receiver_id", $this->receiverId, PDO::PARAM_INT);
            $addBalance->execute();

            $statement = $conn->prepare("
                INSERT INTO transactions
                (sender_id, receiver_id, amount, reason)
                VALUES
                (:sender_id, :receiver_id, :amount, :reason)
            ");

            $statement->bindValue(":sender_id", $this->senderId, PDO::PARAM_INT);
            $statement->bindValue(":receiver_id", $this->receiverId, PDO::PARAM_INT);
            $statement->bindValue(":amount", $this->amount);
            $statement->bindValue(":reason", $this->reason);
            $statement->execute();

            $conn->commit();

            return true;
        } catch(Exception $e) {
            if($conn->inTransaction()) {
                $conn->rollBack();
            }

            throw $e;
        }
    }

    private static function getUserForUpdate($userId) {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            SELECT id, balance
            FROM users
            WHERE id = :id
            FOR UPDATE
        ");

        $statement->bindValue(":id", $userId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public static function getAllByUser($userId) {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            SELECT
                transactions.*,
                sender.firstname AS sender_firstname,
                sender.lastname AS sender_lastname,
                receiver.firstname AS receiver_firstname,
                receiver.lastname AS receiver_lastname
            FROM transactions
            INNER JOIN users AS sender ON transactions.sender_id = sender.id
            INNER JOIN users AS receiver ON transactions.receiver_id = receiver.id
            WHERE transactions.sender_id = :user_id
            OR transactions.receiver_id = :user_id
            ORDER BY transactions.created_at DESC
        ");

        $statement->bindValue(":user_id", $userId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function getByIdForUser($id, $userId) {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            SELECT
                transactions.*,
                sender.firstname AS sender_firstname,
                sender.lastname AS sender_lastname,
                receiver.firstname AS receiver_firstname,
                receiver.lastname AS receiver_lastname
            FROM transactions
            INNER JOIN users AS sender ON transactions.sender_id = sender.id
            INNER JOIN users AS receiver ON transactions.receiver_id = receiver.id
            WHERE transactions.id = :id
            AND (
                transactions.sender_id = :user_id
                OR transactions.receiver_id = :user_id
            )
            LIMIT 1
        ");

        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->bindValue(":user_id", $userId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}
