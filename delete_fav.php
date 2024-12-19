<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'food');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure that the user is logged in
if (isset($_SESSION['user_id']) && isset($_POST['category_id']) && isset($_POST['category_name'])) {
    $user_id = $_SESSION['user_id'];          // Logged-in user ID
    $category_name = $_POST['category_name'];  // Category name to delete

    // Prepare the delete query
    $delete_query = "DELETE FROM fav_cat WHERE user_cat_id = ? AND category_name = ?";

    // Prepare and bind the statement
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("is", $user_id, $category_name);  // Bind user_id and category_name

    if ($stmt->execute()) {
        // Redirect to home.php after successful deletion
        header("Location: home.php");
        exit();
    } else {
        // Handle failure (optional)
        echo "Error deleting category: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
