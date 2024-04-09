<?php
include 'config.php';
include 'jwtAuthentication.php';
include 'response/response.php';

$headers = apache_request_headers();
$authorizationHeader = $headers['Authorization'];

if(isset($authorizationHeader) && $authorizationHeader != "") {
    $token = explode(" ", $authorizationHeader)[1];
    $user = verifyToken($token);
    if ($user['userType'] == 1) {
        $stmt = $conn->prepare("SELECT Screens.* FROM Users JOIN Theaters ON Users.userId = Theaters.userId JOIN Screens ON Theaters.theaterID = Screens.theaterID WHERE Users.userId = ?");
        $stmt->bind_param("i", $user['userId']);
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        generateResponse(array(
                    "message" => "Ok",
                    "screens" => $rows
                ));
    } else {
        generateResponse(array(
            "message" => "Error"
        ));
    }
}
else {
    generateResponse(array(
        "message" => "Error"
    ));
}

$stmt->close();
$conn->close();
?>