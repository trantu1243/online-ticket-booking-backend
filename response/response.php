<?php

function generateResponse($data){
    header("Content-Type: application/json");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, PATCH, OPTIONS");
    $response = array(
        "statusCode" => 200,
        "body" => json_encode($data)
    );
    echo json_encode($response);
}
?>