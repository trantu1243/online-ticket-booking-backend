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
        $stmt = $conn->prepare("SELECT 
                            th.theaterID,
                            th.name AS name,
                            th.state AS state,
                            th.address AS address,
                            th.zipCode AS zipCode,
                            u.username,
                            SUM(t.charge) AS totalCharge
                        FROM
                            Theaters th
                                LEFT JOIN
                            Users u ON th.userId = u.userId
                                LEFT JOIN
                            Screens s ON th.theaterID = s.theaterID
                                LEFT JOIN
                            Shows sh ON s.screenId = sh.screenId
                                LEFT JOIN
                            Tickets t ON sh.showId = t.showId
                        GROUP BY th.theaterID
                        ORDER BY th.theaterID;");
        $stmt->execute();
        $result = $stmt->get_result();

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        generateResponse(array(
                    "message" => "Ok",
                    "theatre" => $rows
                ));
    } else generateResponse(array("message" => "Error"));
} else {
    generateResponse(array("message" => "Error"));
}