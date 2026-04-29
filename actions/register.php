<?php
require_once("../config/db.php");

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];

if(!$name || !$email || !$password){
    header("Location: ../register.php?error=All fields required");
    exit();
}

$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s",$email);
$stmt->execute();

if($stmt->get_result()->num_rows > 0){
    header("Location: ../register.php?error=Email exists");
    exit();
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users(name,email,password) VALUES(?,?,?)");
$stmt->bind_param("sss",$name,$email,$hash);

if($stmt->execute()){
    header("Location: ../index.php");
} else {
    header("Location: ../register.php?error=DB error");
}