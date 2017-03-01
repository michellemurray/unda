<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->fileName = 'index';
    }

    public function index() {
        $this->view->intro = $this->intro();
        $this->view->more = $this->more();
        $this->view->pageBreak = $this->pageBreak();
        $this->view->plans = $this->plans();
        $this->view->features = $this->features();
        $this->view->signup = $this->signup();
        $this->view->render("index/index");
    }

    private function intro() {
        return $this->view->partial("index/_intro");
    }

    private function more() {
        return $this->view->partial("index/_more");
    }

    private function pageBreak() {
        return $this->view->partial("index/_pageBreak");
    }

    private function plans() {
        return $this->view->partial("index/_plans");
    }

    private function features() {
        return $this->view->partial("index/_features");
    }

    private function signup() {
        return $this->view->partial("index/_signup");
    }

    public function addUserInfo() {

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];

        $validation = $this->validateInput($firstName, $lastName, $email);

        if ($validation != 1) {
            echo $validation;
            return false;
        }

        if ($this->model->emailExists($email)) {
            echo json_encode(array('code' => 0, 'message' => 'Email already exists.'));
        } else {
            if ($this->model->addUserInfo($firstName, $lastName, $email)) {
                echo json_encode(array('code' => 1, 'message' => 'Thanks for your interest!'));
            } else {
                echo json_encode(array('code' => 0, 'message' => 'Failed to submit.'));
            }
        }
    }

    private function validateInput($firstName, $lastName, $email) {
        if ($firstName == NULL || trim($firstName, " ") == "") {
            return json_encode(array('code' => 0, 'message' => 'Name must not be left blank'));
        }

        if ($lastName == NULL || trim($lastName, " ") == "") {
            return json_encode(array('code' => 0, 'message' => 'Name must not be left blank'));
        }

        if ($email == NULL || trim($email, " ") == "") {
            return json_encode(array('code' => 0, 'message' => 'Email must not be left blank'));
        }

        return 1;
    }


}
