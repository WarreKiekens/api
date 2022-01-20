<?php
  include_once("../config.php");

  function auth_isvalid_token($token){
        
    // Check influencer
    // Debug token
    $token = '4f004dacb742f74acfd2919c92ec06c9912f78f91347ccd3281ea792368d8bdf3b8c77e7ce8782406928f654514f9c0704abda50b6b4884750b3e14dfa367185';
    
    $query = "SELECT count(*) as count FROM influencer WHERE token = '$token';";
    $data = get_query_data($query);
    
    if ($data["count"] === "1") {
      return array("valid" => true);
    }
    
    // Check stad
    $query = "SELECT count(*) FROM stad WHERE gebruikersnaam = '$username' and wachtwoord = '$password';";
    $data = get_query_data($query);
    
    if ($data["count"] === "1") {
      return array("valid" => true);
    }
    
    return array("valid" => false);
  };
?>
