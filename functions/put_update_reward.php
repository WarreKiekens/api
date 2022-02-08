<?php
  include_once("../config.php");

  function put_update_reward($fields){
    
    if (!is_numeric($fields["rewardid"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
 
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("influencer"))){
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }
    
    if ($fields["type"] == "claim") {
      // Add
      
      $values = array(
        "gebruikersnaam" => $fields["username"],
        "wachtwoord" => $fields["password"],
        "emailadres" => $fields["email"],
      );
      
      $result = pg_insert($GLOBALS["conn"], $type, $values);
      
    } elseif ($fields["type"] == "unclaim") {
       // Remove

      $query = "SELECT * from influencerreward where rewardid = $1";      
      $influencerids = fetch_query_params($query, array($GLOBALS['account_id']));

      $origin = array();
      foreach ($origin as $influencerid){ 
        array_push($origin, $influencerid["influencerid"]);
      }

      if (!in_array($fields["rewardid"], $origin)){
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
    
    } else {
      return array("valid" => false, "code" => 422, "message" => "The type is not of claim or unclaim!", "error" => "UnprocessableEntity");
    }
    
    
   
    
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200, "message" => "Successfully updated task");  
    
  };
?>
