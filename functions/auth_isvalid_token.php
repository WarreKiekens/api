<?php
  include_once("../config.php");

  function auth_isvalid_token($token){    
    
    // Debug token
    //$token = '4f004dacb742f74acfd2919c92ec06c9912f78f91347ccd3281ea792368d8bdf3b8c77e7ce8782406928f654514f9c0704abda50b6b4884750b3e14dfa367185';
    
    // Validate Bearer
    if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
      $tokenString = $matches[1];
      
      if (ctype_alnum($tokenString)) {
        // Valid Bearer
        $token = $tokenString;
        
        // Validate existing token
    
        // Check influencer
        $query = "SELECT count(*) as count FROM influencer WHERE token = '$token';";
        $data = get_query_data($query);

        if ($data["count"] === "1") {
          $query = "SELECT (select TO_CHAR(NOW(), 'DD-MM-YYYY HH:MI:SS')) as expireTime, expiretoken as creationTime FROM influencer WHERE token = '$token';";
          $time = get_query_data($query);
          
          // if token expired return non valid and maybe change all arrays to array("valid" => ..., "reason")
          
          // Calculate time between expireTime and creationTime
          $days = (strtotime($time["expireTime"]) - strtotime($time["creationTime"]))/86400;
          
          sendResponse(000, "Debug", array("debug"=>$time, "daysDiff" => $days));
          return array("valid" => true);
        }

        ////////////////
        // Check stad
        $query = "SELECT count(*) FROM stad WHERE gebruikersnaam = '$username' and wachtwoord = '$password';";
        $data = get_query_data($query);

        if ($data["count"] === "1") {
          return array("valid" => true);
        }
        ////////////////
        
      }
   
    }
    return array("valid" => false);
  };
?>
