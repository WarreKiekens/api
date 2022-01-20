<?php
  include_once("../config.php");

  function get_details_influencer($id){
    
    $query = "SELECT voornaam,familienaam,geslacht FROM Influencer where id = $id";
    $data = get_query_data($query);
    return $data;
  };
?>
