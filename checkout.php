<?php
include 'config.php';
include 'jwtAuthentication.php';
include 'response/response.php';

$headers = apache_request_headers();
$authorizationHeader = $headers['Authorization'];

if(isset($authorizationHeader) && $authorizationHeader != "")
{
    $token = explode(" ", $authorizationHeader)[1];
    $user = verifyToken($token);
    if ($user['userType'] == 2) {
        $id = $_POST['id'];
        $str = $_POST['seats'];
        $charge = $_POST['price'];

        $id = intval($id);
        $charge = intval($charge);

        $seats = explode(",", $str);
        $stmt = $conn->prepare('INSERT INTO Seats (showId, seatName) VALUES (?, ?)');
        foreach($seats as $seat){
            $stmt->bind_param("is", $id, $seat);
            $stmt->execute();
        }

        $stmt2 = $conn->prepare('INSERT INTO Tickets (showId, userId, seats, charge) VALUES (?, ?, ?, ?)');
        $stmt2->bind_param("iisi", $id, $user['userId'], $str, $charge);
        $stmt2->execute();
        generateResponse(array(
            "message" => "Ok",
        ));
    } else {
        generateResponse(array(
            "message" => "Error",
        ));
    } 
} else {
    generateResponse(array(
        "message" => "Error",
    ));
} 

?>