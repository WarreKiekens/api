<?php
  include_once("../config.php");

  function get_details_influencers(){
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("stad"))) {
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }
    
    if (isset($_GET["where"]) and isset($_GET["like"])) {
      
      $data = filtering_influencers();
      
      if ($data == null) {
        return array("valid" => true, "code" => 200, "message" => "Influencers successfully requested!");
      }
    } else {
      $res = pg_query("SELECT id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories FROM Influencer ORDER BY ID;");
      $data = fetch_query_data($res);
    }
    
    if ($data == null) {
      return array("valid" => false, "code" => "500", "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
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
