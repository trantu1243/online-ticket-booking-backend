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
        $name = $_POST['movieName'];
        $description = $_POST['description'];
        $releaseDate = $_POST['releaseDate'];
        $duration = $_POST['duration'];
        $genre = $_POST['genre'];
        $videoUrl = $_POST['videoUrl'];
        $tomatoPoint = $_POST['tomatoPoint'];

        if ($name && $description && $releaseDate && $duration && $genre && $videoUrl && $tomatoPoint) {
            $stmt2 = $conn->prepare("UPDATE Movies
                                    SET name = ?,
                                    description = ?,
                                    releaseDate = ?,
                                    duration = ?,
                                    genre = ?,
                                    videoUrl = ?,
                                    tomatoPoint = ?
                                    WHERE movieID = ?");
            $stmt2->bind_param("sssissii", $name, $description, $releaseDate, $duration, $genre, $videoUrl, $tomatoPoint, $id);

            if ($stmt2->execute()) generateResponse(array("message" => "Ok",));
        
        } else generateResponse(array("message" => "error1",));
        
    } else generateResponse(array("message" => "Error2"));
} else {
    generateResponse(array("message" => "Error3"));
}