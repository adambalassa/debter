<?php
include_once _MODELS."Payments_Model"._PHP_ENDING;

class Debts_Model extends Payments_Model {
  public function __construct($id = "") {
    parent::__construct($id);
  }
  public function uploadDebts(){
    try {
      $this->updateDebts(
        $debts = $this->arrangeDebts(
          $this->getDebts(
            $this->getUsersSum(),
            $this->getSum()
            )
          )
        );
      return ["error" => false, "debts" => array_values($debts)];
    }
    catch (\Exception $e) {
      return ["error" => true, "message" => $e->getMessage()];
    }
  }
  public function arrangeDebt($from, $to, $value){
    try {
      if($debts = $this->checkIfPaymentArrangesDebt($this->readDebts(), $from, $to, $value))
      {
        $this->updateDebts($debts);
        return ["error" => false, "debts" => $debts];
      }
      return $this->uploadDebts();
    }
    catch (\Exception $e) {
      return ["error" => true, "message" => $e->getMessage()];
    }
  }

  public function returnDebts(){
    try {
      $debts = $this->getDebts($this->getUsersSum(), $this->getSum());
      $arrangement = $this->readDebts();
      return ["error" => false, "arrangements" => array_values($arrangement), "debts" => $debts, "maincurrency" => $this->mainCurrency];
    }
    catch (\Exception $e) {
      die($e->getMessage());
    }
  }

  private function updateDebts($arr){
    $stmt = self::$db->prepare('UPDATE debts SET debts = :debts, arranged = :a WHERE projectid = :id');
    if(!$stmt->execute([":debts" => json_encode($arr), ":id" => $this->project, ":a" => $this->areDebtsArranged($arr)]))
    throw new \Exception(json_encode(["error" => true, "message" => "Error occured updating debts"]));
  }

  private function areDebtsArranged($debts){
    foreach ($debts as $user) {
      foreach ($user["arrange"] as $debt) {
        if(!$debt["done"]) return false;
      }
    }
    return true;
  }

  private function getDebts($debts, $sum){
    $returnArray = [];
    $forone = $sum / count($this->getUsers());
    foreach ($this->users as $userid => $user) {
      $returnArray["users"][$user["userid"]]["debt"] = (isset($debts[$user["userid"]]["sum"]) ? $debts[$user["userid"]]["sum"] : 0) - $forone;
      $returnArray["users"][$user["userid"]]["username"] = $user["username"];
      $returnArray["users"][$user["userid"]]["sum"] = (float)(isset($debts[$user["userid"]]["sum"]) ? $debts[$user["userid"]]["sum"] : 0);
      $returnArray["users"][$user["userid"]]["visiblesum"] = round((float)(isset($debts[$user["userid"]]["visiblesum"]) ? $debts[$user["userid"]]["visiblesum"] : 0), 2);
    }
    return $returnArray;
  }
  private function arrangeDebts($debts){

    $plus = []; $minus = [];
    $arranged = [];
    foreach ($debts["users"] as $userid => $user) {
      if($user["debt"] > 0){
        $plus[$userid]["debt"] = $user["debt"];
        $plus[$userid]["username"] = $user["username"];
      }
      else if($user["debt"] < 0){
        $minus[$userid] = abs($user["debt"]);
        $arranged[$userid] = ["userid" => $userid, "username" => $user["username"], "arrange" => []];
      }
    }
    for ($i=0; $i < 200 && !empty($minus) && !empty($plus); $i++) {
      arsort($plus); arsort($minus);
      foreach ($plus as $p => $plususer) {
        foreach ($minus as $m => $minususer) {
          if(abs($plususer["debt"] - $minususer) < 1.5 * $this->rounding){
            $toPay = round($minususer / $this->rounding) * $this->rounding;
            array_push($arranged[$m]["arrange"], ["userid" => $p, "username" => $plususer["username"], "value" => $toPay, "done" => !$toPay]);
            unset($minus[$m], $plus[$p]);
            continue 3;
          }
        }
      }
      foreach ($plus as $p => $plususer) {
        foreach ($minus as $m => $minususer) {
          if($minususer < $plususer["debt"]){
            $toPay = round($minususer / $this->rounding) * $this->rounding;
            array_push($arranged[$m]["arrange"], ["userid" => $p, "username" => $plususer["username"], "value" => $toPay, "done" => !$toPay]);
            unset($minus[$m]);
            $plus[$p]["debt"] -= $toPay;
            continue 3;
          }
        }
      }
      foreach ($plus as $p => $plususer) {
        foreach ($minus as $m => $minususer) {
          $toPay = round($plususer["debt"] / $this->rounding) * $this->rounding;
          array_push($arranged[$m]["arrange"], ["userid" => $p, "username" => $plususer["username"], "value" => $toPay, "done" => !$toPay]);
          unset($plus[$p]);
          $minus[$m] -= $toPay;
          continue 3;
        }
      }
    }
    return $arranged;
  }
  private function checkIfPaymentArrangesDebt($debts, $from, $to, $value){
    if(isset($debts[$from]))
      foreach ($debts[$from]["arrange"] as $key => $debt) {
        if($debt["userid"] == $to && $debt["value"] > $value - $this->rounding / 2 && $debt["value"] < $value + $this->rounding / 2){
          $debts[$from]["arrange"][$key]["done"] = true;
          return $debts;
        }
      }
    return false;
  }
  private function readDebts(){
    $stmt = self::$db->prepare("SELECT debts FROM debts WHERE projectid = :id");
    if($stmt->execute([":id" => $this->project]))
      return json_decode($stmt->fetchAll(PDO::FETCH_NUM)[0][0], true);
      throw new \Exception(json_encode(["error" => true, "message" => "Error occured downloading debts"]));
  }
}
