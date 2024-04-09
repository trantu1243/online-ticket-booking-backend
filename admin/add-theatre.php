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
        $name = $_POST['theatreName'];
        $address = $_POST['theatreAddress'];
        $state = $_POST['state'];
        $username = $_POST['username'];
        $zipCode = $_POST['zipCode'];
        $password = $_POST['password'];
        $cfPassword = $_POST['confirmPassword'];

        $stmt = $conn->prepare("SELECT * FROM Users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $regex = '/^.{8,}$/';
        if (!$address || !$state || !$name || !$zipCode) {
            generateResponse(array(
                "message" => "error",
                "error" => "Invalid"
                ));
        } else if ($password != $cfPassword){
            generateResponse(array(
                "message" => "error",
                "error" => "Password not match"
                ));
        } else if (!preg_match($regex, $username)){
            generateResponse(array(
                "message" => "error",
                "error" => "Invalid username"
                ));
        } else if (!preg_match($regex, $password)){
            generateResponse(array(
                "message" => "error",
                "error" => "Invalid password"
                ));
        }
        else if ($result->num_rows > 0) {
            generateResponse(array(
                "message" => "error",
                "error" => "Username already exists"
                ));
        } else{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $userType = 1;
            $email = $name . "@gmail.com";
            $stmt = $conn->prepare("INSERT INTO Users (username, password, email, userType) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $username, $hashed_password, $email, $userType);
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $stmt2 = $conn->prepare("INSERT INTO Theaters (name, state, address, zipCode, userId) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param("ssssi", $name, $state, $address, $zipCode, $user_id);
                if ($stmt2->execute()){
                    generateResponse(array(
                        "message" => "Ok",
                    ));
                }

            } else {
                generateResponse(array(
                    "message" => "error",
                    "error" => "Failed"
                ));
            }
        }
    }
} else {
    generateResponse(array("message" => "Error"));
}