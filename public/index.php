<?php
include_once("../common/header.php");
include_once("../common/response.php");
include_once("../common/get_query_data.php");

include_once("../functions/get_details_influencer.php");
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
  
  if ($auth["valid"] != true) {
    sendResponse(401, "Unauthorized: Bearer token is wrong or doesn't exist!", array("debug"=>$auth));
  }
} else {

  // Check if account credentials are valid
  $auth = auth_isvalid_account($_POST["username"], $_POST["password"], $_POST["type"]);
  if ($auth["valid"]) {

    // Update token
    $token = auth_update_token($auth["id"], $_POST["type"]);
    sendResponse(200, "Token successfully requested!", array("token"=>$token));

  } else {
    sendResponse(401, "Account doesn't exist or credentials/username is wrong!", array("debug"=>$auth));
  }

}


include_once("../functions/get_details_influencer.php");



if ($_SERVER["REQUEST_METHOD"] === "GET") {
  // Debug
  //echo (json_encode($_GET, JSON_PRETTY_PRINT)); 
  
  // api/influencers/{id}
  if (isset($_GET["influencers"]) && $_GET["influencers"]!="") {
    $id = $_GET["influencers"];
    
    $details = get_details_influencer($id);
    sendResponse(200, "Influencer successfully requested!", $details);
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
