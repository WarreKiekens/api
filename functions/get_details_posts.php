<?php
  include_once("../config.php");

  function get_details_posts(){
    
    // Authorization
    
   // TODO: influencer perspective 
       
    $res = pg_query("SELECT id,naam,postcode,isactief,isnew,emailadres, (select count(influencerid) from InfluencerStad where stadid = stad.id) as influencercount,picture FROM stad ORDER BY id");
    $data = fetch_query_data($res);

    if ($data == null) {
      return array("valid" => false, "code" => 500, "message" => "PSQL statement couldn't be executed!", "error" => "InternalError");
    }
    
    
    return array("valid" => true, "data" => $data);
  };
?>
