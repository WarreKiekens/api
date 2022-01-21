<?php
  include_once("../config.php");

  function get_details_influencer($id){
        
    $query = "SELECT voornaam,familienaam,geslacht FROM Influencer where id = $1";
    $data = fetch_query_params($query, array($id));
    
    if ($data == null) {
      return array("valid" => false, "code" => 200, "message" => "Index out of reach!", "error" => "IndexOverflow");
    } 
    return array("valid" => true, "data" => $data);
  };
?>
