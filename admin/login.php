<?php
include '../config.php';
include '../response/response.php';

require '../vendor/autoload.php';

use Firebase\JWT\JWT;

$key = "online ticket booking";

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM Users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];
    
    if (password_verify($password, $hashed_password) && ($user['userType']) == 0) {
        $issuedAt = time();
        $expirationTime = $issuedAt + (30 * 24 * 60 * 60);
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'uid' => $user['userId']
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        generateResponse(array(
            "message" => "Ok",
            "username" => $user['username'],
            "token" => $jwt
        ));
    } else {
        generateResponse(array("message" => "Mật khẩu không chính xác"));
    }
} else {
    generateResponse(array("message" => "Người dùng không tồn tại"));
}

$stmt->close();
$conn->close();
?>