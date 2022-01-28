<?php

function filtering_cities() {
  
  // check if value is bool
  if (in_array($_GET["like"], array("t","f"))) {

    $query = "SELECT id,naam,postcode,isactief FROM stad WHERE {$_GET['where']} = $1 ORDER BY id";

    if ($_GET["like"] == "t") {
      $data = fetch_query_params($query, array('true'));
    } else {
      $data = fetch_query_params($query, array('false'));
    }    

  } else {

    //TODO: check if where value in cols 
    $query = "SELECT id,naam,postcode,isactief FROM stad WHERE position($1 in {$_GET['where']}) > 0 ORDER BY id";
    $data = fetch_query_params($query, array($_GET["like"]));

  }
  
  return $data;

}


function filtering_influencers() {
  
  // check if value is bool
  if (in_array($_GET["like"], array("t","f"))) {

    $query = "SELECT id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories FROM Influencer WHERE {$_GET['where']} = $1 ORDER BY ID;";

    if ($_GET["like"] == "t") {
      $data = fetch_query_params($query, array('true'));
    } else {
      $data = fetch_query_params($query, array('false'));
    }    

  } else {

    //TODO: check if where value in cols 
    $query = "SELECT id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories FROM Influencer WHERE position($1 in {$_GET['where']}) > 0 ORDER BY ID;";
    $data = fetch_query_params($query, array($_GET["like"]));

  }
  
  return $data;

}




function filtering_city_influencers($cityId) {
  
  // check if value is bool
  if (in_array($_GET["like"], array("t","f"))) {
    
    $query = "select id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories from influencer where id in (select influencerid from influencerstad where stadid = $1) and {$_GET['where']} = $2 ORDER BY ID;";
    
    if ($_GET["like"] == "t") {
      $data = fetch_query_params($query, array($cityId, 'true'));
    } else {
      $data = fetch_query_params($query, array($cityId, 'false'));
    }    

  } else {

    //TODO: check if where value in cols 
    $sql = "select id,LOWER(voornaam),LOWER(familienaam),geslacht,LOWER(gebruikersnaam),LOWER(profielfoto),LOWER(adres),LOWER(postcode),LOWER(stad),geboortedatum,LOWER(telefoonnummer),LOWER(emailadres),LOWER(gebruikersnaamInstagram),LOWER(gebruikersnaamFacebook),LOWER(gebruikersnaamTiktok),LOWER(infoovervolgers),AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories from influencer where id in (select influencerid from influencerstad where stadid = '{$cityId}') and position(LOWER('{$_GET['like']}') in {$_GET['where']}) > 0 ORDER BY ID;";
    $query = pg_query($sql);
    $data = fetch_query_data($query);

  }
  
  // Convert categories into proper array
  $index = 0;
  foreach ($data as $influencer){ 
    $data[$index]["categories"] = explode(";", $influencer["categories"]);
    $index++; 
  }
  
  return $data;

}
?>
