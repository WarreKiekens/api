<?php
function sendResponse($resp_code, $message, $data = null, $error = null){
    $result = array(
     'code'=>$resp_code,
     'message'=>$message
    );
    
    if ($data != null) {
        $result['data'] = $data;
    }
    
    
    if ($error != null) {
        $result['error'] = $error;
    }
    
    echo json_encode($result);
    
    // Stop API after sending response
    die();
}
?>
