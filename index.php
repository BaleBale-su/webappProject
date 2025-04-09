<?php
session_start();

// Handle AJAX login request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax'])) {
    $host = 'localhost';
    $dbname = 'webapp';
    $username = 'root';
    $password_db = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_db);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            echo json_encode(['success' => true, 'email' => $user['email']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax']) && $_POST['ajax'] == 'logout') {
    session_unset();      // เคลียร์ตัวแปรใน session
    session_destroy();    // ทำลาย session
    echo json_encode(['success' => true]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elysian - Exclusive Private Hotel</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <div class="logo">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="#1A2456" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M2 17L12 22L22 17" stroke="#1A2456" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M2 12L12 17L22 12" stroke="#1A2456" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Elysian
                </div>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Suites</a></li>
                    <li><a href="#">Amenities</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                <div id="mainContent">
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <a href="logout.php"><button id="logoutBtn">Logout</button></a>
                    <?php else: ?>
                        <a href="login.php"><button id="loginBtn">Login</button></a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Private Luxury Retreat</h1>
            <p>Experience unparalleled exclusivity and personalized service in our boutique private hotel</p>

            <div class="search-container">
                <form class="search-form" onsubmit="return checkAvailability(event)">
                    <div class="form-group">
                        <label for="check-in">Check-in</label>
                        <input type="date" id="check-in" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="check-out">Check-out</label>
                        <input type="date" id="check-out" class="form-control" required>
                    </div>
                    <button type="submit" class="search-btn">
                        Check Availability
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="featured">
        <div class="container">
            <h2 class="section-title">Exclusive Suites</h2>

            <div class="suites-container">
                <div class="suite-card">
                    <img src="Hotel2.jpg" alt="Presidential Suite" class="suite-img">
                    <div class="suite-info">
                        <h3 class="suite-name">Presidential Suite</h3>
                        <p class="suite-description">Our signature suite featuring panoramic views, private terrace, and
                            personal butler service for the ultimate luxury experience.</p>
                        <div class="suite-details">
                            <div class="suite-detail">
                                <span class="suite-detail-value">120m²</span>
                                <span class="suite-detail-label">Area</span>
                            </div>
                            <div class="suite-detail">
                                <span class="suite-detail-value">2</span>
                                <span class="suite-detail-label">Bedrooms</span>
                            </div>
                            <div class="suite-detail">
                                <span class="suite-detail-value">4</span>
                                <span class="suite-detail-label">Guests</span>
                            </div>
                        </div>
                        <div class="suite-price">
                            $300 <span>per night</span>
                        </div>
                        <form method="GET" action="booking.php">
                            <input type="hidden" name="room" value="Presidential Suite">
                            <input type="hidden" name="price" value="300">
                            <button type="submit">Request Booking</button>
                        </form>
                    </div>
                </div>

                <div class="suite-card">
                    <img src="Hotel1.jfif" alt="Executive Suite" class="suite-img">
                    <div class="suite-info">
                        <h3 class="suite-name">Executive Suite</h3>
                        <p class="suite-description">Elegant and spacious accommodations with separate living area,
                            premium amenities, and exclusive access to the Executive Lounge.</p>
                        <div class="suite-details">
                            <div class="suite-detail">
                                <span class="suite-detail-value">85m²</span>
                                <span class="suite-detail-label">Area</span>
                            </div>
                            <div class="suite-detail">
                                <span class="suite-detail-value">1</span>
                                <span class="suite-detail-label">Bedroom</span>
                            </div>
                            <div class="suite-detail">
                                <span class="suite-detail-value">2</span>
                                <span class="suite-detail-label">Guests</span>
                            </div>
                        </div>
                        <div class="suite-price">
                            $180 <span>per night</span>
                        </div>
                        <form method="GET" action="booking.php">
                            <input type="hidden" name="room" value="Executive Suite">
                            <input type="hidden" name="price" value="180">
                            <button type="submit">Request Booking</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>A Private Sanctuary of Luxury and Comfort</h2>
                    <p>At Elysian, we redefine the meaning of exclusivity. Our private hotel operates on an
                        invitation-only basis, ensuring complete privacy and personalized attention for our
                        distinguished guests.</p>
                    <p>Each of our meticulously designed suites offers a perfect blend of timeless elegance and modern
                        sophistication, creating an atmosphere of refined luxury that caters to the most discerning
                        tastes.</p>
                    <p>From our discreet concierge service to our exclusive amenities, every aspect of your stay is
                        crafted with the utmost attention to detail, promising an unforgettable experience that exceeds
                        all expectations.</p>
                </div>
                <img src="Middle2.jfif" alt="Hotel Interior" class="about-image">
            </div>
        </div>
    </section>

    <section class="contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            <div class="contact-content">
                <div class="contact-info">
                    <h3>We're Here to Assist You</h3>
                    <div class="contact-details">
                        <div class="contact-item">
                            <svg class="contact-item-icon" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3 5C3 3.89543 3.89543 3 5 3H8.27924C8.70967 3 9.09181 3.27543 9.22792 3.68377L10.7257 8.17721C10.8831 8.64932 10.6694 9.16531 10.2243 9.38787L7.96701 10.5165C9.06925 12.9612 11.0388 14.9308 13.4835 16.033L14.6121 13.7757C14.8347 13.3306 15.3507 13.1169 15.8228 13.2743L20.3162 14.7721C20.7246 14.9082 21 15.2903 21 15.7208V19C21 20.1046 20.1046 21 19 21H18C9.71573 21 3 14.2843 3 6V5Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="contact-item-text">+66 61-716-xxxx</span>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-item-icon" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5963 12 13.5963C12.3951 13.5963 12.7813 13.4793 13.11 13.26L21 8M5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <div class="contact-item-text">Homnual_T@silpakorn.edu</div>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-item-icon" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5963 12 13.5963C12.3951 13.5963 12.7813 13.4793 13.11 13.26L21 8M5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7V17C3 18.1046 3.89543 19 5 19Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <div class="contact-item-text">Homchanpen_P@silpakorn.edu</div>
                        </div>
                        <div class="contact-item">
                            <svg class="contact-item-icon" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17.6569 16.6569C16.7202 17.5935 14.7616 19.5521 13.4138 20.8999C12.6327 21.681 11.3677 21.6814 10.5866 20.9003C9.26234 19.576 7.34159 17.6553 6.34315 16.6569C3.21895 13.5327 3.21895 8.46734 6.34315 5.34315C9.46734 2.21895 14.5327 2.21895 17.6569 5.34315C20.781 8.46734 20.781 13.5327 17.6569 16.6569Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M15 11C15 12.6569 13.6569 14 12 14C10.3431 14 9 12.6569 9 11C9 9.34315 10.3431 8 12 8C13.6569 8 15 9.34315 15 11Z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span class="contact-item-text">Pattaya Chonburi</span>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <form onsubmit="return submitContactForm(event)">
                        <div class="form-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <h3>Elysian</h3>
                    <p>A private sanctuary where luxury meets exclusivity. Our private hotel offers an unparalleled
                        experience for those who value privacy, personalized service, and refined elegance.</p>
                </div>
                <div class="footer-column">
                    <h3>Hotel</h3>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Suites</a></li>
                        <li><a href="#">Amenities</a></li>
                        <li><a href="#">Private Events</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Legal</h3>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                © 2025 By students from Silpakorn in CS Major.
            </div>
        </div>
    </footer>
</body>

</html>