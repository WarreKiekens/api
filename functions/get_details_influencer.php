<?php
  include_once("../config.php");

  function get_details_influencer($id){
    
    if (!is_numeric($id)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!!", "error" => "UnprocessableEntity");
    }
        
    $query = "SELECT voornaam,familienaam,geslacht FROM Influencer where id = $1";
    $data = fetch_query_params($query, array($id));
    
    if ($data == null) {
      return array("valid" => false, "code" => 200, "message" => "Index out of reach!", "error" => "IndexOverflow");
    } 
    return array("valid" => true, "data" => $data);
  };
?>
