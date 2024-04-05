<?php
include 'config.php';
include 'response/response.php';

require 'vendor/autoload.php';

use Firebase\JWT\JWT;

$key = "online ticket booking";

// Lấy dữ liệu từ frontend
$username = $_POST['username'];
$password = $_POST['password'];

// Truy vấn để lấy thông tin người dùng từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM Users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];
    
    // Kiểm tra mật khẩu và user type
    if (password_verify($password, $hashed_password) && ($user['userType']) == 2) {
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
        // Mật khẩu không chính xác
        generateResponse(array("message" => "error"));
    }
} else {
    // Người dùng không tồn tại
    generateResponse(array("message" => "error"));
}

$stmt->close();
$conn->close();
?>