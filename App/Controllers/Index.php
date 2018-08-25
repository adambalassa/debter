<?php
$router = new CoreApp\Router();

include_once _MODELS."Payments_Model"._PHP_ENDING;

  $router->get("", function() {
    header("Location: Index/en");
  });
  $router->get("Contact", function() {
    $view = new CoreApp\View("Contact");
    $view->files["include"] = 'Views/includes/notification.php';
    $view->render();
  });
  $router->get("About", function() {
    $view = new CoreApp\View("About");
    $view->files["include"] = 'Views/includes/notification.php';
    $view->render();
  });

  $router->get("(:language)", function($parameters) {
    \CoreApp\Session::set("language", $parameters["language"]);
    $view = new CoreApp\View("Index");
    $view->files["include"] = 'Views/includes/notification.php';
    $view->parameters["language"] = $parameters["language"];
    $view->render();
  });
