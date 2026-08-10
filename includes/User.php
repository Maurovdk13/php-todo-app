<?php

require_once(__DIR__ . "/Db.php");

class User {

    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $password;
    private $balance;

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    // FIRSTNAME
    public function setFirstname($firstname) {

        $firstname = trim($firstname);

        if(empty($firstname)) {
            throw new Exception("Firstname cannot be empty");
        }

        $this->firstname = $firstname;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    // LASTNAME
    public function setLastname($lastname) {

        $lastname = trim($lastname);

        if(empty($lastname)) {
            throw new Exception("Lastname cannot be empty");
        }

        $this->lastname = $lastname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    // EMAIL
    public function setEmail($email) {

        $email = trim(strtolower($email));

        if(empty($email)) {
            throw new Exception("Email cannot be empty");
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email is not valid");
        }

        if(!str_ends_with($email, "@student.thomasmore.be")) {
            throw new Exception("Email must end with @student.thomasmore.be");
        }

        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    // PASSWORD
    public function setPassword($password) {

        if(empty($password)) {
            throw new Exception("Password cannot be empty");
        }

        if(strlen($password) < 5) {
            throw new Exception("Password must be at least 5 characters");
        }

        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setBalance($balance) {
        if(!is_numeric($balance)) {
            throw new Exception("Balance must be a number");
        }

        $this->balance = number_format((float)$balance, 2, ".", "");
    }

    public function getBalance() {
        return $this->balance;
    }

    // REGISTER
    public function register() {

        $conn = Db::getConnection();

        if(self::emailExists($this->email)) {
            throw new Exception("Email already exists");
        }

        $hashedPassword = password_hash(
            $this->password,
            PASSWORD_DEFAULT
        );

        $statement = $conn->prepare("
            INSERT INTO users
            (firstname, lastname, email, password, balance)
            VALUES
            (:firstname, :lastname, :email, :password, :balance)
        ");

        $statement->bindValue(":firstname", $this->firstname);
        $statement->bindValue(":lastname", $this->lastname);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password", $hashedPassword);
        $statement->bindValue(":balance", "10.00");

        return $statement->execute();
    }

    public static function emailExists($email) {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            SELECT id FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $statement->bindValue(":email", $email);
        $statement->execute();

        return $statement->fetch() !== false;
    }

    public function login() {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            SELECT * FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $statement->bindValue(":email", $this->email);
        $statement->execute();

        $user = $statement->fetch();

        if(!$user) {
            throw new Exception("Email not found");
        }

        if(!password_verify($this->password, $user['password'])) {
            throw new Exception("Incorrect password");
        }

        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user'] = [
            "id" => $user['id'],
            "firstname" => $user['firstname'],
            "lastname" => $user['lastname'],
            "email" => $user['email']
        ];

        return true;
    }

    public static function getById($id) {

        $conn = Db::getConnection();

        $statement = $conn->prepare("
            SELECT id, firstname, lastname, email, balance, created_at
            FROM users
            WHERE id = :id
            LIMIT 1
        ");

        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public static function searchByName($query, $currentUserId) {

        $conn = Db::getConnection();
        $search = "%" . $query . "%";

        $statement = $conn->prepare("
            SELECT id, firstname, lastname
            FROM users
            WHERE id != :current_user_id
            AND (
                firstname LIKE :search
                OR lastname LIKE :search
                OR CONCAT(firstname, ' ', lastname) LIKE :search
            )
            ORDER BY firstname, lastname
            LIMIT 8
        ");

        $statement->bindValue(":current_user_id", $currentUserId, PDO::PARAM_INT);
        $statement->bindValue(":search", $search);
        $statement->execute();

        return $statement->fetchAll();
    }
}
