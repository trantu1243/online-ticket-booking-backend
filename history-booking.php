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
        $stmt = $conn->prepare("SELECT 
                                    Movies.name AS movieName,
                                    Movies.genre,
                                    Theaters.name AS theaterName,
                                    Screens.screenName,
                                    Shows.showDate,
                                    Shows.showTime,
                                    Tickets.ticketId,
                                    Tickets.seats,
                                    Tickets.charge
                                FROM 
                                    Tickets
                                INNER JOIN 
                                    Users ON Tickets.userId = Users.userId
                                INNER JOIN 
                                    Shows ON Tickets.showId = Shows.showId
                                INNER JOIN 
                                    Movies ON Shows.movieID = Movies.movieID
                                INNER JOIN 
                                    Screens ON Shows.screenId = Screens.screenId
                                INNER JOIN 
                                    Theaters ON Screens.theaterID = Theaters.theaterID
                                WHERE 
                                    Users.userId = ?;");
        $stmt->bind_param("i", $user['userId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        if ($result->num_rows > 0) {
                generateResponse(array(
                            "message" => "Ok",
                            "booking" => $rows
                        ));
            } else {
                generateResponse(array(
                            "message" => "Error",
                        ));
            }
            $stmt->close();
            $conn->close();
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