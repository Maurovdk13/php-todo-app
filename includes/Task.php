<?php

require_once("Db.php");

class Task {

    private $title;
    private $priority;
    private $listId;

    public function setTitle($title) {

        if(empty($title)) {
            throw new Exception("Task title cannot be empty");
        }

        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function setListId($listId) {
        $this->listId = $listId;
    }

    public function getListId() {
        return $this->listId;
    }

    public function create() {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            INSERT INTO tasks
            (list_id, title, priority)
            VALUES
            (:list_id, :title, :priority)
        ");

        $statement->bindValue(":list_id", $this->listId);
        $statement->bindValue(":title", $this->title);
        $statement->bindValue(":priority", $this->priority);

        return $statement->execute();
    }

    public static function getTasksByList($listId) {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            SELECT * FROM tasks
            WHERE list_id = :list_id

            ORDER BY
            CASE
                WHEN priority = 'high' THEN 1
                WHEN priority = 'medium' THEN 2
                ELSE 3
            END
        ");

        $statement->bindValue(":list_id", $listId);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function toggleStatus($id) {

    $conn = Db::getConnection();

    $statement = $conn->prepare("
        UPDATE tasks

        SET status =
        CASE
            WHEN status = 'todo' THEN 'done'
            ELSE 'todo'
        END

        WHERE id = :id
    ");

    $statement->bindValue(":id", $id);

    return $statement->execute();
}

public static function delete($id) {

    $conn = Db::getConnection();

    $statement = $conn->prepare("
        DELETE FROM tasks
        WHERE id = :id
    ");

    $statement->bindValue(":id", $id);

    return $statement->execute();
}

}