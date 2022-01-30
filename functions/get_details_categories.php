<?php
  include_once("../config.php");

  function get_details_categories(){
    
    // Authorization
    // Everyone can access this resource

    $res = pg_query("SELECT id,naam FROM categorie ORDER BY id");
    $data = fetch_query_data($res);

    if ($data == null) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }    
    
    return array("valid" => true, "data" => $data);
  };
?>
