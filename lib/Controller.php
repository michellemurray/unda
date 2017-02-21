<?php

class Controller {

    function __construct() {
        $this->view = new View();
        $this->db = new Database();
        Session::init();
    }

}
