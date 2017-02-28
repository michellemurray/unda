<?php

class Controller {

    function __construct() {
        $this->view = new View();
    }

    public function model($model) {
        $lcmodel = strtolower($model);
        $path = "./app/models/$lcmodel.php";
        if (file_exists($path)) {
            require $path;
            $this->model = new $model();
        }
    }

}
