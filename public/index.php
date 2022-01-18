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
  // api/influencer/{id}
  if (isset($_GET["users"]) && $_GET["users"]!="") {
    $id = $_GET["users"];
  }
  
  // api/influencer/{id}/posts
  
  // api/influencer/{id}/posts/{id}
}  

if ($_SERVER["REQUEST_METHOD"] === "POST") {}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {}
