<?php
  include_once("../config.php");

  function auth_create_task($fields){
    
    $type = $fields["type"];
    
    if (!in_array($type, ["stad"])) {
      return array("valid" => false, "code" => 400, "message" => "Type is expected to be of stad or influencer in body!", "error" => "AuthTypeInvalid");
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
