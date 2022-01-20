<?php
  $host = "192.168.56.115";
  $dbname = "application";
  $user = "application";
  $password = "warre";

  $conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");

  $GLOBALS["conn"] = $conn;
  $GLOBALS["expireAfterHours"] = 4;

?>
