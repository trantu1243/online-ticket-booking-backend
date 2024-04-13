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
        $id = intval($id);
        $name = $_POST['theatreName'];
        $address = $_POST['theatreAddress'];
        $state = $_POST['state'];
        $zipCode = $_POST['zipCode'];

        $regex = '/^.{8,}$/';
        if ($address && $state && $name && $zipCode) {
            $stmt2 = $conn->prepare("UPDATE Theaters
                                    SET name = ?,
                                        state = ?,
                                        address = ?,
                                        zipCode = ?
                                    WHERE theaterID = ?");
            $stmt2->bind_param("ssssi", $name, $state, $address, $zipCode, $id);

            if ($stmt2->execute()) generateResponse(array("message" => "Ok",));
        
        } else generateResponse(array("message" => "error",));
        
    } else generateResponse(array("message" => "Error"));
} else {
    generateResponse(array("message" => "Error"));
}