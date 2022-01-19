<?php
  function get_query_data($query){

    $rows = pg_query($GLOBALS["conn"], $query);
    $result = pg_fetch_all($rows);

    if (count($result) == 1) {
      $result = $result[0];
    }
    return $result;
  }
?>
