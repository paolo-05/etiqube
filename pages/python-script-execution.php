<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Clear the session and redirect to the login page
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute'])) {
    // Execute the Python script and capture the output
    $output = shell_exec("python3 main.py");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Python Script Output</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./../style.css">

    <style>
        .console {
        background-color: #000;
        color: #fff;
        padding: 10px;
        margin-bottom: 20px;
        max-height: 200px;
        overflow-y: auto;
        border-radius: 16px;
        }
        .console pre {
            margin: 0;
        }
    </style>

</head>
<body>
    <div class="container">
        <div class="logout-btn">
            <form method="POST">
                <button type="submit" name="logout" class="btn btn-primary">Logout</button>
            </form>
        </div>

        <h2>Python Script Output</h2>

        <div class="console">
            <pre>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute'])) {
                    // Display the output
                    echo $output;
                }
                ?>
            </pre>
        </div>

        <form method="POST">
            <button type="submit" name="execute" class="btn btn-primary">Execute Python Script</button>
        </form>
        <br>
        <div class="back-btn">
            <a href="/homepage.php" class="btn btn-primary">Back to Homepage</a>
        </div>
    </div>
</body>
</html>
