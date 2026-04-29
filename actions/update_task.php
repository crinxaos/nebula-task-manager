<?php
session_start();
require_once("../config/db.php");

header('Content-Type: application/json');

// Method check
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
    exit();
}

// Auth check
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit();
}

// Safe input handling
$task_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$user_id = $_SESSION['user_id'];

// Validation
if ($task_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid task ID"
    ]);
    exit();
}

if (empty($title)) {
    echo json_encode([
        "status" => "error",
        "message" => "Title is required"
    ]);
    exit();
}

if (strlen($title) > 255) {
    echo json_encode([
        "status" => "error",
        "message" => "Title too long"
    ]);
    exit();
}

// Prepare statement
$stmt = $conn->prepare("
    UPDATE tasks 
    SET title = ?, description = ?
    WHERE id = ? AND user_id = ?
");

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Prepare failed"
    ]);
    exit();
}

// Bind params
$stmt->bind_param("ssii", $title, $description, $task_id, $user_id);

// Execute
if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "task" => [
            "id" => $task_id,
            "title" => htmlspecialchars($title),
            "description" => htmlspecialchars($description)
        ]
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Database error"
    ]);
}

// Cleanup
$stmt->close();
$conn->close();