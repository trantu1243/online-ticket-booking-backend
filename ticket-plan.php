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
        $currentDate = date('Y-m-d');

        if ($state == '' && $date == '' && $theaterId == ''){
            $stmt = $conn->prepare("SELECT DISTINCT t.state
                                    FROM Shows s
                                    INNER JOIN Screens sc ON s.screenId = sc.screenId
                                    INNER JOIN Theaters t ON sc.theaterID = t.theaterID
                                    WHERE s.movieID = ? AND s.showDate >= ?");
            $stmt->bind_param("is", $id, $currentDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            if ($result->num_rows > 0) {
                generateResponse(array(
                            "message" => "Ok",
                            "city" => $rows
                        ));
            } else {
                generateResponse(array(
                            "message" => "Error",
                        ));
            }
            $stmt->close();
            $conn->close();
        } else if ($date == '' && $theaterId == ''){
            $stmt = $conn->prepare("SELECT DISTINCT s.showDate
                                    FROM Shows s
                                    INNER JOIN Screens sc ON s.screenId = sc.screenId
                                    INNER JOIN Theaters t ON sc.theaterID = t.theaterID
                                    WHERE t.state = ? AND s.movieID = ? AND s.showDate >= ?");
            $stmt->bind_param("sis", $state, $id, $currentDate);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            if ($result->num_rows > 0) {
                generateResponse(array(
                            "message" => "Ok",
                            "date" => $rows
                        ));
            } else {
                generateResponse(array(
                            "message" => "Error",
                        ));
            }
            $stmt->close();
            $conn->close();
        } else if ($theaterId == ''){
            $stmt = $conn->prepare("SELECT DISTINCT Theaters.theaterID, Theaters.name
                                    FROM Shows
                                    JOIN Screens ON Shows.screenId = Screens.screenId
                                    JOIN Theaters ON Screens.theaterID = Theaters.theaterID
                                    WHERE Theaters.state = ? AND Shows.showDate = ? AND Shows.movieID = ?");
            $stmt->bind_param("ssi", $state, $date, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            if ($result->num_rows > 0) {
                generateResponse(array(
                            "message" => "Ok",
                            "theater" => $rows
                        ));
            } else {
                generateResponse(array(
                            "message" => "Error",
                        ));
            } 
            $stmt->close();
            $conn->close();
        } else{
            $theaterId = intval($theaterId);
            $stmt = $conn->prepare("SELECT DISTINCT Shows.screenId, Screens.screenName
                                    FROM Shows
                                    JOIN Screens ON Shows.screenId = Screens.screenId
                                    JOIN Theaters ON Screens.theaterID = Theaters.theaterID
                                    WHERE Theaters.state = ?
                                    AND Shows.showDate = ?
                                    AND Theaters.theaterID = ?
                                    AND Shows.movieID = ?");
            $stmt->bind_param("ssii", $state, $date, $theaterId, $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            if ($result->num_rows > 0) {
                generateResponse(array(
                            "message" => "Ok",
                            "screen" => $rows
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