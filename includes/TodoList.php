<?php

require_once("Db.php");

class TodoList {

    private $title;
    private $userId;

    public function setTitle($title) {

        if(empty($title)) {
            throw new Exception("Title cannot be empty");
        }

        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function create() {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            INSERT INTO lists
            (user_id, title)
            VALUES
            (:user_id, :title)
        ");

        $statement->bindValue(":user_id", $this->userId);
        $statement->bindValue(":title", $this->title);

        return $statement->execute();
    }

    public static function getAllByUser($userId) {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            SELECT * FROM lists
            WHERE user_id = :user_id
        ");

        $statement->bindValue(":user_id", $userId);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {

    $conn = Db::getConnection();

    $statement = $conn->prepare("
        SELECT * FROM lists
        WHERE id = :id
    ");

    $statement->bindValue(":id", $id);

    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
}

}