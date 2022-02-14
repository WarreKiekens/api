<?php
  include_once("../config.php");

  function get_details_admins(){
    
    // Authorization
echo json_encode($GLOBALS);    
    if ($GLOBALS["issuper"]) {
    }
    
    
    $res = pg_query("SELECT id, gebruikersnaam, voornaam, familienaam, emailadres, isactief, issuper, picture FROM admin ORDER BY id");
    $data = fetch_query_data($res);

    
    
    return array("valid" => true, "data" => $data);
  };
?>
