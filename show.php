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
        $id = $_GET['showId'];
        
        if ($id){
            $id = intval($id);
            $stmt = $conn->prepare("SELECT Movies.*
                                    FROM Shows
                                    JOIN Movies ON Shows.movieID = Movies.movieID
                                    WHERE Shows.showId = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt2 = $conn->prepare("SELECT seatName
                                    FROM Seats
                                    WHERE showId = ?");
            $stmt2->bind_param("i", $id);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            $stmt3 = $conn->prepare("SELECT Screens.charge
                                    FROM Shows
                                    INNER JOIN Screens ON Shows.screenId = Screens.screenId
                                    WHERE Shows.showId = ?");
            $stmt3->bind_param("i", $id);
            $stmt3->execute();
            $result3 = $stmt3->get_result();

            $rows = array();
            while ($row = $result2->fetch_assoc()) {
                $rows[] = $row;
            }
            if ($result->num_rows > 0 && $result3->num_rows > 0) {
                $movie = $result->fetch_assoc();
                generateResponse(array(
                            "message" => "Ok",
                            "movie" => $movie,
                            "seats" => $rows,
                            "price" => $result3->fetch_assoc()
                        ));
            } else {
                generateResponse(array(
                            "message" => "Error",
                        ));
            } 
            $stmt->close();
            $conn->close();
        } 
    }
}else {
    generateResponse(array(
                "message" => "Error",
            ));
} 

?>