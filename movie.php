<?php
include 'config.php';
include 'response/response.php';

$id = $_GET["id"];
$id = intval($id);

$stmt = $conn->prepare("SELECT * FROM Movies WHERE movieID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    generateResponse(array(
                "message" => "Ok",
                "movie" => $result->fetch_assoc()
            ));
} else {
    generateResponse(array(
                "message" => "Error",
            ));
}
$stmt->close();
$conn->close();
?>