<?php
include("../common/header.php");

if (isset($_GET["details"]) && $_GET["details"]!="") {
  $details = $_GET["details"];
  echo $details;
}
  
