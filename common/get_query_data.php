<?php
  function get_query_data($query){

    $rows = pg_query($GLOBALS["conn"], $query);
    $result = pg_fetch_all($rows);

    return json_encode($result, JSON_PRETTY_PRINT);
  }
?>
