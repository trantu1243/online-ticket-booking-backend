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
        $name = $_POST['screenName'];
        $charge = $_POST['charge'];

        $stmt = $conn->prepare("SELECT * FROM Theaters WHERE userId=?");
        $stmt->bind_param("s", $user['userId']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $theatre = $result->fetch_assoc();

            if($name && $charge){ 
                $stmt2 = $conn->prepare("INSERT INTO Screens (screenName, charge, theaterId) VALUES (?, ?, ?)");
                $stmt2->bind_param("sis", $name, $charge, $theatre['theaterID']);
                if ($stmt2->execute()){
                    generateResponse(array(
                        "message" => "Ok",
                    ));
                } else {
                    generateResponse(array("message" => "Error"));
                }
            
            }
        }
    }
} else {
    generateResponse(array("message" => "Error"));
}