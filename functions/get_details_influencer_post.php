<?php
  include_once("../config.php");

  function get_details_influencer_post($influencerId, $postId){
    
    if (!is_numeric($influencerId) or !is_numeric($postId)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      if ($GLOBALS["account_id"] != $influencerId) {
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
      }
    }
                
    $query = "SELECT * FROM post WHERE influencerid = $1 and id = $2";
    $data = fetch_query_params($query, array($influencerId, $postId));
    
    if ($data == null) {
      return array("valid" => false, "code" => 200, "message" => "Index out of reach!", "error" => "IndexOverflow");
    } 
 
    return array("valid" => true, "data" => $data);
  };
?>
