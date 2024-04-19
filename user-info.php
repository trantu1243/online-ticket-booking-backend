<?php
include 'config.php';
include 'jwtAuthentication.php';
include 'response/response.php';

$headers = apache_request_headers();
$authorizationHeader = $headers['Authorization'];

if(isset($authorizationHeader) && $authorizationHeader != "") {
    $token = explode(" ", $authorizationHeader)[1];
    $user = verifyToken($token);
    if ($user['userType'] == 2) {
        $name = $_POST['name'] ?? "";
        $phoneNumber = $_POST['phoneNumber'] ?? "";
        $cccd = $_POST['cccd'] ?? "";
        $birthday = $_POST['birthday'] ?? "";
        $sex = $_POST['sex'] ?? "";
        $address = $_POST['address'] ?? "";

        $stmt = $conn->prepare("UPDATE Users
                                SET 
                                    name = ?,
                                    phoneNumber = ?,
                                    cccd = ?,
                                    birthday = ?,
                                    sex = ?,
                                    address = ?
                                WHERE 
                                    userId = ?;");
        $stmt->bind_param("ssssssi", $name, $phoneNumber, $cccd, $birthday, $sex, $address, $user['userId']);
        if ($stmt->execute()){
            generateResponse(array(
                "message" => "Ok",
            ));
        } else {
            generateResponse(array("message" => "Error"));
        }
        
    }
} else {
    generateResponse(array("message" => "Error"));
}