<?php
  include_once("../config.php");

  function auth_isvalid_account($username, $password, $type){
    
    if (!in_array($type, ["stad", "influencer","admin"])) {
      return array("valid" => false, "code" => 400, "message" => "Type is expected to be of stad or influencer in body!", "error" => "AuthTypeInvalid");
    }
    
    $credentials = [$username, $password];
    foreach ($credentials as $credential) {
      if ($credential == "") {
        return array("valid" => false, "code" => 400, "message" => "Username and/or password hasn't been provided in body!", "error" => "AuthCredsInvalid");
      }
    }
    
    $query = "SELECT count(*) as count FROM $type WHERE gebruikersnaam = $1 and wachtwoord = $2;";
    $data = fetch_query_params($query, array($username, $password))[0];
    
    if ($data["count"] === "1") {
      $query = "SELECT id FROM $type WHERE gebruikersnaam = $1 and wachtwoord = $2;";
      $data = fetch_query_params($query, array($username, $password))[0];
      
      return array("valid" => true, "id" => $data["id"]);
    } 
    return array("valid" => false, "code" => 401, "message" => "Account doesn't exist or credentials/username is wrong!", "error" => "AuthCredsWrong");
  };
?>
