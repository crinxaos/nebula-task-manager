<?php
session_start();
require_once("../config/db.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status"=>"error","message"=>"Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status"=>"error","message"=>"Invalid request"]);
    exit();
}

$task_id = intval($_POST['id'] ?? 0);
$user_id = $_SESSION['user_id'];

if ($task_id <= 0) {
    echo json_encode(["status"=>"error","message"=>"Invalid ID"]);
    exit();
}

$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>"DB error"]);
}