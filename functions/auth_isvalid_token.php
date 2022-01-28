<?php
  include_once("../config.php");

  function auth_isvalid_token($token){    
    
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
          $data = fetch_query_params($query, array($token))[0];
          
          if ($data["count"] === "1") {
            $query = "SELECT (select TO_CHAR(NOW(), 'DD-MM-YYYY HH:MI:SS')) as expireTime, expiretoken as creationTime FROM $party WHERE token = $1;";            
            $time = fetch_query_params($query, array($token));

            // Calculate hours between expireTime and creationTime
            $hours = (strtotime($time["expiretime"]) - strtotime($time["creationtime"]))/3600;

            if ($hours < $GLOBALS["expireAfterHours"]) {
              // Token not expired
              
              // Get id that matches with token
              $query = "SELECT id,isactief FROM $party WHERE token = $1;";            
              $data = fetch_query_params($query, array($token))[0];
              
              if ($data["isactief"] == "f") {
                if ($party == "influencer") {
                  return array("valid" => false, "code" => 403, "message" => "Account is disabled! If this is an error please contact the administrator to resolve this issue.", "error" => "AuthAccountDisabled");
                }
                return array("valid" => false, "code" => 403, "message" => "Account is not verified yet! The administrator is still processing your request.", "error" => "AuthAccountNotVerified");

              }
              
              $super = false;
              if ($party == "admin") {
                $query = "SELECT issuper FROM admin WHERE token = $1;";            
                $res = fetch_query_params($query, array($token))[0];
                
                if ($res["issuper"] == "t") {
                  $super = true;
                } 
              }
              
              return array("valid" => true, "id" => $data["id"], "type" => $party, "super" => $super);
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
