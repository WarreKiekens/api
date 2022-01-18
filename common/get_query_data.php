<?php
function get_query_data($query){

  $rows = pg_query($conn, "SELECT * FROM influencer");
  
  return json_encode($rows);
   
}

?>
