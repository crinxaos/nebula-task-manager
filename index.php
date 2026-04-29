<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <p>This is a test from Ubuntu</p>
    <p>This is a test from Windows</p>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <h2>Login</h2>

    <form id="loginForm">
        <input type="text" name="identifier" placeholder="Email or Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button>Login</button>
    </form>

    <p><a href="register.php">Register</a></p>

    <script src="js/script.js"></script>

    <script>
    document.getElementById("loginForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch("actions/login.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    showToast("Login success ✔");
                    setTimeout(() => window.location = "dashboard.php", 800);
                } else {
                    showToast(data.message, "error");
                }
            });
    });
    </script>

</body>

</html>