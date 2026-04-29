<?php
session_start();
require_once("../config/db.php");

header('Content-Type: application/json');

// Auth check
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit();
}

// Method check
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
    exit();
}

// Validate input
$task_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$user_id = $_SESSION['user_id'];

if ($task_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid task ID"
    ]);
    exit();
}

// Get current status
$stmt = $conn->prepare("SELECT status FROM tasks WHERE id = ? AND user_id = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed"]);
    exit();
}

$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo json_encode([
        "status" => "error",
        "message" => "Task not found"
    ]);
    exit();
}

$task = $result->fetch_assoc();

// Toggle status
$new_status = ($task['status'] === 'pending') ? 'completed' : 'pending';

// Update
$stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed"]);
    exit();
}

$stmt->bind_param("sii", $new_status, $task_id, $user_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "new_status" => $new_status
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database error"
    ]);
}

$stmt->close();
$conn->close();