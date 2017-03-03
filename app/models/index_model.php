<?php

class Index_Model extends Model {

    function __construct() {
        parent::__construct();
    }

    public function addUserInfo($name, $email) {
        $dateTime = gmdate('Y-m-d H:i:s');
        $query = "INSERT INTO users (name, email, date_time) VALUES (?, ?, ?)";
        $statement = $this->database->prepare($query);
        $statement->bind_param('sss', $name, $email, $dateTime);
        $statement->execute();
        $statement->store_result();
        return $statement->affected_rows == 1;
    }

    public function emailExists($email) {
        $query = "SELECT 1 FROM users WHERE email = ?";
        $statement = $this->database->prepare($query);
        $statement->bind_param('s', $email);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows == 1;
    }

}
