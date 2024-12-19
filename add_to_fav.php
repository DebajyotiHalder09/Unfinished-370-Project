<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'food');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $category_name = $_POST['category_name']; // Get category name from the form

    // Check if the category already exists for the user
    $check_query = "SELECT * FROM fav_cat WHERE user_cat_id = ? AND category_name = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("is", $user_id, $category_name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Category already exists in the favorites
        echo "This category is already added to your favorites!";
    } else {
        // Insert into fav_cat table if it doesn't already exist
        $insert_query = "INSERT INTO fav_cat (user_cat_id, category_name) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("is", $user_id, $category_name);

        if ($insert_stmt->execute()) {
            // Redirect to home.php after successful insertion
            header("Location: home.php");
            exit();
        } else {
            // Handle failure (optional)
            echo "Debug: Insertion failed - " . $insert_stmt->error . "<br>";
        }
    }
} else {
    // User is not logged in
    echo "Please log in to add a category to your favorites.";
}

$conn->close();
?>
