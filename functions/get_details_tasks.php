<?php
  include_once("../config.php");

  function get_details_tasks(){
    
    //
    // TODO -> fix influencer perspective !!!!!!!!!!!!!
    //
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad","influencer"))) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }

   
    if ($GLOBALS["account_type"] == "stad") {
      $query = "SELECT *,(select count(*) from post where opdrachtid = opdracht.id) as postcount, (select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from opdrachtcategorie where opdrachtid = opdracht.id)) as categories FROM opdracht where stadid = $1 ORDER BY ID;";
      $data = fetch_query_params($query, array($GLOBALS["account_id"]));
    } else {
      $query = "select * from opdracht where stadid in (select stadid from influencerstad where influencerid = $1);";
      $data = fetch_query_params($query, array($GLOBALS["account_id"]));
    }
    
    if ($data == null) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
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
