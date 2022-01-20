<?php
function sendResponse($resp_code, $message, $data){
    $result = array(
     'code'=>$resp_code,
     'message'=>$message
    );
    
    if ($data != null) {
        $result['data'] = $data;
    }
    
    echo json_encode($result);
    
    // Stop API after sending response
    die();
}
?>
