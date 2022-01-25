<?php
  $host = "10.0.0.4";
  //$host = "192.168.56.101";
  //$host = "192.168.56.102";

  $dbname = "application";

  $user = "application";

  $password = "application";
  

  $conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

  $GLOBALS["conn"] = $conn;
  $GLOBALS["expireAfterHours"] = 4;

?>
