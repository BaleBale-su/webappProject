<?php
$room = $_GET['room'] ?? 'Unknown';
$price = $_GET['price'] ?? 'N/A';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking: <?php echo htmlspecialchars($room); ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #1A2456;
            padding: 30px;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }

        h1, h3 {
            text-align: center;
            color: #333;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #218838;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
        }

        .qr-section {
            margin-top: 40px;
            text-align: center;
        }

        .qr-section img {
            width: 200px;
            margin-top: 10px;
        }

        .note {
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Details</h1>
        <p><strong>Room Type:</strong> <?php echo htmlspecialchars($room); ?></p>
        <p><strong>Price per Night:</strong> $<?php echo htmlspecialchars($price); ?></p>

        <form method="post" action="confirm_booking.php">
            <input type="hidden" name="room_id" value="1"> <!-- ปรับตามห้องจริงถ้ามี -->

            <label>First Name:</label>
            <input type="text" name="first_name" required>

            <label>Last Name:</label>
            <input type="text" name="last_name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Phone Number:</label>
            <input type="text" name="phone">

            <label>Check-in Date:</label>
            <input type="date" name="checkin" required>

            <label>Check-out Date:</label>
            <input type="date" name="checkout" required>

            <label>Number of Guests:</label>
            <input type="number" name="guests" min="1" required>

            <button type="submit">Confirm Booking</button>
        </form>

        <div class="qr-section">
            <hr>
            <h3>Scan to Pay</h3>
            <p>กรุณาชำระเงินผ่าน QR Code ด้านล่างนี้</p>

            <!-- ✅ แสดง QR Code แบบรูปภาพในเครื่อง -->
            <img src="Qrcode.png" alt="QR Code Payment">

            <!-- หรือถ้าคุณใช้ qr.php แบบ dynamic: -->
            <!-- <img src="qr.php?price=<?php echo urlencode($price); ?>" alt="QR Code"> -->

            <p><strong>PromptPay ID:</strong> 011234567890</p>
            <p><strong>Amount:</strong> $<?php echo htmlspecialchars($price); ?></p>

            <div class="note">
                <p>📷 กรุณาถ่ายภาพหน้าถัดไปไว้เป็นหลักฐาน</p>
            </div>
        </div>
    </div>
</body>
</html>
