<?php

namespace Helpers;

class GlobalFunctions
{
  public function __construct(){
    self::$db = \CoreApp\DB::init(\CoreApp\AppConfig::getData("appconfig_real", "database=>config=>DB_NAME"));
  }
  public static function v4() {
    return sprintf('%04x-%04x-%04x-%04x',mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff),mt_rand(0, 0xffff));
  }
  public static function codeGenerator($banned){
    $length = 6;
    do{
      $bool = false;
      $str = substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )), 1 ,$length);
      foreach ($banned as $value) {
        if($str == $value)
          $bool = true;
      }
    }while($bool);
    return $str;
  }
  public static function getProjectTitle($id, $db){
    $stmt = $db->prepare("SELECT title FROM projects WHERE id = :id");
    return $stmt->execute([":id" => $id]) ? $stmt->fetchAll(\PDO::FETCH_ASSOC)[0]['title'] : false;
  }
  public static function countDebts($id){
    include_once _MODELS."Debts_Model"._PHP_ENDING;
    $debts = new \Debts_Model($id);
    return !$debts->uploadDebts()["error"];
  }
  public static function updateDebts($id, $from, $to, $value){
    include_once _MODELS."Debts_Model"._PHP_ENDING;
    $debts = new \Debts_Model($id);
    return !$debts->arrangeDebt($from, $to, $value)["error"];
  }
}
