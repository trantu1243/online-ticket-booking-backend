<?php
include 'authentication/jwtAuthentication.php';
include 'response/response.php';

$headers = apache_request_headers();
$authorizationHeader = $headers['Authorization'];

if(isset($authorizationHeader) && $authorizationHeader != "") {
    $token = explode(" ", $authorizationHeader)[1];
    $user = verifyToken($token);
    if ($user) {
        generateResponse(array(
            "message" => "Ok",
            "user" => $user
        ));
    } else {
        generateResponse(array("message" => "Error"));
    }
} else {
    generateResponse(array("message" => "Error"));
}