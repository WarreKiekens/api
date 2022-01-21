<?php
include_once("../common/header.php");
include_once("../common/response.php");
include_once("../common/get_query_data.php");

include_once("../functions/get_details_influencer.php"); // In future, probably needs to be moved to bottom due to performancy issues
include_once("../functions/auth_isvalid_account.php");
include_once("../functions/auth_isvalid_token.php");
include_once("../functions/auth_update_token.php");

// Check if path start with /api
if (explode("/",$_SERVER["REDIRECT_URL"])[1] != "api") {
  echo "Wrong path<br>";
  var_dump($_SERVER);
  var_dump(explode("/",$_SERVER["REDIRECT_URL"]));
  exit();
}

// Authentication
// If token is set in header, check ExpireToken
if (isset($_SERVER["HTTP_AUTHORIZATION"]) && $_SERVER["HTTP_AUTHORIZATION"] != "") {
  
  // Verify valid token
  $token = $_SERVER["HTTP_AUTHORIZATION"];
  $auth = auth_isvalid_token($token);
  
  if ($auth["valid"] == false) {
    sendResponse($auth["code"], $auth["message"], $auth["data"], $auth["error"]);
  }
  
  // Set global id and type
  $GLOBALS["id"] = $auth["id"];
  $GLOBALS["type"] = $auth["type"];
    
} else {

  // Check if account credentials are valid
  $auth = auth_isvalid_account($_POST["username"], $_POST["password"], $_POST["type"]);
  if ($auth["valid"]) {

    // Update token
    $token = auth_update_token($auth["id"], $_POST["type"]);
    
    if ($token["valid"]) {
      sendResponse(200, "Token successfully requested!", $token["data"]);
    } else {
      sendResponse($token["code"], $token["message"], $token["data"], $token["error"]);
    }
    
    
  } else {
    sendResponse($auth["code"], $auth["message"], $auth["data"], $auth["error"]);
  }

}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
  // Debug
  //echo (json_encode($_GET, JSON_PRETTY_PRINT)); 
  
  // api/influencers/{id}
  if (isset($_GET["influencers"]) && $_GET["influencers"]!="") {
    $id = $_GET["influencers"];
    
    $details = get_details_influencer($id);
    
    if ($details["valid"]) {
      sendResponse(200, "Influencer successfully requested!", $details["data"]);
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    }
    
    
  }
  
  // api/influencers/{id}/posts
  
  // api/influencers/{id}/posts/{id}
}  

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Debug
  //echo (json_encode($_POST, JSON_PRETTY_PRINT));
 
  
  // api/login Body:[type(influencer,stad), name, password] Headers:[]
  
  
  
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {}
