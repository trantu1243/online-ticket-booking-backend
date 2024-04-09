<?php
require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function verifyToken($token) {
    include 'config.php';

    $key = "online ticket booking";
    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $id = $decoded->uid;
        $stmt = $conn->prepare("SELECT * FROM Users WHERE userId=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $conn->close();
        $user=$result->fetch_assoc();
        return array(
            'userId' => $user['userId'],
            'username' => $user['username'],
            'email' => $user['email'],
            'userType' => $user['userType']
        );; // Trả về user_id nếu token hợp lệ
    } catch (Exception $e) {
        
        return null; // Trả về -1 nếu token không hợp lệ
    }
   
}

?>