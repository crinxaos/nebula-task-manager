<?php
session_start();
require_once("../config/db.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit();
}

$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$user_id = $_SESSION['user_id'];

if (empty($title)) {
    echo json_encode(["status" => "error", "message" => "Title required"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $title, $description);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "task" => [
            "id" => $stmt->insert_id,
            "title" => htmlspecialchars($title),
            "status" => "pending"
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "DB error"]);
}