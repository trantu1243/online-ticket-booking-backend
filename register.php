<?php
include 'config.php';
include 'response/response.php';

// require 'vendor/autoload.php';

// use Firebase\JWT\JWT;

// $key = "online ticket booking";

// Lấy dữ liệu từ frontend
$username = $_POST['username'];
$password = $_POST['password'];
$cfPassword = $_POST['confirmPassword'];
$email = $_POST['email'];

$regex = '/^.{8,}$/';

$emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

// Kiểm tra xem người dùng đã tồn tại chưa
$stmt = $conn->prepare("SELECT * FROM Users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($password != $cfPassword){
    generateResponse(array(
        "message" => "error",
        "error" => "Password not match"
        ));
} else if (!preg_match($regex, $username)){
    generateResponse(array(
        "message" => "error",
        "error" => "Invalid username"
        ));
} else if (!preg_match($emailRegex, $email)){
    generateResponse(array(
        "message" => "error",
        "error" => "Invalid email"
        ));
} else if (!preg_match($regex, $password)){
    generateResponse(array(
        "message" => "error",
        "error" => "Invalid password"
        ));
} else if ($result->num_rows > 0) {
    generateResponse(array(
        "message" => "error",
        "error" => "Username already exists"
        ));
} else {
    // Thêm người dùng mới vào cơ sở dữ liệu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $userType = 2;
    $stmt = $conn->prepare("INSERT INTO Users (username, password, email, userType) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $hashed_password, $email, $userType);
    if ($stmt->execute()) {
        // Đăng ký thành công
//        $issuedAt = time();
//        $expirationTime = $issuedAt + (30 * 24 * 60 * 60);
//        $user_id = $stmt->insert_id;
//        $payload = array(
//            'iat' => $issuedAt,
//            'exp' => $expirationTime,
//            'uid' => $user_id
//        );
//        $jwt = JWT::encode($payload, $key, 'HS256');
        generateResponse(array(
            "message" => "Ok",
//            "username" => $username,
//            "token" => $jwt,
        ));
    } else {
        generateResponse(array(
            "message" => "error",
            "error" => "Failed"
        ));
    }
}

$stmt->close();
$conn->close();
?>