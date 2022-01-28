<?php
  include_once("../config.php");

  function get_details_cities(){
    
    // Authorization
    // Everyone can access this resource
    
    if (isset($_GET["where"]) and isset($_GET["like"])) {
        $query = pg_query("SELECT id,naam,postcode,isactief FROM stad WHERE $1 like '%$2%' ORDER BY id");
        $data = fetch_query_params($query, array($_GET["where"], $_GET["like"]));
    } else {        
      $res = pg_query("SELECT id,naam,postcode,isactief FROM stad ORDER BY id");
      $data = fetch_query_data($res);
    }
    
    if ($data == null) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    } 
    return array("valid" => true, "data" => $data);
  };
?>
