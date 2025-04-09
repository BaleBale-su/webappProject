<?php
session_start();
$conn = new mysqli("localhost", "root", "", "webapp");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // ตรวจสอบว่า email ซ้ำหรือไม่
    $check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        $error = "❌ Email นี้มีผู้ใช้งานแล้ว!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $password);
        if ($stmt->execute()) {
            $_SESSION["user_id"] = $stmt->insert_id;
            $_SESSION["email"] = $email;
            $_SESSION["full_name"] = $full_name;
            header("Location: index.php");
            exit();
        } else {
            $error = "❌ ไม่สามารถสมัครสมาชิกได้!";
        }
        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Elysian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1A2456;
            color: white; 
        }

        header {
            background-color: #1A2456;
            color: white;
            padding: 10px 0;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            color: white;
        }

        header .logo svg {
            margin-right: 8px;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        .login-container {
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: white;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-container label {
            text-align: left;
            width: 100%;
            margin-bottom: 5px;
            font-size: 14px;
            color: white;
        }

        .login-container input[type="text"],
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .login-container input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .login-container input[type="submit"]:hover {
            background-color: #162138;
        }

        .login-container p {
            font-size: 14px;
            margin-top: 15px;
            color: white;
        }

        .login-container .error {
            margin-top: 15px;
            color: red;
            font-weight: bold;
        }

        footer {
            background-color: #1A2456;
            color: white;
            padding: 10px;
            text-align: center;
        }

        footer p {
            margin: 0;
            font-size: 14px;
        }
    </style>
</head>
<body>

<header>
    <div class="container">
        <nav>
            <div class="logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#1A2456" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 17L12 22L22 17" stroke="#1A2456" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 12L12 17L22 12" stroke="#1A2456" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Elysian
            </div>
        </nav>
    </div>
</header>

<main>
    <div class="login-container">
        <h2>Create an Account</h2>
        <form action="register.php" method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>

        <?php
        if (isset($error)) {
            echo "<div class='error'>$error</div>";
        }
        ?>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; 2025 Elysian. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
