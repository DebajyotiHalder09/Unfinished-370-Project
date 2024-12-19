<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "food");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["registerUsername"];
    $password = password_hash($_POST["registerPassword"], PASSWORD_BCRYPT);

    // Check if username already exists
    $result = $mysqli->query("SELECT id FROM users WHERE username='$username'");
    if ($result->num_rows > 0) {
        echo "Username already exists!";
    } else {
        // Insert new user
        $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            echo "Registration successful!";
            header("Location: index.html");
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $username = $_POST["loginUsername"];
    $password = $_POST["loginPassword"];

    // Authenticate user
    $result = $mysqli->query("SELECT * FROM users WHERE username='$username'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            header("Location: home.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}
?>
