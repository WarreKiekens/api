<?php
require_once('config.php');

$rows = pg_query($conn, "SELECT * FROM influencer");

echo json_encode($rows);

?>
