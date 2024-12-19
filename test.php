<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="sellerstyles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                        <a class="nav-link" href="seller.html">Seller</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="menu.html">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.html">Cart</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--content-->
    <div class="main">
        <!--left-->
        <div class="left">
            <h4>you have</h4> <br> <h1>0</h1> <br> <h5>favourites</h5><br>
            <h4>you have</h4> <br> <h1>0</h1> <br> <h5>favourites</h5><br>
            <h4>you have</h4> <br> <h1>0</h1> <br> <h5>favourites</h5><br>
            <h4>you have</h4> <br> <h1>0</h1> <br> <h5>favourites</h5><br>
            <h4>you have</h4> <br> <h1>0</h1> <br> <h5>favourites</h5><br>
            <h4>you have</h4> <br> <h1>0</h1> <br> <h5>favourites</h5><br>
            <h4>you have</h4> <br> <h1>0</h1> <br> <h5>favourites</h5><br>
            <h4>you have</h4> <br> <h1>0</h1> <br> <h5>favourites</h5><br>
        </div>
        <!--right-->
        <div class="right">
            <button id="openModal" class="btn btn-primary">Be a seller today</button>
            <!-- Modal Structure -->
            <div id="sellerModal" class="modal">
                <div class="modal-content">
                    <span id="closeModal" class="close">&times;</span>
                    <h2>Fill out the form to Be a Seller</h2>
                    <form method="POST" action="add_seller.php">
                        <div class="mb-3">
                            <input type="text" name="item" class="form-control" id="itemname" placeholder="Enter item_name; you can use spacing" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="item" class="form-control" id="price" placeholder="price; in int" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="item" class="form-control" id="category" placeholder="catagory; burger; pizza" required>
                        </div>
                        <div class="modal-buttons">
                            <button type="submit" class="btn btn-primary">Confirm</button>
                            <button type="button" id="cancelModal" class="btn btn-secondary">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="rightbot">
                <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'food');
            
                // Check the connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
            
                // Query to join user and seller tables
                $query = "SELECT users.username, sellers.item_name, sellers.price 
                          FROM users 
                          INNER JOIN sellers ON users.id = sellers.user_id";
            
                $result = $conn->query($query);
            
                // Check if the query returned results
                if ($result) {
                    if ($result->num_rows > 0) {
                        // Loop through the results and display each record
                        while ($row = $result->fetch_assoc()) {
                            echo "<div class='seller-item'>";
                            echo "<div class='details'>";
                            echo "<h4>" . htmlspecialchars($row['username']) . "</h4>";
                            echo "<p>" . htmlspecialchars($row['item_name']) . "</p>";
                            echo "</div>";
                            echo "<div class='price'>₹" . number_format($row['price'], 2) . "</div>";
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
    <script>
        // Get modal elements
        const modal = document.getElementById('sellerModal');
        const openModal = document.getElementById('openModal');
        const closeModal = document.getElementById('closeModal');
        const cancelModal = document.getElementById('cancelModal');

        // Open modal
        openModal.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        // Close modal
        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        cancelModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        // Close modal when clicking outside the content
        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
