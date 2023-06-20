<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

if (!file_exists("./../api/db_connection.php")) {
    die("Database connection file not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include_once './../api/db_connection.php';
    $max_sportelli = getNumeroSportelli() - 1;
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="google" content="notranslate">
    <title>Controllo stato sensori</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../styles/page.css">
    <link rel="stylesheet" type="text/css" href="../styles/console.css">
</head>
<body>
    <div class="container">
        <div class="logout-btn">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-primary" id="home">Torna alla Homepage</button>
                <button type="button" class="btn btn-danger" id="logout">Logout</button>
            </div>
        </div>

        <h2>Controllo stato sensori</h2>

        <div class="console">
            <pre id="console-output"></pre>
        </div>

        <div class="mb-3">
            <label for="n-sportello" class="form-label">Numero Sportello:</label>
            <input type="number" class="form-control" id="n-sportello" min="1" max="<?php echo "$max_sportelli" ?>" value="1" required>
        </div>

        <button type="button" class="btn btn-primary" id="execute-btn">Avvia il controllo dello stato dello sportello</button>
    </div>
    <script src="../scripts/test-sensori.js"></script>
</body>
</html>
