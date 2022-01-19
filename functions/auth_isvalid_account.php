<?php
  include_once("../config.php");

  function auth_isvalid_account($username, $password, $type){
    include("../common/get_query_data.php");
    
    $query = "SELECT count(*) as count FROM $type WHERE gebruikersnaam = '$username' and wachtwoord = '$password';";
    $data = get_query_data($query);
    
    if ($data["count"] === "1") {
      $query = "SELECT id FROM $type WHERE gebruikersnaam = '$username' and wachtwoord = '$password';";
      $data = get_query_data($query);
      
      return array("valid" => true, "id" => $data["id"]);
    } 
    return array("valid" => false, "id" => null);
  };
?>
