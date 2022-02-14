<?php
  include_once("../config.php");

  function get_details_admins(){
    
    // Authorization
echo $GLOBALS["issuper"];    
    if ($GLOBALS["issuper"]) {
    }
    
    
    $res = pg_query("SELECT id,naam,postcode,isactief,isnew,emailadres, (select count(influencerid) from InfluencerStad where stadid = stad.id) as influencercount,picture FROM stad ORDER BY id");
    $data = fetch_query_data($res);

    
    
    return array("valid" => true, "data" => $data);
  };
?>
