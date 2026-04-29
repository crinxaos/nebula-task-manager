<?php
require_once("includes/auth.php");
require_once("config/db.php");

$id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$tasks = $stmt->get_result();
?>

<h2>Welcome <?php echo $_SESSION['user_name']; ?></h2>

<form id="taskForm">
    <input type="text" name="title" placeholder="Task title" required>
    <textarea name="description"></textarea>
    <button>Add Task</button>
</form>

<ul>
    <?php while($t=$tasks->fetch_assoc()): ?>
    <li>
        <?php echo $t['title']; ?>
        <a href="actions/delete_task.php?id=<?php echo $t['id'];?>">Delete</a>
    </li>
    <?php endwhile;?>
</ul>

<a href="logout.php">Logout</a>

<script>
document.getElementById("taskForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("actions/create_task.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {

                const task = data.task;

                const li = document.createElement("li");

                li.innerHTML = `
                <strong>${task.title}</strong>
                (<span style="color: orange;">⏳ Pending</span>)

                <button onclick="deleteTask(${task.id}, this)">Delete</button>
            `;

                document.getElementById("taskList").appendChild(li);

                this.reset();

                showToast("Task created ✔");

            } else {
                showToast(data.message, "error");
            }
        })
        .catch(() => showToast("Server error", "error"));
});
</script>

<script>
function deleteTask(id, btn) {

    if (!confirm("Delete task?")) return;

    fetch("actions/delete_task.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                id
            })
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {

                btn.parentElement.remove();

                showToast("Task deleted 🗑");

            } else {
                showToast(data.message, "error");
            }

        })
        .catch(() => showToast("Server error", "error"));
}
</script>