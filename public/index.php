<?php
include_once("../common/header.php");
include_once("../common/response.php");
include_once("../common/get_query_data.php");

include_once("../functions/auth_isvalid_account.php");
include_once("../functions/auth_isvalid_token.php");
include_once("../functions/auth_update_token.php");

// In future, probably needs to be moved to bottom due to performancy issues
include_once("../functions/get_details_influencers.php");
include_once("../functions/get_details_influencer.php");
include_once("../functions/get_details_influencer_posts.php");



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
  $GLOBALS["account_id"] = $auth["id"];
  $GLOBALS["account_type"] = $auth["type"];
    
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
  
  // /api/influencers...
  if (strpos($_SERVER["REQUEST_URI"], "/api/influencers") === 0) {
     
    // /api/influencers/{id}/posts/{id}
    if (isset($_GET["influencers"]) && $_GET["influencers"] != "" && isset($_GET["posts"]) && $_GET["influencers"] != "") {
      $influencerId = $_GET["influencers"];
      $postId = $_GET["posts"];
      //$details = get_details_influencer_post.php();
      
    }
  
    // /api/influencers/{id}/posts 
    if (isset($_GET["influencers"]) && $_GET["influencers"] != "" && isset($_GET["posts"])) {
      $influencerId = $_GET["influencers"];
      $details = get_details_influencer_posts.php();
      
      if ($details["valid"]) {
        sendResponse(200, "Posts successfully requested!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      } 
    }
  
    // /api/influencers/{id}
    if (isset($_GET["influencers"]) && $_GET["influencers"] != "") {
      $influencerId = $_GET["influencers"];

      $details = get_details_influencer($influencerId);

      if ($details["valid"]) {
        sendResponse(200, "Influencer successfully requested!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      }    
    }
    
    // /api/influencers
    $details = get_details_influencers();
    
    if ($details["valid"]) {
      sendResponse(200, "Influencers successfully requested!", $details["data"]);
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    }
  }
  

}  

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Debug
  //echo (json_encode($_POST, JSON_PRETTY_PRINT));
 
  
  // api/login Body:[type(influencer,stad), name, password] Headers:[]
  
  
  
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {}
