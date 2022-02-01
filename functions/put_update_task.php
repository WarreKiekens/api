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
    
    if (!in_array($fields["taskid"], $trueTasks)){
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }    
      
    $values = array(
      "titel" => $fields["title"],
      //"omschrijving" => $fields["description"],
      //"aantalpuntenwaard" => $fields["totalpointsworth"],
      //"isuitgevoerd" => false,
      //"datumopgegeven" => $now["now"],
      //"foto" => $fields["picture"]
    ); 



    // unset all null values
    foreach($values as $key=>$value){
      if(is_null($value) || $value == '')
          unset($values[$key]);
    }
    
    echo json_encode($values);
    
    $result = pg_update($GLOBALS["conn"], "opdracht", $values, array("id" => $fields["taskid"]));
    
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200, "message" => "Successfully updated task");  
    
  };
?>
