<?php

  function fetch_query_data($res){
    
    $result = pg_fetch_all($res);

    //if (count($result) == 1) {
    //  $result = $result[0];
    //}
    
    return $result;
  }

  function fetch_query_params($query, $array){
    $res = pg_query_params($GLOBALS["conn"], $query, $array);
    return fetch_query_data($res);
  }

?>
