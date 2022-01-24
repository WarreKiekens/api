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
        
        $parties = ["influencer", "stad", "admin"];
        foreach ($parties as $party){
          
          $query = "SELECT count(*) as count FROM $party WHERE token = $1";         
          $data = fetch_query_params($query, array($token));
          
          if ($data["count"] === "1") {
            $query = "SELECT (select TO_CHAR(NOW(), 'DD-MM-YYYY HH:MI:SS')) as expireTime, expiretoken as creationTime FROM $party WHERE token = $1;";            
            $time = fetch_query_params($query, array($token));

            // Calculate hours between expireTime and creationTime
            $hours = (strtotime($time["expiretime"]) - strtotime($time["creationtime"]))/3600;

            if ($hours < $GLOBALS["expireAfterHours"]) {
              // Token not expired
              
              // Get id that matches with token
              $query = "SELECT id FROM $party WHERE token = $1;";            
              $data = fetch_query_params($query, array($token));
              
              return array("valid" => true, "id" => $data["id"], "type" => $party, "creationtime" => $time["creationtime"], "expiretime" => $time["expiretime"]);
            } else {
              // Token expired
              return array("valid" => false, "code" => 401, "message" => "Token has expired!", "error" => "AuthTokenExpire");
            }
          }
           
        }
        
        return array("valid" => false, "code" => 401, "message" => "Unknown token provided!", "error" => "AuthTokenWrong");
        
      }
   
    }
    return array("valid" => false, "code" => 401, "message" => "Unvalid token type!", "error" => "AuthTokenUnvalid");
  };
?>
