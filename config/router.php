<?php

class Router {

    private $_uri = array();
    private $urlFound = false;
    // Addes URIs to the class array
    public function add($uri) {
        $this->_uri[] = $uri;
    }
    // Checks if the URL is in the URI array
    public function checkURL() {
        $url = isset($_GET['url']) ? "/" . rtrim($_GET['url'], "/") : "/";
        if (!in_array($url, $this->_uri)) {
            $this->error();
        }
    }

    /*
    $url = the url
    $uri = the framework url e.g. /url/ur1 and /url/:param/:param2
    */
    public function route($uri, $path, $hiddenParams = false) {

        if ($this->urlFound) {
            return false;
        }

        $url = isset($_GET['url']) ? '/' . rtrim($_GET['url'], '/')  : '/';

        $compare = $this->compareSlashes($url, $uri);
        $contains_params = $this->hasParams($uri);

        if (!$compare) {
            return false;
        }
        // If the URI is the same as the URL in the browser, then add the URL to the URI array
        if ($uri == $url) {

            $this->add($uri);
            if (!$hiddenParams) {
                $this->path($path);
            } else {
                $this->path($path, $hiddenParams);
            }

        } else {

            if ($contains_params) {

                $param_array = $this->getParams($uri);

                if ($param_array[0] == null) {
                    $param_array[0] = "/";
                }

                if (strpos($url, $param_array[0]) > -1) {

                    $parameters = $this->filterParameters($param_array[0], $url);

                    $newurl = $param_array[0];
                    for ($i = 1; $i < count($param_array); $i++) {

                        $newurl .= '/' . $parameters[$i];

                        $params[$param_array[$i]] = $parameters[$i];
                    }

                    if ($newurl == $url) {
                        $this->add($url);
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
        $fileName = $array[0];
        $file = "app/controllers/$fileName.php";
        $model = $class . "_Model";

        if (file_exists($file)) {
            // Require the File once to avoid Fatal Error "Cannot redeclare class"
            require_once($file);

            if (class_exists($model)) {
                return false;
            }
            // Create a new object
            $controller = new $class();
            $controller->model($model);
            // If the method exists within the class
            if (method_exists($controller, $method)) {
                $controller->$method($params);
                $this->urlFound = true;
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
