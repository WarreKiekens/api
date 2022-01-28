<?php
  include_once("../config.php");

  function get_details_cities(){
    
    // Authorization
    // Everyone can access this resource
    
    if (isset($_GET["where"]) and isset($_GET["like"])) {
        //TODO: check if where value in cols 
        $query = "SELECT id,naam,postcode,isactief FROM stad WHERE {$_GET['where']} like $1 ORDER BY id";
        $data = fetch_query_params($query, array($_GET["like"]));
      
        if ($data == null) {
          return array("valid" => true, "code" => 200, "message" => "Cities successfully requested!");
        }
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
