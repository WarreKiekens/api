<?php
  include_once("../config.php");

  function post_create_task($fields){
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))){
      if ($GLOBALS["account_id"] != $id) {
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
      }
    }
    
    $res = pg_query("select TO_CHAR(NOW(), 'YYYY-MM-DD HH:MI:SS') as now");
    $now = fetch_query_data($res)[0];
    
    $values = array(
      "stadid" => $GLOBALS["account_id"],
      "titel" => $fields["title"],
      "omschrijving" => $fields["description"],
      "aantalpuntenwaard" => $fields["totalpointsworth"],
      "isuitgevoerd" => false,
      "datumopgegeven" => $now["now"],
      "foto" => $fields["picture"]
    ); 

    $result = pg_insert($GLOBALS["conn"], "opdracht", $values);


    if ($result) {
      return array("valid" => true);
    }

    return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");  
    
  }
?>
