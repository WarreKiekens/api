<?php
  include_once("../config.php");

  function auth_login($username, $password){
    include("../common/get_query_data.php");
    
    $query = "SELECT $username, $password from influencers;";
    $data = get_query_data($query);
    
    $result = array();
    
    if (count($data) == 1) {
    }
    
    return $data;
  };
?>
