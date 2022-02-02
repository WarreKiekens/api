<?php
  include_once("../config.php");

  function get_details_tasks(){
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad","influencer"))) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }

   
    if ($GLOBALS["account_type"] == "stad") {
      $query = "SELECT * FROM opdracht where stadid = $1 ORDER BY ID;";
      $data = fetch_query_params($query, array($GLOBALS["account_id"]));
    } else {
      $query = "SELECT * FROM opdracht where categorie in (...) ORDER BY ID;";
      $data = fetch_query_params($query, array($GLOBALS["account_id"]));
    }
    
    if ($data == null) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    } 
        
    
    return array("valid" => true, "data" => $data);
  };
?>
