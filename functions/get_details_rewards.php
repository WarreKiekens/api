<?php
  include_once("../config.php");

  function get_details_rewards($id){
    
    if (!is_numeric($id)){
      return array("valid" => false, "code" => 422, "message" => "The type of given Entity isn't supported!", "error" => "UnprocessableEntity");
    }
    
     // Authorization
    if ( !in_array($GLOBALS["account_type"], array("influencer")) or ($GLOBALS["account_id"] != $id) ){
      return array("valid" => false, "code" => 403, "message" => "Unauthorized to access this resource", "error" => "ForbiddenContent");
    }

    // all accepted rewards
    $query = "select *,true as isClaimed from reward where id in (select rewardid from influencerreward where influencerid = $1) order by id;";
    $data1 = fetch_query_params($query, array($id));

    // all other rewards
    $query = "select *,false as isClaimed from reward where id not in (select id from reward where id in (select rewardid from influencerreward where influencerid = $1)) order by id;";
    $data2 = fetch_query_params($query, array($id));
    
    $allRewards = array_merge($data1,$data2);
    
    return array("valid" => true, "data" => $allRewards);
  };
?>
