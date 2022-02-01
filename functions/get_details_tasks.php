<?php
  include_once("../config.php");

  function get_details_tasks(){
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }
    
    if (isset($_GET["where"]) and isset($_GET["like"])) {
      
      //$data = filtering_tasks();
      
      if ($data == null) {
        return array("valid" => true, "code" => 200, "message" => "Influencers successfully requested!");
      }
    } else {
      $res = pg_query("SELECT * FROM opdracht ORDER BY ID;");
      $data = fetch_query_data($res);
    }
    
    if ($data == null) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    } 
        
    
    return array("valid" => true, "data" => $data);
  };
?>
