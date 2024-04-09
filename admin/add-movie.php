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
        $name = $_POST['movieName'];
        $description = $_POST['description'];
        $releaseDate = $_POST['releaseDate'];
        $duration = $_POST['duration'];
        $genre = $_POST['genre'];
        $videoUrl = $_POST['videoUrl'];
        $tomatoPoint = $_POST['tomatoPoint'];

        $poster = $_FILES['poster'];
        $image = $_FILES['image'];

        if($name && $description && $releaseDate && $duration && $genre && $videoUrl && $tomatoPoint && $poster && $image){
            $uploadDir = '../images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $posterName = uniqid() . '_' . $poster['name'];
            $posterPath = $uploadDir . $posterName;
            $imageName = uniqid() . '_' . $image['name'];
            $imagePath = $uploadDir . $imageName;
            if (move_uploaded_file($poster['tmp_name'], $posterPath) && move_uploaded_file($image['tmp_name'], $imagePath)) {

                $posPath = '/images/' . $posterName;
                $imgPath = '/images' . $imageName;
                $stmt2 = $conn->prepare("INSERT INTO Movies (name, description, releaseDate, duration, genre, poster, image, videoUrl, tomatoPoint) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt2->bind_param("sssissssi", $name, $description, $releaseDate, $duration, $genre, $posPath, $imgPath, $videoUrl, $tomatoPoint);
                if ($stmt2->execute()){
                    generateResponse(array(
                        "message" => "Ok",
                    ));
                } else {
                    generateResponse(array("message" => "Error"));
                }
            } else {
                generateResponse(array("message" => "Error"));
            }
            
        }
    }
} else {
    generateResponse(array("message" => "Error"));
}