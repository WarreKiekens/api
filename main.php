<?php
require_once('config.php'); // this sets up $db

$rows = pg_query($conn, "SELECT * FROM influencer");

echo json_encode($rows);

?>
