<?php

include("../common/get_query_data.php");

$query = "SELECT * FROM ";
$data = get_query_data($query);

var_dump($data);
?>
