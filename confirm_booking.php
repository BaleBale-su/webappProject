<?php
session_start();
$conn = new mysqli("localhost", "root", "", "webapp");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success = false;
$data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $room_id    = $_POST['room_id'];
    $checkin    = $_POST['checkin'];
    $checkout   = $_POST['checkout'];
    $guests     = $_POST['guests'];
    $status     = "Pending";

    $stmt = $conn->prepare("INSERT INTO bookings (first_name, last_name, email, phone_number, room_id, check_in, check_out, number_of_guests, status)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissis", $first_name, $last_name, $email, $phone, $room_id, $checkin, $checkout, $guests, $status);

    if ($stmt->execute()) {
        $success = true;
        $data = compact('first_name', 'last_name', 'email', 'phone', 'room_id', 'checkin', 'checkout', 'guests');
    } else {
        $error = $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #1A2456;
            padding: 40px;
            color: #333;
            text-align: center;
        }

        .container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }

        h2 {
            color: #1A2456;
        }

        .info {
            text-align: left;
            margin-top: 20px;
        }

        .info p {
            margin: 5px 0;
        }

        .qr-code {
            margin-top: 30px;
        }

        .notice {
            margin-top: 30px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <h2>Booking Confirmed!</h2>
            <div class="info">
                <p><strong>Name:</strong> <?= htmlspecialchars($data['first_name']) ?> <?= htmlspecialchars($data['last_name']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($data['phone']) ?></p>
                <p><strong>Room ID:</strong> <?= htmlspecialchars($data['room_id']) ?></p>
                <p><strong>Check-in:</strong> <?= htmlspecialchars($data['checkin']) ?></p>
                <p><strong>Check-out:</strong> <?= htmlspecialchars($data['checkout']) ?></p>
                <p><strong>Number of Guests:</strong> <?= htmlspecialchars($data['guests']) ?></p>
            </div>

            <div class="qr-code">
                <h3>Scan to Pay</h3>
                <img src="Qrcode.png" alt="QR Code for Payment" width="200">
                <p><strong>PromptPay ID:</strong> 011234567890</p>
            </div>

            <div class="notice">
                <p>📸 Please take a screenshot or photo of this page as confirmation.</p>
            </div>
        <?php else: ?>
            <h2>❌ Booking Failed</h2>
            <p><?= htmlspecialchars($error ?? 'Something went wrong.') ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
