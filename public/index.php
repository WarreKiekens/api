<?php
include_once("../common/header.php");
include_once("../common/response.php");
include_once("../common/get_query_data.php");

include_once("../functions/auth_isvalid_account.php");
include_once("../functions/auth_isvalid_token.php");
include_once("../functions/auth_update_token.php");

include_once("../functions/auth_create_account.php");

include_once("../functions/get_details_influencers.php");
include_once("../functions/get_details_influencer.php");
include_once("../functions/get_details_influencer_posts.php");
include_once("../functions/get_details_influencer_post.php");

include_once("../functions/get_details_cities.php");
include_once("../functions/get_details_city.php");




$rawBody = json_decode(file_get_contents("php://input"), true);
if (count($rawBody) > 0) {
  $_POST = $rawBody;
}

// Check if path start with /api
if (explode("/",$_SERVER["REDIRECT_URL"])[1] != "api") {
  echo "Wrong path<br>";
  var_dump($_SERVER);
  var_dump(explode("/",$_SERVER["REDIRECT_URL"]));
  die();
}


// Public functions
if ($_SERVER["REQUEST_METHOD"] === "GET" && !isset($_GET["influencers"])) {

  // /api/cities...
  if (strpos($_SERVER["REQUEST_URI"], "/api/cities") === 0) {

    // /api/cities/{id}
    if (isset($_GET["cities"]) && $_GET["cities"] != "") {
      $cityId = $_GET["cities"];

      $details = get_details_city($cityId);

      if ($details["valid"]) {
        sendResponse(200, "City successfully requested!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      }    
    }

    // /api/cities
    $details = get_details_cities();

    if ($details["valid"]) {
      sendResponse(200, "Cities successfully requested!", $details["data"]);
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    }
  }
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

  // login
  
  if (strpos($_SERVER["REQUEST_URI"], "/api/login") === 0) {
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
  
  
  // /api/register (everyone access, no token required)
  if (strpos($_SERVER["REQUEST_URI"], "/api/register") === 0) {
    
    //  Create request for stad-access or influencer depending on type
    $fields = $_POST;
    $details = auth_create_account($fields);

    if ($details["valid"]){
      // check if type is stad or influencer and send diff msg
      sendResponse(200, "Access request successfully submitted!", $details["data"]);

    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    }
    
  }
  
  

}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
  // Debug
  //echo (json_encode($_GET, JSON_PRETTY_PRINT));  
  
  // /api/id...
  if (strpos($_SERVER["REQUEST_URI"], "/api/id") === 0) {
    sendResponse(200, "Id successfully requested!", array("id" => $GLOBALS["account_id"]));
  }
  
  // /api/me...
  if (strpos($_SERVER["REQUEST_URI"], "/api/me") === 0) {
    
    sendResponse(200, "Validation details successfully requested!", array("id" => $GLOBALS["account_id"], "type" => $GLOBALS["account_type"]));
  }
  
  // /api/influencers...
  if (strpos($_SERVER["REQUEST_URI"], "/api/influencers") === 0) {
     
    // /api/influencers/{id}/posts/{id}
    if (isset($_GET["influencers"]) && $_GET["influencers"] != "" && isset($_GET["posts"]) && $_GET["posts"] != "") {
      $influencerId = $_GET["influencers"];
      $postId = $_GET["posts"];
      $details = get_details_influencer_post($influencerId, $postId);
      
      if ($details["valid"]) {
        sendResponse(200, "Posts successfully requested!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      } 
      
    }
  
    // /api/influencers/{id}/posts 
    if (isset($_GET["influencers"]) && $_GET["influencers"] != "" && isset($_GET["posts"])) {
      $influencerId = $_GET["influencers"];
      $details = get_details_influencer_posts($influencerId);
      
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
  
  
  // /api/cities...
  if (strpos($_SERVER["REQUEST_URI"], "/api/influencers") === 0) {
    
    // /api/cities/{id}/influencers
    if (isset($_GET["cities"]) && $_GET["cities"] != "" && isset($_GET["influencers"]) && $_GET["influencers"] == "") {
      $cityId = $_GET["cities"];

      //$details = get_details_city_influencers($cityId);
      
      die();
      
      if ($details["valid"]) {
        sendResponse(200, "Influencers of city successfully requested!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      }    
    }
    
    
  }
  
   
  
  
  

}  

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Debug
  //echo (json_encode($_POST, JSON_PRETTY_PRINT));
  
  // Activate city accounts
  if ($GLOBALS["account_type"] == "admin"){
   
  }
  
  
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {}
