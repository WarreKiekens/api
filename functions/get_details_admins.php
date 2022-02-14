<?php
  include_once("../config.php");

  function get_details_admins(){
    
    // Authorization
    if ($GLOBALS["account_issuper"] != 1) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");      
    }
    
    $res = pg_query("SELECT id, gebruikersnaam, voornaam, familienaam, emailadres, isactief, issuper, picture FROM admin ORDER BY id");
    $data = fetch_query_data($res);
    
    return array("valid" => true, "data" => $data);
  };
?>
