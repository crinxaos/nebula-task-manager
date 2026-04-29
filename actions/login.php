<?php
session_start();
require_once("../config/db.php");

header('Content-Type: application/json');

$id = $_POST['identifier'];
$pass = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email=? OR name=?");
$stmt->bind_param("ss",$id,$id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows === 1){
    $user = $res->fetch_assoc();

    if(password_verify($pass,$user['password'])){
        $_SESSION['user_id']=$user['id'];
        $_SESSION['user_name']=$user['name'];
        echo json_encode(["status"=>"success"]);
        exit();
    }
}

echo json_encode(["status"=>"error","message"=>"Invalid credentials"]);