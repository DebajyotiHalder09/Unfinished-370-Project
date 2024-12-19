<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'food');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in.";
    exit();
}

// Process form submission to become a seller
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["item_name"]) && isset($_POST["price"]) && isset($_POST["category"])) {
    $user_id = $_SESSION['user_id']; // Get the logged-in user's user_id from session
    $item_name = trim($_POST['item_name']);
    $price = trim($_POST['price']);
    $category = trim($_POST['category']);

    // Make sure the inputs are valid before inserting into the database
    if (empty($item_name) || empty($price) || empty($category)) {
        echo "All fields are required.";
        exit();
    }

    // Check if the user already exists as a seller
    $check_seller = $conn->prepare("SELECT * FROM sellers WHERE user_id = ?");
    $check_seller->bind_param('i', $user_id);
    $check_seller->execute();
    $result = $check_seller->get_result();

    if ($result->num_rows > 0) {
        echo "You are already a seller!";
        exit();
    } else {
        // Insert the data into the sellers table
        $query = "INSERT INTO sellers (user_id, item_name, price, category) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('issd', $user_id, $item_name, $category, $price);

        if ($stmt->execute()) {
            echo "Successfully became a seller!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>