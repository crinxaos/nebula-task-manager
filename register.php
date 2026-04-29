<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
</head>

<body>

    <h2>Register</h2>

    <?php if(isset($_GET['error'])): ?>
    <p style="color:red;"><?php echo $_GET['error']; ?></p>
    <?php endif; ?>

    <form action="actions/register.php" method="POST">
        <input type="text" name="name" required><br><br>
        <input type="email" name="email" required><br><br>
        <input type="password" name="password" required><br><br>
        <button>Register</button>
    </form>

    <a href="index.php">Back to login</a>

</body>

</html>