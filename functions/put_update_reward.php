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
      // Check if enough points
      $query = "select SUM(aantalpunten - (select tegoed from reward where id = $1)) from influencer where id = $2";
      $subtract = fetch_query_params($query, array($fields["rewardid"], $GLOBALS["account_id"]))[0]["sum"];
      
      if ($subtract < 0) {
        return array("valid" => false, "code" => 505, "message" => "You don't have enough points to claim this reward!", "error" => "InsufficientFunds");
      }
      
      $values = array(
        "rewardid" => $fields["rewardid"],
        "influencerid" => $GLOBALS["account_id"],
      );
      
      $result = pg_insert($GLOBALS["conn"], "influencerreward", $values);
      
      $result2 = pg_update($GLOBALS["conn"], "influencer", array("aantalpunten" => $subtract), array("id" => $GLOBALS["account_id"]));
      
      if (!$result) {
        return array("valid" => false, "code" => 403, "message" => "This resource already exist.", "error" => "ForbiddenContent");
      }     
      
    } elseif ($fields["type"] == "unclaim") {
      // Remove
      
      $query = "SELECT * from influencerreward where influencerid = $1";      
      $influencerids = fetch_query_params($query, array($GLOBALS["account_id"]));

      //$origin = array();
      //foreach ($influencerids as $influencerid){ 
      //  array_push($origin, $influencerid["influencerid"]);
      //}

      if (!in_array(array("influencerid" => $GLOBALS["account_id"], "rewardid" => $fields["rewardid"]), $influencerids)){
          return array("valid" => false, "code" => 403, "message" => "This resource doesn't exist.", "error" => "ForbiddenContent");
      }
      
      $values = array(
        "rewardid" => $fields["rewardid"],
        "influencerid" => $GLOBALS["account_id"],
      );
      

      // unset all null values
      foreach($values as $key=>$value){
        if(is_null($value) || $value == '')
            unset($values[$key]);
      }

      $result = pg_delete($GLOBALS["conn"], 'influencerreward', $values);

    
    } else {
      return array("valid" => false, "code" => 422, "message" => "The type is not of claim or unclaim!", "error" => "UnprocessableEntity");
    }
    
    
   
    
    
    if (!$result) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => 200, "message" => "Successfully updated task");  
    
  };
?>
