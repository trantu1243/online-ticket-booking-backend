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
        $id = $_GET['id'];

        if ($id){
            $id = intval($id);
    
            $stmt = $conn->prepare("SELECT Movies.name AS movieName, Movies.genre, Movies.image, Theaters.state, Shows.showDate, Shows.showTime, Screens.screenName, Screens.charge, Theaters.name AS theaterName
                                    FROM Shows
                                    JOIN Movies ON Shows.movieID = Movies.movieID
                                    JOIN Screens ON Shows.screenId = Screens.screenId
                                    JOIN Theaters ON Screens.theaterID = Theaters.theaterID
                                    WHERE Shows.showId = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if ($result->num_rows > 0) {
                generateResponse(array(
                            "message" => "Ok",
                            "checkout" => $result ->fetch_assoc()
                        ));
            } else {
                generateResponse(array(
                            "message" => "Error",
                        ));
            }
            $stmt->close();
            $conn->close();
        } 
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