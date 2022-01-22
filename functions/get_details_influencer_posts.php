<?php
  include_once("../config.php");

  function get_details_influencer_posts($influencerId){
    
    if (!is_numeric($influencerId)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!!", "error" => "UnprocessableEntity");
    }
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      if ($GLOBALS["account_id"] != $influencerId) {
        return array("valid" => false, "code" => 401, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
      }
    }
                
    $query = "SELECT * FROM post WHERE influencerid = $1";
    $data = fetch_query_params($query, array($influencerId));
    
    //if ($data == null) {
    //  return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    //} 
    return array("valid" => true, "data" => $data);
  };
?>
