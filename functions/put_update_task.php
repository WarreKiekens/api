<?php
  include_once("../config.php");

  function put_update_task($fields){
    
    if (!is_numeric($fields["taskid"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
 
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))){
      if ($GLOBALS["account_id"] != $id) {
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
      }
    }
    
    // Check if account owns given id post + check if executed
    $query = "SELECT id from opdracht where stadid = $1";      
    $taskids = fetch_query_params($query, array($GLOBALS['account_id']));
    
    $trueTasks = array();
    foreach ($taskids as $taskid){ 
      array_push($trueTasks, $taskid["id"]);
    }
    
    if (!in_array($fiels["taskid"], $trueTasks)){
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }
    
    var_dump($trueTasks);
    echo "\n"; 
    var_dump($data);
    die();
    
      
    // TODO: validate input
    $values = array(
      "id" => $fields["taskid"],
      "winnaarid" => $fields["winnerid"],
      "gebruikersnaam" => $fields["username"],
      "wachtwoord" => $fields["password"],
      "naam" => $fields["name"],
      "postcode" => $fields["postcode"],
      "emailadres" => $fields["email"],
      "foto" => $fields["photo"]
    ); 



    // unset all null values
    foreach($values as $key=>$value){
      if(is_null($value) || $value == '')
          unset($values[$key]);
    }
    
    $result = pg_update($GLOBALS["conn"], "opdracht", $values, array("id" => $fields["postid"]));
    
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200, "message" => "Successfully updated task");  
    
  };
?>
