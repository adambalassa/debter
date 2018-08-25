<?php
$router = new CoreApp\Router();
include_once _MODELS."Projects_Model"._PHP_ENDING;
Projects_Model::dbinit();

$router->post('create', function(){
  $lang = CoreApp\Session::get("language");
  session_unset();
  CoreApp\Session::set("language", $lang);
  $model = new Projects_Model();
  echo json_encode($model->createProject($_POST["projectname"]));
});

$router->post('join', function(){
  $lang = CoreApp\Session::get("language");
  session_unset();
  CoreApp\Session::set("language", $lang);
  $model = new Projects_Model();
  $res = $model->joinProject(strtoupper($_POST["projectid"]));
  if($res["error"] && isset($_COOKIE["debter_projects"])){
    $projects = explode('&', $_COOKIE["debter_projects"]);
    array_splice($projects, array_search($_POST["projectid"], $projects), 1);
    setcookie("debter_projects", implode("&", $projects), time() + (86400 * 100), "/");
  }
  echo json_encode($res);
});

$router->post('enter', function(){
  $model = new Projects_Model(CoreApp\Session::get("projectid"));
  echo json_encode($model->enterProject($_POST["usernames"]));
});

$router->get("(:projectid)", function($param) {
  $model = new Projects_Model($param["projectid"]);
  if(isset($_COOKIE["debter_projects"])){
    $projects = $_COOKIE["debter_projects"];
    if(!in_array($param["projectid"], explode('&', $projects))){
      setcookie("debter_projects", $projects."&".$param["projectid"], time() + (86400 * 100), "/");
    }
    else{
      $projects = explode('&', $projects);
      array_splice($projects, array_search($param["projectid"], $projects), 1);
      array_unshift($projects, $param["projectid"]);
      setcookie("debter_projects", implode("&", $projects), time() + (86400 * 100), "/");
    }
  }
  else
    setcookie("debter_projects", $param["projectid"], time() + (86400 * 100), "/");
  switch (CoreApp\Session::get("status")) {
    case 1:
      $page = "Join";
      break;
    case 2:
      $page = "Project";
      break;
    default:
      unset($_SESSION["status"]);
      header('Location: /Index');
      break;
  }
  $view = new CoreApp\View($page);
  $view->parameters["projectname"] = $model->getProjectTitle();
  $view->parameters["projectid"] = $param["projectid"];
  $view->parameters["language"] = CoreApp\Session::get('language');
  $view->files["include"] = 'Views/includes/notification.php';
  $view->render();
});

$router->get("(:projectid)/history", function($param){
  if(CoreApp\Session::get("status") != 2){
    unset($_SESSION["status"]);
    header('Location: /Index');
  }
  $model = new Projects_Model($param["projectid"]);
  $view = new CoreApp\View("History");
  $view->parameters["projectname"] = $model->getProjectTitle();
  $view->parameters["projectid"] = $param["projectid"];
  $view->parameters["language"] = CoreApp\Session::get('language');
  $view->files["include"] = 'Views/includes/notification.php';
  $view->render();
});

$router->get("(:projectid)/payments", function($param){
  if(CoreApp\Session::get("status") != 2){
    unset($_SESSION["status"]);
    header('Location: /Index');
  }
  $model = new Projects_Model($param["projectid"]);
  $view = new CoreApp\View("Payments");
  $view->parameters["projectname"] = $model->getProjectTitle();
  $view->parameters["projectid"] = $param["projectid"];
  $view->parameters["language"] = CoreApp\Session::get('language');
  $view->files["include"] = 'Views/includes/notification.php';
  $view->render();
});

$router->get("(:projectid)/debts", function($param){
  if(CoreApp\Session::get("status") != 2){
    unset($_SESSION["status"]);
    header('Location: /Index');
  }
  $model = new Projects_Model($param["projectid"]);
  $view = new CoreApp\View("Debts");
  $view->parameters["projectname"] = $model->getProjectTitle();
  $view->parameters["projectid"] = $param["projectid"];
  $view->parameters["language"] = CoreApp\Session::get('language');
  $view->files["include"] = 'Views/includes/notification.php';
  $view->render();
});

$router->get("(:projectid)/settings", function($param){
  if(CoreApp\Session::get("status") != 2){
    unset($_SESSION["status"]);
    header('Location: /Index');
  }
  $model = new Projects_Model($param["projectid"]);
  $view = new CoreApp\View("Settings");
  $view->parameters["projectname"] = $model->getProjectTitle();
  $view->parameters["projectid"] = $param["projectid"];
  $view->parameters["language"] = CoreApp\Session::get('language');
  $view->files["include"] = 'Views/includes/notification.php';
  $view->render();
});

$router->post("changeLang", function(){
  $lang = CoreApp\Session::get("language");
  if($lang == "hu")
    CoreApp\Session::set("language", "en");
  else if($lang == "en")
    CoreApp\Session::set("language", "hu");
});

$router->post("clean", function(){
  Projects_Model::clean();
});

$router->post("(:projectid)/settings", function($param){
  $model = new Projects_Model($param["projectid"]);
  echo json_encode($model->getSettings());
});

$router->post("editSettings", function(){
  $model = new Projects_Model($_POST["projectid"]);
  echo json_encode($model->editSettings($_POST["maincurrency"], $_POST["rounding"], $_POST["email"]));
  \Helpers\GlobalFunctions::countDebts($_POST["projectid"]);
});
