<?php

class Projects_Model extends CoreApp\Model {
  protected $project;
  protected static $db;
  protected $mainCurrency;
  protected $rounding;
  protected $email;
  public function __construct($id = "") {
    if(CoreApp\Session::get("projectid") != $id){
      session_unset();
      header('Location: /Index');
    }
    $this->project = $id;
    $this->initializeSettings();
  }
  public static function dbinit(){
    CoreApp\DB::restore();
    self::$db = CoreApp\DB::init(CoreApp\AppConfig::getData("appconfig_real", "database=>config=>DB_NAME"));
  }
  public function createProject($projectname){
    try{
      $projects = $this->getProjects();
      $this->project = Helpers\GlobalFunctions::codeGenerator($projects);
      $this->uploadProject($projectname);
      CoreApp\Session::set("status", 1);
      CoreApp\Session::set("projectid", $this->project);
      return ["error" => false, "projectid" => $this->project];
    }
    catch (\Exception $e) {
      die($e->getMessage());
    }
  }

  public function getSettings(){
    return ["maincurrency" => $this->mainCurrency, "rounding" => $this->rounding, "email" => $this->email];
  }

  public function editSettings($currency, $rounding, $email){
    try{
      $this->updateSetting("maincurrency", $currency);
      $this->updateSetting("rounding", $rounding);
      return ["error" => false];
    }
    catch(\Exception $e){
      die(json_encode(["error" => true, "message" => $e->getMessage()]));
    }
  }

  private function getProjects($bool = false){
    $stmt = self::$db->prepare("SELECT id as projectid FROM projects WHERE :bool OR id = :id");
    if($stmt->execute([":bool" => !$bool, ":id" => $this->project]))
      return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    throw new Exception(json_encode(["error" => true, "message" => "Database error"]));
  }
  private function uploadProject($title){
    $stmt = self::$db->prepare("INSERT INTO projects (id, title, modified) VALUES (:projectid, :title, :tme)");
    if(!$stmt->execute([":projectid" => $this->project, ":title" => $title, ":tme" => time()]))
      throw new Exception(json_encode(["error" => true, "message" => "Database error2"]));
  }
  public function joinProject($id){
    $this->project = $id;
    try {
      $project = $this->getProjects(true);
      CoreApp\Session::set('projectid', $id);
      CoreApp\Session::set('status', 2);
      $this->refresh();
      return empty($project) ? ["error" => true, "message" => "Invalid room id"] : ["error" => false, "projectid" => $id];
    }
    catch (\Exception $e) {
      die($e->getMessage());
    }
  }
  public function getProjectTitle(){
    return \Helpers\GlobalFunctions::getProjectTitle($this->project, self::$db);
  }
  public function enterProject($usernames){
    try{
      $users = $this->getUsers();
      foreach ($users as $user) {
        foreach ($usernames as $name) {
          if($user["username"] == $name)
            throw new \Exception(json_encode(["error" => true, "message" => "Name is already taken"]));
        }
      }
    }
    catch (\Exception $e){
      die($e->getMessage());
    }
    finally{
      $users = [];
      try{
        foreach ($usernames as $username)
          $users[$id = $this->uploadUser($username)] = ["userid" => $id, "username" => $username, "arrange" => []];
        $this->uploadBlankDebts($users);
      }
      catch (\Exception $e) {
        die($e->getMessage());
      }
      finally{
        CoreApp\Session::set("status", 2);
        return ["error" => false, "projectid" => $this->project];
      }
    }
  }

  public static function clean(){
    $stmt = self::$db->prepare("SELECT id FROM projects
      LEFT JOIN debts ON projects.id = debts.projectid
      WHERE modified < :maximumTime OR modified < :arrangedTime AND debts.arranged = 1");
    if($stmt->execute([":maximumTime" => time() - 22000000, ":arrangedTime" => time() - 6000000]))
      $projects = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    foreach ($projects as $project) {
      self::removeProject($project);
    }
  }

  protected function getUsers(){
    $stmt = self::$db->prepare("SELECT userid, username FROM users WHERE projectid = :id");
    if($stmt->execute([":id" => $this->project]))
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    throw new Exception(json_encode(["error" => true, "message" => "Database error"]));
  }
  private function uploadBlankDebts($users){
    $stmt = self::$db->prepare('INSERT INTO debts (projectid, debts, arranged) VALUES (:projectid, :debts, 1)');
    if(!$stmt->execute([":projectid" => $this->project, ":debts" => json_encode($users)]))
      throw new \Exception(json_encode(["error" => true, "message" => "Error occured inserting debts"]));
  }
  private function uploadUser($username){
    $stmt = self::$db->prepare("INSERT INTO users (projectid, username, userid) VALUES (:id, :username, :userid)");
    if($stmt->execute([":id" => $this->project, ":username" => $username, ":userid" => $val = Helpers\GlobalFunctions::v4()]))
      return $val;
    throw new Exception(json_encode(["error" => true, "message" => "Database error2"]));
  }
  private function refresh(){
    $stmt = self::$db->prepare("UPDATE projects SET modified = :tme WHERE id = :id");
    if(!$stmt->execute([":id" => $this->project, ":tme" => time()]))
      throw new Exception(json_encode(["error" => true, "message" => "Database error"]));
  }
  private static function removeProject($id){
    $stmt = self::$db->prepare("DELETE projects, users, debts, settings, payments FROM projects
      LEFT JOIN users ON projects.id = users.projectid
      LEFT JOIN payments ON projects.id = payments.projectid
      LEFT JOIN debts ON projects.id = debts.projectid
      LEFT JOIN settings ON projects.id = settings.projectid
       WHERE projects.id = :id");
    $stmt->execute([":id" => $id]);
  }
  private function initializeSettings(){
    $stmt = self::$db->prepare("SELECT type, value FROM settings WHERE projectid = :id");
    $stmt->execute([":id" => $this->project]);
    $settings = $stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_COLUMN);
    $this->mainCurrency = isset($settings["maincurrency"]) ? $settings["maincurrency"][0] : "HUF";
    $this->rounding = isset($settings["rounding"]) ? (float)$settings["rounding"][0] : 1;
    $this->email = isset($settings["email"]) ? $settings["email"] : [];
  }

  private function updateSetting($type, $value){
    $stmt = self::$db->prepare('SELECT type FROM settings WHERE projectid = :id AND type = :type');
    $stmt->execute([":id" => $this->project, ":type" => $type]);
    if(empty($stmt->fetchAll(PDO::FETCH_NUM))) return $this->uploadSetting($type, $value);

    $stmt = self::$db->prepare("UPDATE settings SET value = :value WHERE projectid = :id AND type = :type");
    $stmt->execute([":id" => $this->project, ":value" => $value, ":type" => $type]);
  }

  private function uploadSetting($type, $value){
    $stmt = self::$db->prepare("INSERT INTO settings (projectid, type, value) VALUES (:id, :type, :value)");
    $stmt->execute([":id" => $this->project, ":value" => $value, ":type" => $type]);
  }
}
