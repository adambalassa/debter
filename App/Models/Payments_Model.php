<?php
include_once _MODELS."Projects_Model"._PHP_ENDING;

class Payments_Model extends Projects_Model {
  protected $users = [];
  public $rates;
  public function __construct($id = "") {
    parent::__construct($id);
    $this->users = $this->getUsers();
    $this->rates = json_decode(file_get_contents("http://data.fixer.io/api/latest?access_key=5b84246bf4897159cfa64baa7eab657d&format=1"))->rates;
  }
  public function getPayments(){
    try{
      $payments = $this->getAllPayments(false);
      return array_merge(["error" => false], $this->getStatistics($payments));
    }
    catch(\Exception $e){
      die(json_encode(["error" => true, "message" => $e->getMessage()]));
    }
  }
  public function discard($user, $value, $tme, $discard){
    try {
      $this->discardPayment($user, $tme, $value, $discard);
      return ["error" => false];
    } catch (\Exception $e) {
      die(json_encode(["error" => true, "message" => $e->getMessage()]));
    }
  }
  public function getProjectsUsers(){
    try{
      return array_merge(["error" => false], $this->getStatistics($this->getAllPayments(false)));
    }
    catch(\Exception $e){
      die(json_encode(["error" => true, "message" => $e->getMessage()]));
    }
  }
  public function pay($from, $value, $note, $excluded, $currency){
    if(!isset($currency)) $currency = $this->mainCurrency;
    if($id = $this->arrangePayment($from, $value, $note, $excluded, $currency))
      return ["error" => false];
  }
  public function getDiscarded(){
    try {
      return ["error" => false, "payments" => $this->getDiscardedPayments()];
    } catch (\Exception $e) {
      die(json_encode(["error" => true, "message" => $e->getMessage()]));
    }
  }
  public function gotpayed($from, $to, $value, $note, $currency){
    try {
      if(!isset($currency)) $currency = $this->mainCurrency;
      $id = $this->uploadPayment($from, $value, $note, $currency);
      $this->uploadPayment($to, -1 * $value, $id, $currency);
      return ["error" => false];
    }
    catch (\Exception $e) {
      die(json_encode(["error" => true, "message" => $e->getMessage()]));
    }
  }
  public function getChartData(){
    try {
      $payments = $this->getPaymentsByDays();
      $chartData = $this->getChartArray($payments);
      sort($chartData["values"]); $values = $chartData["values"]; unset($chartData["values"]);
      return ["error" => false, "values" => $values, "chart" => $chartData, "maincurrency" => $this->mainCurrency];
    }
    catch (\Exception $e) {
      die(json_encode(["error" => true, "message" => $e->getMessage()]));
    }
  }
  private function getExcluded($id){
    $stmt = self::$db->prepare("SELECT users.userid FROM payments INNER JOIN users ON users.userid = payments.userid
       WHERE note = :paymentid AND value > 0 AND NOT discarded");
    if($stmt->execute([":paymentid" => $id]))
      return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    throw new \Exception("Error occured loading excluded");

  }
  private function deletePayment($id){
    $stmt = self::$db->prepare("DELETE FROM payments WHERE id = :id");
    if(!$stmt->execute(["id" => $id]))
      throw new \Exception("Error occured executing delete query");
  }
  private function arrangePayment($from, $value, $note, $excluded, $currency){
    try{
      if($value <= 0 || $note == "")
        throw new \Exception("Invalid data");
      $id = $this->uploadPayment($from, $value, $note, $currency);
      $payment = $value / (count($this->users) - count($excluded));
      foreach ($excluded as $user)
        $this->uploadPayment($user, (float)$payment, $id, $currency, false);
      return $id;
    }
    catch(\Exception $e){
      die(json_encode(["error" => true, "message" => $e->getMessage()]));
    }
  }
  private function getUserName($id){
    $stmt = self::$db->prepare("SELECT userid, username FROM users WHERE userid = :id AND projectid = :projectid");
    if($stmt->execute([":id" => $id, ":projectid" => $this->project]))
      if(!$res = $stmt->fetchAll(PDO::FETCH_ASSOC))
        throw new \Exception("Invalid userid");
      else return $res;
    throw new \Exception("Error executing query");
  }
  private function uploadPayment($from, $value, $note, $currency, $visible = true){
    $this->getUserName($from);
    $stmt = self::$db->prepare("INSERT INTO payments (id, projectid, userid, valuta, value, note, tme, visible)
    VALUES(:id, :projectid, :userid, :valuta, :value, :note, :tme, :visible)");
    if($stmt->execute([
        ":id" => $id = Helpers\GlobalFunctions::v4(),
        ":projectid" => $this->project,
        ":userid" => $from,
        ":valuta" => $currency,
        ":value" => $value,
        ":note" => $note,
        ":tme" => time(),
        ":visible" => (int)$visible
      ]))
      return $id;
    throw new \Exception("Uploading to database failed");
  }
  protected function getSum(){
    $stmt = self::$db->prepare("SELECT value, valuta FROM payments
    WHERE projectid = :id AND NOT discarded");
    $sum = 0;
    if($stmt->execute([":id" => $this->project])){
      $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($payments as $payment) $sum += $this->convertCurrency($payment["valuta"], ($this->mainCurrency), $payment["value"]);
      return $sum;
    }
    throw new \Exception("Database Error downloading payments");
  }

  protected function getUsersSum(){
    $stmt = self::$db->prepare('SELECT users.userid, value, valuta, users.username, visible FROM payments
    INNER JOIN users ON users.userid = payments.userid WHERE payments.projectid = :projectid  AND NOT discarded');
    if($stmt->execute([":projectid" => $this->project])){
      $users = [];
      $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($payments as $payment)
        if(isset($users[$payment["userid"]])){
          $val = $this->convertCurrency($payment["valuta"], ($this->mainCurrency), $payment["value"]);
          $users[$payment["userid"]]["sum"] += $val;
          $users[$payment["userid"]]["visiblesum"] += $payment["visible"] ? $val : 0;
        }
        else
          $users[$payment["userid"]] = [
            "sum" => $this->convertCurrency($payment["valuta"], ($this->mainCurrency), $payment["value"]),
            "username" => $payment["username"],
            "userid" => $payment["userid"],
            "visiblesum" => $payment["visible"] ? $this->convertCurrency($payment["valuta"], ($this->mainCurrency), $payment["value"]) : 0
          ];
      return $users;
    }
    throw new \Exception(json_encode(["error" => true, "message" => "Error occured downloading userdebts"]));
  }

  private function getAllPayments($innerRequest = true){
    $stmt = self::$db->prepare("SELECT valuta, value, note, tme, visible, id, users.userid, users.username
    FROM payments INNER JOIN users ON users.userid = payments.userid WHERE payments.projectid = :id AND NOT discarded AND (visible = 1 OR :bool) ORDER BY tme");
    if($stmt->execute([":id" => $this->project, ":bool" => $innerRequest]))
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    throw new \Exception("Database Error downloading payments");
  }
  private function getStatistics($payments){
    $returnArray = [];
    $returnArray["sum"] = 0;
    $returnArray["users"] = [];
    $returnArray["payments"] = $payments;
    foreach ($this->users as $user){
      $returnArray["users"][$user["userid"]] = [];
      $returnArray["users"][$user["userid"]]["payments"] = [];
      $returnArray["users"][$user["userid"]]["name"] = $user["username"];
      $returnArray["users"][$user["userid"]]["sum"] = 0;
      $returnArray["users"][$user["userid"]]["realsum"] = 0;
    }
    foreach ($payments as $key => $payment) {
      $returnArray["payments"][$key]["note"] = ((float)$payment["value"] > 0 ? $payment["note"] : $this->getOriginal($payment["note"]));
      $returnArray["users"][$payment["userid"]]["sum"] += $this->convertCurrency($payment["valuta"], ($this->mainCurrency), $payment["value"]);
      $returnArray["users"][$payment["userid"]]["realsum"] += $this->convertCurrency($payment["valuta"], ($this->mainCurrency), $payment["value"] * (bool)$payment["visible"]);
      $returnArray["sum"] += $this->convertCurrency($payment["valuta"], ($this->mainCurrency), $payment["value"] * (bool)$payment["visible"]);
      $excluded = $this->getExcluded($payment["id"]);
      $returnArray["payments"][$key]["excluded"] = $excluded;
      if((bool)$payment["visible"])
        array_push($returnArray["users"][$payment["userid"]]["payments"], [
          "value" => $payment["value"],
          "note" =>((float)$payment["value"] > 0 ? $payment["note"] : $this->getOriginal($payment["note"])),
          "tme" => $payment["tme"],
          "excluded" => $excluded,
          "valuta" => $payment["valuta"]
        ]);
        unset($returnArray["payments"][$key]["id"]);
    }
    $returnArray["maincurrency"] = $this->mainCurrency;
    return $returnArray;
  }
  private function getOriginal($id){
    $stmt = self::$db->prepare("SELECT note FROM payments WHERE id = :id AND projectid = :projid AND NOT discarded");
    if($stmt->execute([":id" => $id, ":projid" => $this->project]))
      return $stmt->fetchAll(PDO::FETCH_ASSOC)[0]["note"];
    throw new \Exception("Errors loading original note");
  }
  private function discardPayment($user, $tme, $val, $redo){
    $stmt = self::$db->prepare("SELECT id, note FROM payments WHERE tme = :tme AND userid = :user AND value = :val");
    if($stmt->execute([":tme" => $tme, ":user" => $user, ":val" => $val]) && $res = $stmt->fetchAll(PDO::FETCH_ASSOC))
        $res =  $res[0];
    else
      throw new \Exception("Invalid data");
    $stmt = self::$db->prepare("UPDATE payments SET discarded = :d WHERE id = :id OR id = :note OR note = :id");
    if(!$stmt->execute([":id" => $res["id"], ":note" => $res["note"], ":d" => $redo]))
      throw new \Exception("Error discarding payment(s)");
  }
  private function getDiscardedPayments($innerRequest = false){
    $stmt = self::$db->prepare("SELECT id, valuta, value, note, tme, visible, users.userid, users.username
    FROM payments INNER JOIN users ON users.userid = payments.userid WHERE payments.projectid = :id AND discarded AND (visible = 1 OR :bool) ORDER BY tme");
    if($stmt->execute([":id" => $this->project, ":bool" => $innerRequest]))
      return $stmt->fetchAll(PDO::FETCH_UNIQUE|PDO::FETCH_ASSOC);
    throw new \Exception("Database Error downloading discarded payments");
  }
  private function getPaymentsByDays(){
    $stmt = self::$db->prepare("SELECT value, tme, users.userid, users.username FROM payments INNER JOIN users ON users.userid = payments.userid
    WHERE payments.projectid = :id AND visible = 1 AND value > 0 ORDER BY tme");
    if($stmt->execute([":id" => $this->project]))
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    throw new \Exception("Database Error downloading discarded payments");
  }
  private function getChartArray($payments){
    $returnArray = [];
    $i = 0;
    $returnArray["values"] = [];
    foreach ($payments as $payment) {
      $year = date('Y', $payment["tme"]); $day = date('z', $payment["tme"]);
      if(!isset($returnArray[$i])) {
        $returnArray[$i] = [];
        $returnArray[$i]["payments"] = [];
        array_push($returnArray["values"], (float)$payment["value"]);
      }
      else if($day != $returnArray[$i]["day"] || $returnArray[$i]["year"] != $year) {
        $i++; $returnArray[$i] = [];
        $returnArray[$i]["payments"] = [];
        array_push($returnArray["values"], (float)$payment["value"]);
      }
      else $returnArray["values"][count($returnArray["values"]) - 1] += (float)$payment["value"];
      $returnArray[$i]["day"] = $day; $returnArray[$i]["year"] = $year; $returnArray[$i]["time"] = $payment["tme"];
      if(!isset($returnArray[$i]["payments"][$payment["userid"]]))
        $returnArray[$i]["payments"][$payment["userid"]] = [
          "value" => (float)$payment["value"],
          "username" => $payment["username"]
        ];
      else
        $returnArray[$i]["payments"][$payment["userid"]]["value"] += $payment["value"];
    }
    return $returnArray;
  }
  public function convertCurrency($from, $to, $value){
    if($from == $to) return $value;
    foreach ($this->rates as $currency => $exchangeRate) {
      if($currency == $from)
        $fromRate = $exchangeRate;
      else if($currency == $to)
        $toRate = $exchangeRate;
    }
    return round($value * $toRate / $fromRate);
  }
}
