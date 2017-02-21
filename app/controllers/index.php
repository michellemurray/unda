<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->fileName = 'index';
    }

    public function index() {
        $this->view->render("index/index");
    }

}
