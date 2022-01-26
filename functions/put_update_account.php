<?php
  include_once("../config.php");

  function put_update_account($fields){
       
    if (!is_numeric($fields["id"])){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
        
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad", "influencer")) or ($GLOBALS["account_id"] != $fields["id"])) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to update this resource", "error" => "ForbiddenContent");
    }
    
    if ($GLOBALS["account_type"] == "influencer") {

      // TODO: validate input
      $values = array(
        "gebruikersnaam" => $fields["username"],
        "wachtwoord" => $fields["password"],
        "profielfoto" => $fields["profilepicture"],
        "voornaam" => $fields["firstname"],
        "familienaam" => $fields["lastname"],
        "adres" => $fields["adress"],
        "postcode" => $fields["postcode"],
        "stad" => $fields["stad"],
        "geboortedatum" => $fields["dateofbirth"],
        "telefoonnummer" => $fields["phonenumber"],
        "emailadres" => $fields["email"],
        "geslacht" => $fields["gender"],
        "gebruikersnaaminstagram" => $fields["usernameinstagram"],
        "gebruikersnaamfacebook" => $fields["usernamefacebook"],
        "gebruikersnaamtiktok" => $fields["usernametiktok"],
        "infoovervolgers" => $fields["infoaboutfollowers"],
        "pincode" => $fields["pincode"],
        "vingerafdruk" => $fields["fingerprint"],
        "scangezicht" => $fields["scanface"],
        "aantalvolgerinstagram" => $fields["totalfollowersinstagram"],
        "aantalvolgerfacebook" => $fields["totalfollowersfacebook"],
        "aantalvolgertiktok" => $fields["totalfollowerstiktok"],
        "badge" => $fields["badge"],
        "isgevalideerd" => $fields["isvalidated"],
        "isaangevuld" => $fields["iscompleted"],
        "isactief" => $fields["isactive"],
        "aantalpunten" => $fields["totalpoints"],       
      );
      
      // unset all null values
      
      foreach($values as $key=>$value){
        if(is_null($value) || $value == '')
            unset($values[$key]);
      }
      
    
    } elseif ($GLOBALS["account_type"] == "stad") {
      
      // TODO: validate input
      $values = array(
        "gebruikersnaam" => $fields["username"],
        "wachtwoord" => $fields["password"],
        "naam" => $fields["name"],
        "postcode" => $fields["postcode"],
        "emailadres" => $fields["email"],
      ); 
    } 

    
    
    $result = pg_update($GLOBALS["conn"], $GLOBALS["account_type"], $values, array("id" => $GLOBALS["account_id"]));
    
    
    if (!$result) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }     
    
    return array("valid" => true, "code" => "200", "message" => "Successfully updated account");  
    
  };
?>
