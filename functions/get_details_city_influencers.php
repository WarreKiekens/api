<?php
  include_once("../config.php");

  function get_details_city_influencers($cityId){
    
    if (!is_numeric($cityId)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("admin"))) {
      if (!in_array($GLOBALS["account_type"], array("stad")) || $GLOBALS["account_id"] != $cityId) {
        return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
      }
    }
    
    
    if (isset($_GET["where"]) and isset($_GET["like"])) {
      
      $data = filtering_city_influencers($cityId);
      
      if ($data == null) {
        return array("valid" => true, "code" => 200, "message" => "Influencers successfully requested!");
      }
      
    } else {
      
      $query = "select id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories from influencer where id in (select influencerid from influencerstad where stadid = $1);";
      $data = fetch_query_params($query, array($cityId));

      // Convert categories into proper array
      $index = 0;
      foreach ($data as $influencer){ 
        $data[$index]["categories"] = explode(";", $influencer["categories"]);
        $index++; 
      }
    
    }
                
    
    
    if ($data == null) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    } 
    return array("valid" => true, "data" => $data);
  };
?>
