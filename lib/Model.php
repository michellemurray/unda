<?php
class Model {

    function __construct() {
        $this->database = new Database();
    }

    public function isAdmin() {
        Session::init();
        if (Session::hasset("userid")) {
            $id = $_SESSION['userid'];
            $query = "SELECT uid FROM user_in_roles WHERE uid = ? AND role_id = 1 LIMIT 1";
            $statement = $this->database->prepare($query);
            $statement->bind_param('i', $id);
            $statement->execute();
            $statement->store_result();
            return $statement->num_rows == 1;
        }
    }

    public function hasAccess($id) {
        $query = "SELECT uid FROM user_in_roles WHERE uid = ? AND role_id != 4 AND role_id != 5 LIMIT 1";
        $statement = $this->database->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows == 1;
    }

    public function isTrainee() {
        Session::init();
        if (isset($_SESSION['userid'])) {
            $id = $_SESSION['userid'];
            $query = "SELECT uid FROM user_in_roles WHERE uid = ? AND role_id = 7 LIMIT 1";
            $statement = $this->database->prepare($query);
            $statement->bind_param('i', $id);
            $statement->execute();
            $statement->store_result();
            return $statement->num_rows == 1;
        }
        return false;
    }

    public function adminAccess() {
        Session::init();
        if (Session::hasset("userid")) {
            $id = $_SESSION['userid'];
            $query = "SELECT uid FROM user_in_roles WHERE uid = ? AND role_id = 1 LIMIT 1";
            $statement = $this->database->prepare($query);
            $statement->bind_param('i', $id);
            $statement->execute();
            $statement->store_result();
            if ($statement->num_rows == 0) {
                header("Location: " . URL);
            }
        } else {
            header("Location: " . URL . "login");
        }
    }

    public function getFirstName($id) {
        $query = "SELECT fname FROM users WHERE id = ?";
        $statement = $this->database->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $statement->store_result();
        if ($statement->num_rows > 0) {
            $statement->bind_result($first_name);
            $statement->fetch();
            return $first_name;
        }
    }

    public function getLastName($id) {
        $query = "SELECT sname FROM users WHERE id = ?";
        $statement = $this->database->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $statement->store_result();
        if ($statement->num_rows > 0) {
            $statement->bind_result($last_name);
            $statement->fetch();
            return $last_name;
        }
    }

    public function getEmail($id) {
        $query = "SELECT email FROM users WHERE id = ?";
        $statement = $this->database->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        $statement->store_result();
        if ($statement->num_rows > 0) {
            $statement->bind_result($email);
            $statement->fetch();
            return $email;
        }
    }

    public function getStudentInfo($active = false) {
        Session::init();
        $parent_id = $_SESSION['userid'];

        if ($active == false) {
            $query = "SELECT id, fname, sname, username, password FROM students WHERE parent_id = ?";
        } else {
            $query = "SELECT id, fname, sname, username, password FROM students WHERE parent_id = ? AND active = 0";
        }

        $statement = $this->database->prepare($query);
        $statement->bind_param('i', $parent_id);
        $statement->execute();
        $statement->store_result();
        if ($statement->num_rows > 0) {
            $statement->bind_result($id, $fname, $sname, $username, $password);
            $array = [];
            while($statement->fetch()) {
                $result = array(
                    "id" => $id,
                    "first_name" => $fname,
                    "last_name" => $sname,
                    "username" => $username,
                    "password" => $password,
                    "books" => $this->assignedBooks($id, $parent_id)
                );
                array_push($array, $result);
            }
            return $array;
        }
    }

    public function assignedBooks($student_id, $parent_id) {
        $query = "SELECT I.name FROM items I INNER JOIN book_users B On I.id = B.book_id WHERE B.student_id = ? AND B.parent_id = ? AND B.active = 1";
        $statement = $this->database->prepare($query);
        $statement->bind_param('ii', $student_id, $parent_id);
        $statement->execute();
        $statement->store_result();
        if($statement->num_rows > 0) {
            $statement->bind_result($name);
            $html = "";
            while($statement->fetch()) {
                $acronym = $this->getAcronym($name);
                $html .= "<div class='avatar' title='$name'><span>$acronym</span></div>";
            }
            return $html;
        }
    }

    private function getAcronym($name) {
        $name = explode(" ", $name);
        $str = "";
        for($i = 0; $i < count($name); $i++) {
            $str .= $name[$i][0];
        }
        return $str;
    }

    public function isBlocked($userid) {
        $query = "SELECT uid FROM user_in_roles WHERE uid = ? AND role_id = 5";
        $statement = $this->database->prepare($query);
        $statement->bind_param('i', $userid);
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows == 1;
    }

}
