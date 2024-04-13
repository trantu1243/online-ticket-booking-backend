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

        $stmt = $conn->prepare("DELETE FROM Movies WHERE movieID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        generateResponse(array(
                    "message" => "Ok",
                ));
    } else generateResponse(array("message" => "Error"));
} else {
    generateResponse(array("message" => "Error"));
}