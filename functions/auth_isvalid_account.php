<?php
  include_once("../config.php");

  function auth_isvalid_account($username, $password, $type){
    
    //if (!in_array($type, ["stad", "influencer","admin"])) {
    //  return array("valid" => false, "code" => 400, "message" => "Type is expected to be of stad or influencer in body!", "error" => "AuthTypeInvalid");
    //}
    
    $credentials = [$username, $password];
    foreach ($credentials as $credential) {
      if ($credential == "") {
        return array("valid" => false, "code" => 400, "message" => "Username and/or password hasn't been provided in body!", "error" => "AuthCredsInvalid");
      }
    }
    
    // Search for type
    $types = ["stad", "influencer", "admin"];
    foreach ($types as $type) {   
    
      $query = "SELECT count(*) as count FROM $type WHERE gebruikersnaam = $1 and wachtwoord = $2;";
      $data = fetch_query_params($query, array($username, $password))[0];

      if ($data["count"] === "1") {

        // Check if account has been deactivated
        $query = "SELECT id,isactief FROM $type WHERE gebruikersnaam = $1 and wachtwoord = $2;";
        $data = fetch_query_params($query, array($username, $password))[0];

        if ($data["isactief"] == "f") {
          if ($type == "influencer") {
            return array("valid" => false, "code" => 403, "message" => "Account is disabled! If this is an error please contact the administrator to resolve this issue.", "error" => "AuthAccountDisabled");
          }
          return array("valid" => false, "code" => 403, "message" => "Account is not verified yet! The administrator is still processing your request.", "error" => "AuthAccountNotVerified");

        }

        return array("valid" => true, "id" => $data["id"]);
      } 
    
    }
    
    return array("valid" => false, "code" => 401, "message" => "Account doesn't exist or credentials/username is wrong!", "error" => "AuthCredsWrong");
  };
?>
