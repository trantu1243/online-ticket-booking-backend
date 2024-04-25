<?php
include 'config.php';
include 'response/response.php';

$date = $_GET['date'];

$stmt = $conn->prepare("SELECT DISTINCT m.*
                        FROM Shows s
                        INNER JOIN Movies m ON s.movieID = m.movieID
                        WHERE s.showDate = ?;"
                        );
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$rows = array();
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}
generateResponse(array(
            "message" => "Ok",
            "movies" => $rows
        ));

$stmt->close();
$conn->close();
?>