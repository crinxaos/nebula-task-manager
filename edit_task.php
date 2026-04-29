<?php
require_once("includes/auth.php");
require_once("config/db.php");

if (!isset($_GET['id'])) {
    echo "Task not found";
    exit();
}

$task_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Get task securely
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
if (!$stmt) {
    echo "Database error";
    exit();
}

$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    echo "Unauthorized";
    exit();
}

$task = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>

<body>

    <h2>Edit Task</h2>

    <form id="editForm">
        <input type="hidden" name="id" value="<?php echo $task['id']; ?>">

        <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
        <br><br>

        <textarea name="description"><?php echo htmlspecialchars($task['description']); ?></textarea>
        <br><br>

        <button type="submit">Update Task</button>
    </form>

    <br>
    <a href="/nebula-task-manager/dashboard.php">Back</a>

    <!-- Global JS -->
    <script src="/nebula-task-manager/js/script.js"></script>

    <!-- AJAX UPDATE -->
    <script>
    document.getElementById("editForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('/nebula-task-manager/actions/update_task.php', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error("Network error");
                return res.json();
            })
            .then(data => {

                if (data.status === "success") {
                    showToast("Task updated ✔");

                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = "/nebula-task-manager/dashboard.php";
                    }, 1000);

                } else {
                    showToast(data.message, "error");
                }

            })
            .catch(err => {
                console.error(err);
                showToast("Server error", "error");
            });
    });
    </script>

</body>

</html>