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
        
        $stmt = $conn->prepare("SELECT 
                                    m.movieID,
                                    m.name AS movieName,
                                    SUM(t.charge) AS totalCharge,
                                    SUM(t.charge / s.charge) AS number
                                FROM
                                    Movies m
                                        INNER JOIN
                                    Shows sh ON m.movieID = sh.movieID
                                        INNER JOIN
                                    Screens s ON sh.screenId = s.screenId
                                        INNER JOIN
                                    Theaters th ON s.theaterID = th.theaterID
                                        LEFT JOIN
                                    Tickets t ON sh.showId = t.showId
                                WHERE
                                    th.theaterID = ?
                                GROUP BY
                                    m.movieID, m.name;");
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
                    "statistic" => $rows
                ));
    } else generateResponse(array("message" => "Error"));
} else {
    generateResponse(array("message" => "Error"));
}