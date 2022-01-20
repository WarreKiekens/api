<?php
  include_once("../config.php");

  function auth_isvalid_account($username, $password, $type){
    
    // Prevent Sql Injections
    $username = pg_escape_string($username);
    $password = pg_escape_string($password);
    $type = pg_escape_string($type);
    
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
