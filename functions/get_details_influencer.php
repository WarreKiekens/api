<?php
  include_once("../config.php");
  include_once("../common/get_query_data.php");

  function get_details_influencer($id){
    $query = "SELECT voornaam,familienaam,geslacht FROM Influencer where id = $id";
    $data = get_query_data($query);
    return var_dump($data);
  };
?>
