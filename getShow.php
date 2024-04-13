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
        $id = $_GET['movieID'];
        $id = intval($id);

        $state = $_GET['state'];
        $date = $_GET['date'];
        $theaterId = $_GET['theaterId'];
        $screenId = $_GET['screenId'];
    

        if ($id && $state && $date && $theaterId && $screenId){
            $id = intval($id);
            $theaterId = intval($theaterId);
            $screenId = intval($screenId);
            $stmt = $conn->prepare("SELECT Shows.showId, Shows.showTime
                                    FROM Shows
                                    JOIN Screens ON Shows.screenId = Screens.screenId
                                    JOIN Theaters ON Screens.theaterID = Theaters.theaterID
                                    WHERE Theaters.state = ?
                                    AND Shows.showDate = ?
                                    AND Theaters.theaterID = ?
                                    AND Shows.movieID = ?
                                    AND Shows.screenId = ?");
            $stmt->bind_param("ssiii", $state, $date, $theaterId, $id, $screenId);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            if ($result->num_rows > 0) {
                generateResponse(array(
                            "message" => "Ok",
                            "show" => $rows
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
} else {
    generateResponse(array(
                "message" => "Error",
            ));
} 

?>