<?php
function sendResponse($resp_code, $message, $data){
    echo json_encode(array(
     'code'=>$resp_code,
     'message'=>$message,
     'data'=>$data
    ));
    
    // Stop API after sending response
    die();
}
?>
