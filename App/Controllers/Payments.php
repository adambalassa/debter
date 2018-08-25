<?php
$router = new CoreApp\Router();
include_once _MODELS."Payments_Model"._PHP_ENDING;
//BUG: INSECURE
Projects_Model::dbinit();
$router->post('getUsers', function(){
  $model = new Payments_Model($_POST["projectid"]);
  echo json_encode($model->getProjectsUsers());
});
$router->post('undo', function(){
  $model = new Payments_Model($_POST["projectid"]);
  echo json_encode($model->discard($_POST["user"], $_POST["value"], $_POST["tme"], (int)$_POST["discard"] ? 1 : 0));
  Helpers\GlobalFunctions::countDebts($_POST["projectid"]);
});
$router->post('getPayments', function(){
  $model = new Payments_Model($_POST["projectid"]);
  echo json_encode($model->getPayments());
});
$router->post('getDiscarded', function(){
  $model = new Payments_Model($_POST["projectid"]);
  echo json_encode($model->getDiscarded());
});
$router->post('pay', function(){
  $model = new Payments_Model($_POST["projectid"]);
  echo json_encode($model->pay($_POST["from"], $_POST["value"], $_POST["note"], isset($_POST["excluded"]) ? $_POST["excluded"] : [], $_POST["currency"]));
  Helpers\GlobalFunctions::countDebts($_POST["projectid"]);
});
$router->post('gotpayed', function(){
  $model = new Payments_Model($_POST["projectid"]);
  echo json_encode($model->gotpayed($_POST["from"], $_POST["to"], $_POST["value"], $_POST["note"], isset($_POST["currency"]) ? $_POST["currency"] : NULL));
  Helpers\GlobalFunctions::updateDebts($_POST["projectid"], $_POST["from"], $_POST["to"], $_POST["value"]);
});
$router->post('getChart', function(){
  $model = new Payments_Model($_POST["projectid"]);
  echo json_encode($model->getChartData());
});
