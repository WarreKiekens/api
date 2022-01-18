<?php
// Rename this file config.php and insert the proper value for connection string
// from the Overview page of your Compose dashboard - or otherwise set the
// values in $db (host, user, pass, port, dbname)


$connection_string = 'postgres://application:warre@192.168.56.115:5432/testdb';
// $dbname = 'compose'; // optional, supply this if the name isn't in the URL

// --- You probably don't need to edit below here ---

$db = parse_url($connection_string);

$db['dbname'] = substr($db['path'],1);
unset($db['path']); // not needed
if(isset($dbname) && $dbname) {
    $db['dbname'] = $dbname;
}

//var_dump($db);
?>
