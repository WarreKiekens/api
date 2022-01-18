<?php
require('config.php'); // this sets up $db

try {
    $dbh = new PDO("pgsql:host=" . $db['host'] . ";dbname=" . $db['dbname'] . ";port=" . $db['port'], 
        $db['user'], $db['pass'], array(PDO::ATTR_PERSISTENT => true));	
} catch(PDOException $e) {
    echo "Error : " . $e->getMessage() . "<br/>";
    die();
}

$sql = "SELECT * FROM accounts";

$stmt = $dbh->query($sql);

if ($stmt===false){
    echo "<pre>Error executing the query: $sql</pre>";
    echo $stmt;
    die();
}


    $accounts = array();
    foreach ($stmt as $row){
        $account = array(
            "id" => $row['id'],
            "name" => $row['username'],
            "password" => $row['password'],
            "email" => $row['email']
        );
        array_push($accounts, $account);
    }
    echo json_encode($accounts);

?>
