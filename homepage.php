<!-- homepage.php -->
<!-- homepage.php -->
<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Clear the session and redirect to the login page
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="logout-btn">
            <form method="POST">
                <button type="submit" name="logout" class="btn btn-primary">Logout</button>
            </form>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Locker Configuration
                    </div>
                    <div class="card-body">
                        <img src="https://i.ytimg.com/vi/8fgCk1wdNek/maxresdefault.jpg" alt="Image 1" class="img-fluid">
                    </div>
                    <div class="card-footer">
                        <a href="/pages/configura-colonna.php" class="btn btn-primary">Go to Configura Colonna</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Python Scritp Execution
                    </div>
                    <div class="card-body">
                        <img src="https://logos-world.net/wp-content/uploads/2021/10/Python-Logo.png" alt="Image 2" class="img-fluid">
                    </div>
                    <div class="card-footer">
                        <a href="/pages/python-script-execution.php" class="btn btn-primary">Go to Python Script Execution</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
