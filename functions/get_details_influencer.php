<?php
  include_once("../config.php");

  function get_details_influencer($id){
    include("../common/get_query_data.php");
    
    $query = "SELECT voornaam,familienaam,geslacht FROM Influencer where id = $id";
    $data = get_query_data($query);
    return var_dump($data);
  };
?>
