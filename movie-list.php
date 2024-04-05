<?php
include 'config.php';
include 'response/response.php';

$stmt = $conn->prepare("SELECT * FROM Movies");
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