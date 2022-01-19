<?php
  include_once("../config.php");

  function auth_isvalid_account($username, $password, $type){
    include("../common/get_query_data.php");
    
    $query = "SELECT $username, $password from $type;";
    $data = get_query_data($query);
    
    $result = array();
    
    if (count($data) == 1) {
      return true;
    } 
    return false;
  };
?>
