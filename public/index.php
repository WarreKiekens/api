<?php
include_once("../common/header.php");
include_once("../functions/get_details_influencer.php");


// Check if path start with /api
if (explode("/",$_SERVER["PATH_INFO"])[1] != "api") {
  echo "Wrong path<br>";
  var_dump($_SERVER);
  var_dump(explode("/",$_SERVER["PATH_INFO"]));
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  // Debug
  echo (json_encode($_GET, JSON_PRETTY_PRINT)); 
  
  // api/influencer/{id}
  if (isset($_GET["influencer"]) && $_GET["influencer"]!="") {
    $id = $_GET["influencer"];
  }
  
  // api/influencer/{id}/posts
  
  // api/influencer/{id}/posts/{id}
}  

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Debug
  echo (json_encode($_POST, JSON_PRETTY_PRINT));
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {}
