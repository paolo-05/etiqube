<!-- homepage.php -->
<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: /index.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="google" content="notranslate">
    <title>Homepage</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/page.css">
</head>
<body>
    <div class="container">
        <div class="logout-btn">
            <button type="button" class="btn btn-danger" id="logout">Logout</button>
        </div>
        <h2>Benvenuto <?php echo $_SESSION['username'] ?>!</h2>
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
                        <a href="/pages/configura-colonna.php" class="btn btn-primary">Vai a Configura Colonna</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <div class="card-header">
                    Test Sensori Sportelli
                    </div>
                    <div class="card-body">
                        <img src="https://hatrabbits.com/wp-content/uploads/2018/10/risky-assumptions.jpg" alt="Image 2" class="img-fluid">
                    </div>
                    <div class="card-footer">
                        <a href="/pages/test-sensori.php" class="btn btn-primary">Vai a Test Sensori Sportelli</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../scripts/logout.js"></script>
</body>
</html>
