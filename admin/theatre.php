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
        $stmt = $conn->prepare("SELECT Theaters.*, Users.username
                                FROM Theaters
                                INNER JOIN Users ON Theaters.userId = Users.userId;");
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        generateResponse(array(
                    "message" => "Ok",
                    "theatre" => $rows
                ));
    } else generateResponse(array("message" => "Error"));
} else {
    generateResponse(array("message" => "Error"));
}