<?php

class Index extends Controller {

    function __construct() {
        parent::__construct();
        $this->view->fileName = 'index';
    }

    public function index() {
        $this->view->intro = $this->intro();
        $this->view->render("index/index");
    }

    private function intro() {
        return $this->view->partial("index/_intro");
    }

}
