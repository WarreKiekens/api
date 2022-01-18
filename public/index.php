<?php
include("../common/header.php");


// Check if path start with /api
if (explode("/",$_SERVER["PATH_INFO"])[1] != "api") {
  echo "Wrong path<br>";
  var_dump($_SERVER);
  var_dump(explode("/",$_SERVER["PATH_INFO"]));
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  // api/influencer/{id}
  if (isset($_GET["id"]) && $_GET["id"]!="") {
    $id = $_GET["id"];
  }
  
  // api/influencer/{id}/posts
  
  // api/influencer/{id}/post/{id}
}  

if ($_SERVER["REQUEST_METHOD"] === "POST") {}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {}
