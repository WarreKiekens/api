<?php
  include_once("../config.php");

  function get_details_cities(){
    
    // Authorization
    // Everyone can access this resource
    
    if (isset($_GET["where"]) and isset($_GET["like"])) {
      
        return(filtering());
      
    } else {        
      $res = pg_query("SELECT id,naam,postcode,isactief FROM stad ORDER BY id");
      $data = fetch_query_data($res);
      
      if ($data == null) {
        return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
      }
    }
    
    
    return array("valid" => true, "data" => $data);
  };
?>
