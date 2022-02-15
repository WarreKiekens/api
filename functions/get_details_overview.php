<?php
  include_once("../config.php");

  function get_details_overview(){
    
    // Authorization
    if (!in_array($GLOBALS["account_type"], array("admin"))){
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }


    $res = pg_query("SELECT id,naam, (select count(*) from influencercategorie where influencerid = id) as influencercount, FROM categorie ORDER BY id");
    $overviewCategories = fetch_query_data($res);
    
    $res = pg_query("SELECT (select count(*) from opdracht) as totaltaskcount, (select count(*) from opdracht where isuitgevoerd = false) as opentaskcount, (select count(*) from opdracht where isuitgevoerd = false) as closedtaskcount");
    $overviewTasks = fetch_query_data($res);
    
    $res = pg_query("select id,gebruikersnaam,(select count(*) from opdracht where winnaarid = influencer.id) as taskwincount, (select count(*) from post where influencerid = influencer.id) as totalposts, (select count(*) from post where influencerid = influencer.id and isgoedgekeurd = true) as approvedposts, (select count(*) from post where influencerid = influencer.id and isgoedgekeurd = false) as unapprovedposts from influencer order by id;");
    $overviewInfluencer = fetch_query_data($res);
    
    $data = array(
      "categories" => $overviewCategories,
      "tasks" => $overviewTasks,
      "influencer" => $overviewInfluencer
    );

    if ($data == null) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }    
    
    return array("valid" => true, "data" => $data);
  };
?>
