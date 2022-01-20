<?php
  include_once("../config.php");

  function get_details_influencer($id){
        
    $query = "SELECT voornaam,familienaam,geslacht FROM Influencer where id = $1";
    $data = fetch_query_params($query, array($id));
    
    return $data;
  };
?>
