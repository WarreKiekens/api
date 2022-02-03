<?php
  include_once("../config.php");

  function get_details_posts_task($id){
    
    if (!is_numeric($id)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    // TODO: request all posts of a task, done by a city (are only allowed to query their own tasks)
    
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }
    
    
    // check if given id is actually an task of city id account
    $query = "SELECT id FROM opdracht where stadid = $1";
    $data = fetch_query_params($query, array($GLOBALS["account_id"]));
    
    if (!in_array($id, $data)) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
      
    }
    
    
    $query = "SELECT * FROM post where stadid = $1 and opdrachtid = $2 ORDER BY ID;";
    $data = fetch_query_params($query, array($GLOBALS["account_id"], $id));

    if ($data == null) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    } 
    
    // Convert categories into proper array
//    $index = 0;
//    foreach ($data as $influencer){ 
//      $data[$index]["categories"] = explode(";", $influencer["categories"]);
//      $index++; 
//    }
    
    
    return array("valid" => true, "data" => $data);
  };
?>
