<?php

require_once("Db.php");

class User {

    private $firstname;
    private $lastname;
    private $email;
    private $password;

    // FIRSTNAME
    public function setFirstname($firstname) {

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

        if(empty($email)) {
            throw new Exception("Email cannot be empty");
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

        $this->password = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    // REGISTER
    public function register() {

        $conn = Db::getConnection();

        $hashedPassword = password_hash(
            $this->password,
            PASSWORD_DEFAULT
        );

        $statement = $conn->prepare("
            INSERT INTO users
            (firstname, lastname, email, password)
            VALUES
            (:firstname, :lastname, :email, :password)
        ");

        $statement->bindValue(":firstname", $this->firstname);
        $statement->bindValue(":lastname", $this->lastname);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password", $hashedPassword);

        return $statement->execute();
    }
}