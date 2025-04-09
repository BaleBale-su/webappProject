<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webapp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {
    $login_input = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT user_id, email, password, full_name FROM users WHERE email = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("s", $login_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($password == $row["password"]) {
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["full_name"] = $row["full_name"];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "❌ รหัสผ่านไม่ถูกต้อง!";
        }
    } else {
        $error_message = "❌ ไม่พบผู้ใช้ในระบบ!";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Elysian</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1A2456;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            color: #fff;
        }

        header {
            padding: 20px 0;
            background-color: rgba(255,255,255,0.05);
            text-align: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: rgba(255,255,255,0.1);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 90%;
        }

        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: none;
            margin-bottom: 20px;
            background: #fff;
            color: #000;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .login-container a {
            color: #ffea00;
            text-decoration: none;
        }

        .error {
            color: #ff4d4d;
            margin-top: 10px;
            text-align: center;
            background: #fff;
            color: #b00020;
            padding: 10px;
            border-radius: 6px;
        }

        footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            background-color: rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2 17L12 22L22 17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2 12L12 17L22 12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            Elysian
        </div>
    </header>

    <main>
        <div class="login-container">
            <h2>Login to Your Account</h2>
            <form action="login.php" method="POST">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="Login">
            </form>
            <p>No Account? <a href="register.php">Registration</a></p>

            <?php if (isset($error_message)) : ?>
                <div class="error"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 Elysian. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
