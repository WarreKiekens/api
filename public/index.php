<?php

// Common functions
include_once("../common/header.php");
include_once("../common/response.php");
include_once("../common/get_query_data.php");
include_once("../common/http_parse.php");
include_once("../common/filtering.php");

// Authentication
  // Verify
include_once("../functions/auth_isvalid_account.php");
include_once("../functions/auth_isvalid_token.php");
  // PUT
include_once("../functions/auth_update_token.php");
  // POST
include_once("../functions/auth_create_account.php");

// Data
  // GET
include_once("../functions/get_details_influencers.php");
include_once("../functions/get_details_influencer.php");
include_once("../functions/get_details_influencer_posts.php");
include_once("../functions/get_details_influencer_post.php");

include_once("../functions/get_details_cities.php");
include_once("../functions/get_details_city.php");
include_once("../functions/get_details_city_influencers.php");

include_once("../functions/get_details_categories.php");

include_once("../functions/get_details_tasks.php"); 
include_once("../functions/get_details_task.php"); 
include_once("../functions/get_details_posts_task.php"); 

// todo
include_once("../functions/get_details_posts.php"); 


  // POST
include_once("../functions/post_create_admin.php");
include_once("../functions/post_create_task.php"); 

  // PUT
include_once("../functions/put_isactive.php");
include_once("../functions/put_update_account.php");
include_once("../functions/put_update_task.php");
include_once("../functions/put_verify_post.php");



$rawBody = json_decode(file_get_contents("php://input"), true);
if (count($rawBody) > 0) {
  $_POST = $rawBody;
}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {

  if (explode(";", $_SERVER["CONTENT_TYPE"])[0] == "multipart/form-data") {
    $_PUT = array();
    parse_raw_http_request($_PUT);
    
  } elseif (isset($_SERVER["QUERY_STRING"]) & $_SERVER["QUERY_STRING"] != "") {
    parse_str(file_get_contents("php://input"),$put_vars);
    parse_str($_SERVER["QUERY_STRING"], $params);
    
    $_PUT = array_merge($put_vars, $params);
  } else {
    $_PUT = $rawBody;
  }
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
  
  // /api/categories...
  if (strpos($_SERVER["REQUEST_URI"], "/api/categories") === 0) {
    $details = get_details_categories();
    
    if ($details["valid"]) {
      sendResponse(200, "Categories successfully requested!", $details["data"]);
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    } 
  }

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
  
  // /api/list...
  if (strpos($_SERVER["REQUEST_URI"], "/api/list") === 0) {
        
    // /api/list/cities/{name} or /api/list/cities/{postcode}
    if (isset($_GET["cityvalue"]) && $_GET["cityvalue"] != "") {
      if (is_numeric($_GET["cityvalue"])) {
        
        // Search a partial string matching in array elements
        function array_search_partial($arr, $keyword) {
          $results = array();
          foreach($arr as $index => $string) {
            if (strpos($string, $keyword) !== FALSE) {
                  return $index;
            }
          }
        }
        
        sendResponse(200, "Cities successfully requested, using postcode!",  array_search(intval($_GET["cityvalue"]), json_decode(file_get_contents("../common/all_cities.json", true), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)));
          
      } else {
        sendResponse(200, "Cities successfully requested, using name!", json_decode(file_get_contents("../common/all_cities.json", true), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)[$_GET["cityvalue"]] );
      
      }
    }
    
    // /api/list/cities

    sendResponse(200, "Cities successfully requested!", json_decode(file_get_contents("../common/all_cities.json", true), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    
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
  $GLOBALS["account_issuper"] = $auth["super"];
    
} else {

  // login
  
  if (strpos($_SERVER["REQUEST_URI"], "/api/login") === 0) {
    // Check if account credentials are valid
    $auth = auth_isvalid_account($_POST["username"], $_POST["password"]);
    if ($auth["valid"]) {

      // Update token
      $token = auth_update_token($auth["id"], $auth["type"]);

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
  
  sendResponse(404, "Page not found!", $details["data"], "PageNotFound");

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
    
    sendResponse(200, "Validation details successfully requested!", array("id" => $GLOBALS["account_id"], "type" => $GLOBALS["account_type"], "issuper" => $GLOBALS["account_issuper"]));
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
  if (strpos($_SERVER["REQUEST_URI"], "/api/cities") === 0) {
    
    // /api/cities/{id}/influencers
    if (isset($_GET["cities"]) && $_GET["cities"] != "" && isset($_GET["influencers"]) && $_GET["influencers"] == "") {
      $cityId = $_GET["cities"];

      $details = get_details_city_influencers($cityId);
      
      if ($details["valid"]) {
        sendResponse(200, "Influencers of city successfully requested!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      }    
    }
    
  }
  
  // /api/tasks...
  if (strpos($_SERVER["REQUEST_URI"], "/api/tasks") === 0) {
    
    // /api/tasks/{id}/posts
    if (isset($_GET["tasks"]) && $_GET["tasks"] != "" && isset($_GET["posts"]) && $_GET["posts"] == "") {
    
      $details = get_details_posts_task($_GET["tasks"]);

      if ($details["valid"]) {
        sendResponse(200, "Posts of a task successfully requested!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      } 
      
    }
    
    // /api/tasks/{id}
    if (isset($_GET["tasks"]) && $_GET["tasks"] != "") {
      $details = get_details_task($_GET["tasks"]);

      if ($details["valid"]) {
        sendResponse(200, "Tasks of city successfully requested!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      } 
    }
      
    // /api/tasks
    $details = get_details_tasks();
    
    if ($details["valid"]) {
      sendResponse(200, "Tasks of city successfully requested!", $details["data"]);
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    } 
    
  }
  
  // /api/posts...
  if (strpos($_SERVER["REQUEST_URI"], "/api/posts") === 0) {
    
    // /api/posts
    $details = get_details_posts();
    
    if ($details["valid"]) {
      sendResponse(200, "Posts of influencer successfully requested!", $details["data"]);
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    } 
    
        
  }

}  

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Debug
  //echo (json_encode($_POST, JSON_PRETTY_PRINT));
  

  // /api/accounts...
  if (strpos($_SERVER["REQUEST_URI"], "/api/accounts") === 0) {
    
     $values = $_POST;
     $details = post_create_admin($values);
    
    if ($details["valid"]) {
      sendResponse(200, "Account successfully created!");
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    }
  }
  
  // /api/tasks...
  if (strpos($_SERVER["REQUEST_URI"], "/api/tasks") === 0) {
  
     $values = $_POST;
     $details = post_create_task($values);
    
    if ($details["valid"]) {
      sendResponse(200, "Posts successfully created!");
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    }
  } 
  
}

if ($_SERVER["REQUEST_METHOD"] === "DELETE") {}

if ($_SERVER["REQUEST_METHOD"] === "PUT") {
  
  // /api/activation...
  if (strpos($_SERVER["REQUEST_URI"], "/api/activation") === 0) {
    
    // /api/activation
    $type = $_PUT["type"];
    $id = $_PUT["id"];
    $value = $_PUT["value"];
    
    $details = put_isactive($type, $id, $value);
    sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
  }
  
  // /api/accounts...
  if (strpos($_SERVER["REQUEST_URI"], "/api/accounts") === 0) {
    
     $values = $_PUT;
     $details = put_update_account($values);
    
    if ($details["valid"]) {
      sendResponse(200, "Details successfully updated!", $details["data"]);
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    }
  }
  
  
  // /api/tasks...
  if (strpos($_SERVER["REQUEST_URI"], "/api/tasks") === 0) {
    
    // /api/tasks/{id}/posts/{id}  => perspective of city
    if (isset($_GET["tasks"]) && $_GET["tasks"] != "" && isset($_GET["posts"]) && $_GET["posts"] != "") {
      
      $values = $_PUT;
      $details = put_verify_post($values);

      if ($details["valid"]) {
        sendResponse(200, "Task successfully updated!", $details["data"]);
      } else {
        sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
      }
      
    }
    
    
    // /api/tasks
    $values = $_PUT;
    $details = put_update_task($values);
    
    if ($details["valid"]) {
      sendResponse(200, "Task successfully updated!", $details["data"]);
    } else {
      sendResponse($details["code"], $details["message"], $details["data"], $details["error"]);
    }
  }
  
  
}

sendResponse(404, "Page not found!", $details["data"], "PageNotFound");

