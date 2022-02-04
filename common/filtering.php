<?php
function clean($arr){
  $res = array();
  foreach ($arr as $val){
    if (strlen($val) >= 2) {
      array_push($res, $val);
    }
  }
  return $res;
}

function filtering_cities() {
  
  // check if value is bool
  if (in_array($_GET["like"], array("t","f"))) {

    $query = "SELECT id,naam,postcode,isactief,isnew,emailadres,(select count(influencerid) from InfluencerStad where stadid = stad.id) as influencercount FROM stad WHERE {$_GET['where']} = $1 ORDER BY id";

    if ($_GET["like"] == "t") {
      $data = fetch_query_params($query, array('true'));
    } else {
      $data = fetch_query_params($query, array('false'));
    }    

  } else {

    //TODO: check if where value in cols 
    $query = "SELECT id,naam,postcode,isactief,isnew,emailadres,(select count(influencerid) from InfluencerStad where stadid = stad.id) as influencercount FROM stad WHERE position($1 in {$_GET['where']}) > 0 ORDER BY id";
    $data = fetch_query_params($query, array($_GET["like"]));

  }
  
  return $data;

}


function filtering_influencers() {
  
  // check if value is bool
  if (in_array($_GET["like"], array("t","f"))) {

    $query = "SELECT id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories FROM Influencer WHERE {$_GET['where']} = $1 and id in (select influencerid from influencerstad where stadid = $1) ORDER BY ID;";

    if ($_GET["like"] == "t") {
      $data = fetch_query_params($query, array('true', $GLOBALS["account_id"]));
    } else {
      $data = fetch_query_params($query, array('false', $GLOBALS["account_id"]));
    }    

  } if (in_array($_GET["like"], array("Vrouw","Man"))) {
    $query = "SELECT id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories FROM Influencer WHERE {$_GET['where']} = $1 and id in (select influencerid from influencerstad where stadid = $2) ORDER BY ID;";
    $data = fetch_query_params($query, array($_GET["like"], $GLOBALS["account_id"]));
    
  } elseif (in_array($_GET["where"], array("categories", "Categories"))) {
   
    $sql = "SELECT id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories FROM Influencer where id in (select influencerid from influencerstad where stadid = $1) ORDER BY ID;";
    $data = fetch_query_params($sql, array($GLOBALS["account_id"]));

    // Convert categories into proper array
    $data = array();
    
    $_GET["like"] = clean(explode("'",$_GET["like"]));
    
    $index1 = 0;
    $index2 = 0;
    foreach ($res as $influencer){
      
      $categoryArray = explode(";", $influencer["categories"]);
      
      if ( count(array_intersect($_GET["like"], $categoryArray)) == count($_GET["like"])) {
        $data[$index1] = $res[$index2];
        //$data[$index1]["categories"] = $categoryArray;
        $index1++; 
      }
      $index2++;
      
    }
  } else {

    //TODO: check if where value in cols 
    $query = "SELECT id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories FROM Influencer WHERE position($1 in {$_GET['where']}) > 0 ORDER BY ID;";
    $data = fetch_query_params($query, array($_GET["like"]));

  }
  
  return $data;

}




function filtering_city_influencers($cityId) {
  
  // Check if value is array
  if (in_array($_GET["where"], array("categories", "Categories"))) {
   
    $sql = "select id,voornaam, familienaam, geslacht, gebruikersnaam, profielfoto, adres, postcode, stad, geboortedatum, telefoonnummer, emailadres, gebruikersnaamInstagram, gebruikersnaamFacebook, gebruikersnaamTiktok, infoovervolgers, AantalVolgersInstagram, AantalVolgersFacebook, AantalVolgersTiktok, badge, aantalpunten, (select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories, (select EXTRACT(YEAR FROM age(now(), geboortedatum))) as leeftijd from influencer where id in (select influencerid from influencerstad where stadid = '{$cityId}') ORDER BY ID;";
    $query = pg_query($sql);
    $res = fetch_query_data($query);

    // Convert categories into proper array
    $data = array();
    
    $_GET["like"] = clean(explode("'",$_GET["like"]));
    
    $index1 = 0;
    $index2 = 0;
    foreach ($res as $influencer){
      
      $categoryArray = explode(";", $influencer["categories"]);
      
      if ( count(array_intersect($_GET["like"], $categoryArray)) == count($_GET["like"])) {
        $data[$index1] = $res[$index2];
        $data[$index1]["categories"] = $categoryArray;
        $index1++; 
      }
      $index2++;
      
    }
  } else {

    //TODO: check if where value in cols 
    $sql = "select id,voornaam,familienaam,geslacht,gebruikersnaam,profielfoto,adres,postcode,stad,geboortedatum,telefoonnummer,emailadres,gebruikersnaamInstagram,gebruikersnaamFacebook,gebruikersnaamTiktok,infoovervolgers,AantalVolgersInstagram,AantalVolgersFacebook,AantalVolgersTiktok,badge,aantalpunten,(select STRING_AGG (naam, ';') AS column FROM categorie where categorie.id in (select categorieid from influencercategorie where influencerid = influencer.id)) as categories, (select EXTRACT(YEAR FROM age(now(), geboortedatum))) as leeftijd from influencer where id in (select influencerid from influencerstad where stadid = '{$cityId}') and {$_GET['where']} ilike '%{$_GET['like']}%' ORDER BY ID;";
    $query = pg_query($sql);
    $data = fetch_query_data($query);


    // Convert categories into proper array
    $index = 0;
    foreach ($data as $influencer){ 
      $data[$index]["categories"] = explode(";", $influencer["categories"]);
      $index++; 
    }
  }

  
  return $data;
 
}
?>
