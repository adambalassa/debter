<?php
header('Access-Control-Allow-Origin: *'); 
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Europe/Budapest");
include "vendor/autoload.php";
include "CoreApp/Enviroment.php";
\CoreApp\Session::init();
$app = new \CoreApp\App();
$app->startApp();
?>
