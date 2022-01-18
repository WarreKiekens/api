<?php
include_once("../config.php");
include("../common/get_query_data.php");

// TODO: refer from public/index.php to this file + include id + change users -> influencers
$query = "SELECT voornaam,familienaam,geslacht FROM Influencer where id = 1";
$data = get_query_data($query);

var_dump($data);
?>
