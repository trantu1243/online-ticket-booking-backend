<?php
include '../config.php';
include '../authentication/jwtAuthentication.php';
include '../response/response.php';

$headers = apache_request_headers();
$authorizationHeader = $headers['Authorization'];

if(isset($authorizationHeader) && $authorizationHeader != "") {
    $token = explode(" ", $authorizationHeader)[1];
    $user = verifyToken($token);
    if ($user['userType'] == 1) {
        $movieID = $_POST['movieID'];
        $screenId = $_POST['screenId'];
        $showDate = $_POST['showDate'];
        $time = $_POST['time'];

        if($movieID && $screenId && $showDate && $time){ 
            $stmt2 = $conn->prepare("INSERT INTO Shows (movieID, screenId, showDate, showTime) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiss", $movieID, $screenId, $showDate, $time);
            if ($stmt2->execute()){
                generateResponse(array(
                    "message" => "Ok",
                ));
            } else {
                generateResponse(array("message" => "Error"));
            }
        
        }

    }
} else {
    generateResponse(array("message" => "Error"));
}