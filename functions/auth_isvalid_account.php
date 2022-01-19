<?php
  include_once("../config.php");

  function auth_isvalid_account($username, $password, $type){
    include("../common/get_query_data.php");
    
    $query = "SELECT count(*) as count FROM $type WHERE gebruikersnaam = '$username' and wachtwoord = '$password';";
    $data = get_query_data($query);
    
    $result = array();
    
    if ($data["count"] === "1") {
      return true;
    } 
    return false;
  };
?>
