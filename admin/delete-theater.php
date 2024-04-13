<?php
include '../config.php';
include '../authentication/jwtAuthentication.php';
include '../response/response.php';

$headers = apache_request_headers();
$authorizationHeader = $headers['Authorization'];

if(isset($authorizationHeader) && $authorizationHeader != "") {
    $token = explode(" ", $authorizationHeader)[1];
    $user = verifyToken($token);
    if ($user['userType'] == 0) {
        $id = $_POST['id'];
        $userId = $_POST['userId'];
        $id = intval($id);
        $userId = intval($userId);

        $stmt = $conn->prepare("DELETE FROM Theaters WHERE theaterID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt = $conn->prepare("DELETE FROM Theaters WHERE UserId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        generateResponse(array(
                    "message" => "Ok",
                ));
    } else generateResponse(array("message" => "Error"));
} else {
    generateResponse(array("message" => "Error"));
}