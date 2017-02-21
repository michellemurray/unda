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

}
