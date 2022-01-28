<?php

function filter() {
  
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

  if ($data == null) {
    return array("valid" => true, "code" => 200, "message" => "Cities successfully requested!");
  }

}

?>
