<?php

/*
The router requires that all urls with parameters to occur after the static urls
to avoid any conflicts.
eg. /url/:param must be after /url/static
*/
require "lib/View.php";
require "lib/Database.php";
require "lib/Model.php";
require "lib/Controller.php";
require "lib/Session.php";

require "config/router.php";
require "config/paths.php";

$app = new Router();

$app->route("/", "Index#index");

$app->checkURL();
