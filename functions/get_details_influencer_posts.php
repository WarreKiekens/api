<?php
  include_once("../config.php");

  function get_details_influencer_posts(){
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      return array("valid" => false, "code" => 401, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }
            
    $res = pg_query("SELECT * FROM Post;");
    $data = fetch_query_data($res);
    
    if ($data == null) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    } 
    return array("valid" => true, "data" => $data);
  };
?>
