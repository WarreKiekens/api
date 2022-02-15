<?php
  include_once("../config.php");

  function get_details_categories(){
    
    // Authorization
    // Everyone can access this resource

    $res = pg_query("SELECT id,naam, (select count(*) from opdrachtcategorie where categorieid = categorie.id) as totaltaskcount (select count(*) from influencercategorie where influencerid = id) as influencercount, (select count(*) from opdrachtcategorie where opdrachtid in (select id from opdracht where isuitgevoerd = false) and categorieid = categorie.id) as opentaskcount,  (select count(*) from opdrachtcategorie where opdrachtid in (select id from opdracht where isuitgevoerd = true) and categorieid = categorie.id) as closedtaskcount FROM categorie ORDER BY id");
    $data = fetch_query_data($res);

    if ($data == null) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }    
    
    return array("valid" => true, "data" => $data);
  };
?>
