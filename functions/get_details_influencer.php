<?php
  include_once("../config.php");

  function get_details_influencer($id){
        
    $query = "SELECT voornaam,familienaam,geslacht FROM Influencer where id = $id";
    $res = pg_query_params($GLOBALS["conn"], $query, array($id));
    $data = fetch_query_data($res);
    
    return $data;
    
  };
?>
