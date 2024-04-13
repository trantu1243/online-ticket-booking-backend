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

        $stmt = $conn->prepare("SELECT theaterID FROM Theaters WHERE userId = ?");
        $stmt->bind_param('i', $user['userId']);
        $stmt->execute();
        $stmt->bind_result($theaterId);
        $stmt->fetch();
        $stmt->close();
        
        $stmt = $conn->prepare("SELECT Shows.*, Movies.name AS movieName, Screens.screenName
                                FROM Shows
                                INNER JOIN Movies ON Shows.movieID = Movies.movieID
                                INNER JOIN Screens ON Shows.screenId = Screens.screenId
                                WHERE Screens.theaterID = ?");
        $stmt->bind_param('i', $theaterId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stmt->close();
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        generateResponse(array(
                    "message" => "Ok",
                    "show" => $rows
                ));
    } else generateResponse(array("message" => "Error"));
} else {
    generateResponse(array("message" => "Error"));
}