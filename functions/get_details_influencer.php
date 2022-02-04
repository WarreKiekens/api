<?php
  include_once("../config.php");

  function get_details_influencer($id){
        
    if (!is_numeric($id)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))){
      if ($GLOBALS["account_id"] != $id) {
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
      }
    }

    
    $query = "influencerid from influencerStad where stadid = $1";
    $influencers = fetch_query_params($query, array($id));
    
    if (!in_array($id, $influencers)) {
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }

    $query = "SELECT id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = $1)) as categories FROM Influencer WHERE id = $1 ORDER BY ID;";
    
    $data = fetch_query_params($query, array($id));
    
    if ($data == null) {
      return array("valid" => false, "code" => 200, "message" => "Index out of reach!", "error" => "IndexOverflow");
    }
    
    // Convert categories into proper array
    $index = 0;
    foreach ($data as $influencer){ 
      $data[$index]["categories"] = explode(";", $influencer["categories"]);
      $index++; 
    }
    
    return array("valid" => true, "data" => $data);
  };
?>
