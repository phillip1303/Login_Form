<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "bishopdemazenod"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error message
$error_message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from the form
    $username_input = mysqli_real_escape_string($conn, $_POST['username']);
    $password_input = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Query the database for the user with the entered username
    $sql = "SELECT * FROM users WHERE username = '$username_input' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        // User found, check if password matches
        $user = mysqli_fetch_assoc($result);
        // Use password_verify to check if the password is correct (if password is hashed)
        if (password_verify($password_input, $user['password'])) { 
            // Store user ID and username in session
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username']; 
            // Redirect to dashboard1.php after successful login
            header('Location: dashboard1.php'); 
            exit();
        } else {
            // Incorrect password
            $error_message = "Incorrect username or password!";
        }
    } else {
        // Username not found
        $error_message = "Incorrect username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bishop De Mazenod High School</title>
    <style>
        /* General Reset */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: rgba(0, 0, 128, 1); /* Navy blue background */
        }

        /* Video Background */
        .video-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -2;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Background Image Styling */
        .background-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('gggggg.jpg');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            z-index: -1;
        }

        /* Transparent Navy Overlay */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 128, 0.7); /* Navy blue with 70% transparency */
        }

        /* Header Styling */
        header {
            position: absolute;
            top: 0;
            width: 100%;
            background-color: rgba(0, 0, 128, 0.9); /* Navy blue, more opaque */
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-end;
            z-index: 2;
        }

        header nav a {
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
            margin-right: 30px; /* Increased spacing */
        }

        header nav a:hover {
            text-decoration: underline;
        }

        /* School Title Styling */
        .school-title {
            position: absolute;
            top: 100px;
            width: 100%;
            text-align: center;
            color: white;
            font-size: 2.8rem;
            z-index: 2;
        }

        /* Login Section Styling */
        .login-section {
            position: absolute;
            top: 180px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            background-color: rgba(0, 0, 0, 0.8); /* Blackish transparent frame */
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            z-index: 2;
        }

        /* Left Frame Styling */
        .user-frame {
            padding: 30px;
            text-align: center;
            color: white;
            border-right: 2px solid rgba(255, 255, 255, 0.2);
        }

        .user-frame h2 {
            margin: 0;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        .user-frame img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 20px 0;
        }

        .user-frame p {
            margin: 0;
            font-size: 0.8rem;
            font-weight: bold;
        }

        /* Right Frame Styling */
        .login-container {
            padding: 30px;
            width: 400px;
            color: white;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            appearance: none; /* Removes browser-specific styling */
        }

        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #007bff; /* Blue button */
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container a {
            color: #007bff; /* Blue links */
            text-decoration: none;
            font-size: 0.9rem;
            display: block;
            margin-top: 10px;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        /* Footer Styling */
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: rgba(0, 0, 128, 0.9); /* Navy blue */
            color: white;
            text-align: center;
            padding: 10px 20px;
            z-index: 2;
        }

        /* Error message styling */
        .error-message {
            color: red;
            font-size: 1rem;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <div class="video-container">
        <video autoplay muted loop>
            <source src="school-video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <div class="background-image"></div>

    <header>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </header>

    <div class="school-title">
        Bishop De Mazenod High School
    </div>

    <div class="login-section">
        <!-- Left Frame -->
        <div class="user-frame">
            <h2>Welcome User</h2>
            <img src="user1.jpg" alt="User Icon">
            <p>Bishop De Mazenod</p>
        </div>

        <!-- Right Frame -->
        <div class="login-container">
            <h2>Login</h2>
            <!-- Display error message if credentials are incorrect -->
            <?php if (isset($error_message)) { echo "<p class='error-message'>$error_message</p>"; } ?>
            
            <form action="login.php" method="POST">
                <input type="text" id="username" name="username" placeholder="Username" required>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <a href="#forgot-password">Forgot Password?</a>
            <a href="register.php">Register Here</a>
        </div>
    </div>

    <footer>
        &copy; <?php echo date("Y"); ?> Bishop De Mazenod High School. All rights reserved.
    </footer>
</body>
</html>
