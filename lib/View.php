<?php

class View {

    function __construct() {
        $this->model = new Model();
    }

    public function render($name) {
        require "./app/views/header.php";
        require "./app/views/$name.php";
        require "./app/views/footer.php";
    }

    public function partial($name) {
        ob_start();
        require "./app/views/$name.php";
        return ob_get_clean();
    }

}
