<?php

class Router {

    private $_uri = array();

    public function add($uri) {
        $this->_uri[] = $uri;
    }

    public function checkURL() {
        $url = isset($_GET['url']) ? "/" . $_GET['url'] : "/";
        if (!in_array($url, $this->_uri)) {
            $this->error();
        }
    }

    public function route($uri, $path) {
        $url = isset($_GET['url']) ? "/" . $_GET['url'] : "/";
        $url = strlen($url) > 1 ? rtrim($url, "/") : "/";
        $compare = $this->compareSlashes($url, $uri);
        $contains_params = $this->hasParams($uri);

        $this->add($uri);

        if (!$compare) {
            return false;
        }

        if ($uri == $url) {

            $this->path($path);

        } else {

            if ($contains_params) {

                $param_array = $this->getParams($uri);

                if (strpos($url, $param_array[0]) > -1) {

                    $parameters = $this->filterParameters($param_array[0], $url);

                    $newurl = $param_array[0];

                    for ($i = 1; $i < count($param_array); $i++) {
                        $newurl .= "/" . $parameters[$i];
                        $params[$param_array[$i]] = $parameters[$i];
                    }

                    if ($newurl == $url) {
                        $this->path($path, $params);
                    } else {
                        $this->error();
                    }

                }

            }

        }

    }

    private function filterParameters($url_base, $url) {
        $array = array_filter(explode($url_base, $url));
        $result = explode("/", $array[1]);
        return array_filter($result);
    }

    // Checks to see if the url has any parameters
    private function hasParams($url) {
        return strpos($url, ":") > -1;
    }
    // Takes in the url and explodes it into an array
    // The first element is the url path, all other
    // elements are added parameters
    private function getParams($url) {
        return explode("/:", $url);
    }

    private function compareSlashes($dir, $url) {
        $dir = array_filter(explode("/", $dir));
        $url = array_filter(explode("/", $url));

        return count($dir) == count($url);
    }

    private function path($path, $params = false) {

        $array = explode("#", $path);
        $class = ucfirst($array[0]);

        $method = $array[1];

        $file = "app/controllers/$class.php";

        if (file_exists($file)) {
            // Require the File once to avoid Fatal Error "Cannot redeclare class"
            require($file);
            // Create a new object
            $c = new $class();
            // If the method exists within the class
            if (method_exists($c, $method)) {
                // Use the method to show the view
                $c->$method($params);
                return false;
            } else {
                // Method does not exist
                $this->error();
                return false;
            }
        } else {
            // Class does not exist
            $this->error();
            return false;
        }

    }

    private function error() {
        require_once("./public/error/404.php");
    }

}
