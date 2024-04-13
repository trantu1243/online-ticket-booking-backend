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
        $id = $_POST['id'];
        $id = intval($id);
        $name = $_POST['screenName'];
        $charge = $_POST['charge'];

        if ($name && $charge) {
            $charge = intval($charge);
            $stmt2 = $conn->prepare("UPDATE Screens
                                    SET screenName = ?,
                                    charge = ?
                                    WHERE screenId = ?");
            $stmt2->bind_param("sii", $name, $charge, $id);

            if ($stmt2->execute()) generateResponse(array("message" => "Ok",));
        
        } else generateResponse(array("message" => "error1",));
        
    } else generateResponse(array("message" => "Error2"));
} else {
    generateResponse(array("message" => "Error3"));
}