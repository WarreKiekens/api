<?php
  include_once("../config.php");

  function post_create_task($fields){
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))){
      if ($GLOBALS["account_id"] != $id) {
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
      }
    }
    
    // TODO: validate input
    $values = array(
      "titel" => $fields["title"],
      "omschrijving" => $fields["description"],
      "aantalpuntenwaard" => $fields["totalpointsworth"],
      "isuitgevoerd" => $fields["isexecuted"],
      "datumopgegeven" => $fields["creationdate"],
      "datumuitgevoerd" => $fields["executiondate"],
      "foto" => $fields["picture"]
    ); 


    $result = pg_insert($GLOBALS["conn"], "stad", $values);


    if ($result) {
      return array("valid" => true);
    }

    return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");  
    
  }
?>
