<?php
  include_once("../config.php");

  function get_details_task($id){
    
    if (!is_numeric($id)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))){
      return array("valid" => false, "code" => 401, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }
        
    // check if given id is actually an task of city id account
    $query = "SELECT count(*) FROM opdracht where stadid = $1";
    $data = fetch_query_params($query, array($GLOBALS["account_id"]));
    
    if ($data["count"] < 1) {
      $data = null;
    }
    
    $query = "SELECT *,(select count(*) from post where opdrachtid = opdracht.id) as postcount, (select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from opdrachtcategorie where opdrachtid = opdracht.id)) as categories FROM opdracht where stadid = $1 and id = $2 ORDER BY ID;";
    $data = fetch_query_params($query, array($GLOBALS["account_id"], $id));
    
    if ($data == null) {
      return array("valid" => false, "code" => 200, "message" => "Index out of reach!", "error" => "IndexOverflow");
    }
    
    // Convert categories into proper array
    $index = 0;
    foreach ($data as $influencer){ 
      $data[$index]["categories"] = explode(";", $influencer["categories"]);
      $index++;
    }
    
    
    return array("valid" => true, "data" => $data);
  };
?>
