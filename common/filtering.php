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

?>
