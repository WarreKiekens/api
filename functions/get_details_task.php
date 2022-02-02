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
    
    var_dump($data);
    
    //$query = "SELECT id,gebruikersnaam,naam,postcode,isactief,isnew,emailadres,(select count(influencerid) from InfluencerStad where stadid = stad.id) as influencercount  FROM stad where id = $1";
    //$data = fetch_query_params($query, array($id));
    
    if ($data == null) {
      return array("valid" => false, "code" => 200, "message" => "Index out of reach!", "error" => "IndexOverflow");
    } 
    return array("valid" => true, "data" => $data);
  };
?>
