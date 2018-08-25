<?php
$router = new CoreApp\Router();
include_once _MODELS."Debts_Model"._PHP_ENDING;
Projects_Model::dbinit();
$router->post('getDebts', function(){
  $model = new Debts_Model($_POST["projectid"]);
  echo json_encode($model->returnDebts());
});
