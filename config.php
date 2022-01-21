<?php
  $host = "192.168.56.115";
  //$host = "192.168.56.101";

  $dbname = "application";

  $user = "application";

  $password = "warre";
  //$password = "application";
  

  $conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

  $GLOBALS["conn"] = $conn;
  $GLOBALS["expireAfterHours"] = 4;

?>
