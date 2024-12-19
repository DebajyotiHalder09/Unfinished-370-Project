<?php
session_start();
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'food');

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="homestyles.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    <body>
        <!--nav bar-->
        <nav class="navbar navbar-expand-sm sticky-top" style="background-color:#312c29">
            <div class="container-xxl">
                <a href="home.html" class="navbar-brand me-auto">
                    <span class="fw-bold" style="color:white">
                        Lunchkorbo
                    </span>
                </a>
                <nav class="navbar">
                    <div class="container-fluid">
                        <form class="d-flex position-relative" role="search" style="width: 100%; max-width: 300px;">
                            <input 
                                class="form-control rounded-pill ps-4 pe-5" 
                                type="search" 
                                placeholder="Search food or chefs" 
                                aria-label="Search" 
                                style="padding-right: 40px;">
                            <button 
                                class="btn position-absolute top-50 translate-middle-y" 
                                type="submit" 
                                style="right: 15px; background: none; border: none;">
                                <i class="bi bi-search" style="font-size: 1rem; color: #28a745;"></i>
                            </button>
                        </form>
                    </div>
                </nav>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false"
                aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end align -center"
                id="main-nav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="seller.php">SELLER</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="menu.html">MENU</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="cart.html">CART</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
        <!--content-->
        <div class="main">
            <!--left-->
            <div class="left">
            <?php
            // Ensure session is started
            // Make sure $_SESSION['user_id'] is set when the user logs in

            if (isset($_SESSION['user_id'])) {
                // Get the logged-in user's ID
                $user_id = $_SESSION['user_id'];
                
                // Query to fetch categories specific to the logged-in user
                $query = "SELECT category_name 
                        FROM fav_cat 
                        WHERE user_cat_id = ?";
                
                // Use prepared statement to prevent SQL injection
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result) {
                    if ($result->num_rows > 0) {
                        // Begin the list group
                        echo '<div class="list-group">';
                        
                        // Loop through the results and create a button for each category
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="list-group-item d-flex justify-content-between">';
                            echo htmlspecialchars($row['category_name']);  // Prevent XSS
                            echo '<form action="delete_fav.php" method="POST" style="display:inline;">';
                            echo '<input type="hidden" name="category_id" value="' . $_SESSION['user_id'] . '">';
                            echo '<input type="hidden" name="category_name" value="' . htmlspecialchars($row['category_name']) . '">';  // Pass category_name
                            echo '<input type="hidden" name="scroll_position" id="scroll-position" value="">';  // Hidden input to store scroll position
                            echo '<button type="submit" class="btn btn-danger btn-sm">Delete</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                        
                        echo '</div>'; // End the list group
                    } else {
                        echo '<h1>You have no favorite categories.</h1>';
                    }
                } else {
                    echo 'Query failed: ' . $conn->error;
                }
            } else {
                // Handle case where user is not logged in
                echo '<h1>Please log in to view your favorite categories.</h1>';
            }
            ?>
            </div>

            <!--right-->
            <div class="right">
                <div class="card-container">
                    <?php
                    $query = "select username, item_name, price, category, img_path from
                    users inner join sellers on users.id=sellers.user_id;";
                    $result = $conn->query($query); 
                    if ($result) {
                        if ($result->num_rows > 0) {
                            // Loop through the results and display each record
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='card' style='width: 30rem;'>";
                                // Placeholder for item image (you can replace it with the actual image path if available)
                                echo "<img src='" . str_replace('./', '/', $row['img_path']) . "' alt='" . htmlspecialchars($row['item_name']) . "'>";
                                echo "<div class='card-body'>";
                                // Display seller's username
                                echo "<p class='card-title'>" . htmlspecialchars($row['username']) ." is selling". "</p>";
                                // Display item name
                                echo "<h5 class='card-text'>" . htmlspecialchars($row['item_name']) . "</h5>";
                                // Display price formatted in Tk
                                echo "<p class='card-text'>Price: " . number_format($row['price'], 2) . " Tk</p>";
                                echo "<a href='#' class='btn btn-primary'>Order Now</a>";
                                echo '<form action="add_to_fav.php" method="POST">';
                                // Hidden input to pass the category name
                                echo '<input type="hidden" name="category_name" value="' . htmlspecialchars($row['category']) . '">';
                                echo '<input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">';
                                echo '<button type="submit" class="btn btn-secondary">Add to fav</button>';
                                echo '</form>';
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No sellers found</p>";
                        }
                    } else {
                        echo "<p>Error: " . $conn->error . "</p>";
                    }
                    
                    // Close the connection
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>      
        </body>
    </html>