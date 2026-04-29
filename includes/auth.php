<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /nebula-task-manager/index.php");
    exit();
}
?>