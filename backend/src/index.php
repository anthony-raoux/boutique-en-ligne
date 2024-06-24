<!-- backend/src/index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="../frontend/css/styles.css">
</head>
<body>
    <?php
    // Example: Check if user is logged in (you need to implement your own login logic)
    $loggedIn = isset($_SESSION['user_id']); // Adjust according to your session management
    
    // Set current page for navbar active class
    $currentPage = 'home';
    // Include the navbar from backend/src/
    include 'navbar.php';
    ?>

    <div class="content">
        <h1>Welcome to the Home Page</h1>
        <p>This is your homepage content.</p>
    </div>

    <script src="../frontend/js/script.js"></script>
</body>
</html>
