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
    
    // check if task already ended
    $query = "SELECT isuitgevoerd from opdracht where id = $1";      
    $isuitgevoerd = fetch_query_params($query, array($fields["taskid"]))[0]["isuitgevoerd"];
    
    if(is_null($isuitgevoerd) || $isuitgevoerd == '' || $isuitgevoerd == "t") {
        return array("valid" => false, "code" => 422, "message" => "Task already ended, unable to update.", "error" => "UnprocessableEntity");
    }
    
    $values = array(
      "titel" => $fields["title"],
      "omschrijving" => $fields["description"],
      "aantalpuntenwaard" => $fields["totalpointsworth"],
      "isuitgevoerd" => false,
      "datumopgegeven" => $now["now"],
      "foto" => $fields["picture"],
      "winnaarid" => $fields["winnerid"],
      
    );

    // unset all null values
    foreach($values as $key=>$value){
      if(is_null($value) || $value == '')
          unset($values[$key]);
    }
    
    // If winner is set, add datumuitgevoerd,isuitgevoerd=>true,winnaarid
    if ($fields["winnerid"]) {
        $res = pg_query("select TO_CHAR(NOW(), 'YYYY-MM-DD HH:MI:SS') as now");
        $now = fetch_query_data($res)[0];
      
        $values["datumuitgevoerd"] = $now["now"];
        $values["isuitgevoerd"] = true;
        $values["winnaarid"] = $fields["winnerid"];
      
    }
    
    $result = pg_update($GLOBALS["conn"], "opdracht", $values, array("id" => $fields["taskid"]));
    
    // give right influencer points: winner post + validated posts
    if (is_numeric($fields["winnerid"])) {
      
      // Winner

      $query = "select SUM(aantalpunten + (select aantalpuntenwaard from opdracht where id = $1)) from influencer where id = $2";
      $winnervalue = fetch_query_params($query, array($fields["taskid"], $values["winnaarid"]))[0]["sum"];

      $winnerresult = pg_update($GLOBALS["conn"], "influencer", array("aantalpunten" => $winnervalue), array("id" => $values["winnaarid"]));
      
      
      // Validated posts
      $query = "select nietwinnaarreward from stad where id = $1";
      $validatedvalue = fetch_query_params($query, array($GLOBALS["account_id"]))[0]["nietwinnaarreward"];
      
      $query = "select influencerid from post where opdrachtid = $1 and isgoedgekeurd = true and influencerid != $2;";
      $validatedids = fetch_query_params($query, array($fields["taskid"], $values["winnaarid"]));
      
      echo json_encode(array($winnervalue, $validatedvalue, $validatedids));
    }
    
    if ($fields["categories"] != null) {
      pg_delete($GLOBALS["conn"], "opdrachtcategorie", array('opdrachtid' => $fields["taskid"]));
      
      
      foreach ($fields["categories"] as $categorieid) {
        if (!is_numeric($categorieid)){
          
          $query = "SELECT id from categorie where naam ilike $1 limit 1";      
          $categorieid = fetch_query_params($query, array($categorieid))[0]["id"];
          
          if ($categorieid == null) {
            pg_delete($GLOBALS["conn"], "opdrachtcategorie", array('opdrachtid' => $fields["taskid"]));
            
            return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed because given category doesn't exist!", "error" => "InternalError");  
          }
        }
        
        $result2 = pg_insert($GLOBALS["conn"], "opdrachtcategorie", array("opdrachtid" => $fields["taskid"],"categorieid" => $categorieid));
      }

    }
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200, "message" => "Successfully updated task");  
    
  };
?>
