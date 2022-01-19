<?php
include_once("../common/header.php");
include_once("../functions/get_details_influencer.php");


// Check if path start with /api
if (explode("/",$_SERVER["REDIRECT_URL"])[1] != "api") {
  echo "Wrong path<br>";
  var_dump($_SERVER);
  var_dump(explode("/",$_SERVER["REDIRECT_URL"]));
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  // Debug
  echo (json_encode($_GET, JSON_PRETTY_PRINT)); 
  
  // api/influencer/{id}
  if (isset($_GET["influencers"]) && $_GET["influencers"]!="") {
    $id = $_GET["influencers"];
    
    include("../functions/get_details_influencer.php");
    
    var_dump(get_details_influencer($id));
  }
  
  // api/influencer/{id}/posts
  
  // api/influencer/{id}/posts/{id}
}  

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Debug
  echo (json_encode($_POST, JSON_PRETTY_PRINT));
  
  
  // If token is set in header, check ExpireToken
  if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
    echo "token";
    
    // Verify valid token
    
  } else {
    echo "no token";
    
    // Check if account credentials are valid
    include("../functions/auth_isvalid_account.php");
    $isValid = auth_isvalid_account($_POST["username", $_POST["password"], $_POST["type"]);
    echo (json_encode($isValid, JSON_PRETTY_PRINT));
    // Request new token      

  }
  
  // api/login Body:[type(influencer,stad), name, password] Headers:[]
  
  
  
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {}
