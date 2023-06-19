<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

if (!file_exists("db_connection.php")) {
    die("Database connection file not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Clear the session and redirect to the login page
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include_once 'db_connection.php';
    $max_sportelli = getNumeroSportelli() - 1;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Controllo stato sensori</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../style.css">
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
    <script>
        // Function to update the console output
        function updateConsoleOutput(output) {
            var consoleOutput = document.getElementById('console-output');
            consoleOutput.innerText += output + '\n';
            consoleOutput.lastElementChild.scrollIntoView();
        }

        // Function to execute the Python script
        function executeScript() {
            var nSportelloInput = document.getElementById('n-sportello');
            var nSportello = nSportelloInput.value;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        updateConsoleOutput(xhr.responseText);
                    }
                }
            };

            xhr.open('POST', 'execute_script.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send('n_sportello=' + encodeURIComponent(nSportello));
        }

        // Execute the script every second
        setInterval(executeScript, 1000);
    </script>
</head>
<body>
    <div class="container">
        <div class="logout-btn">
            <form method="POST">
                <button type="submit" name="logout" class="btn btn-primary">Logout</button>
            </form>
        </div>

        <h2>Controllo stato sensori</h2>

        <div class="console">
            <pre id="console-output"></pre>
        </div>

        <div class="mb-3">
            <label for="n-sportello" class="form-label">Numero Sportello:</label>
            <input type="number" id="n-sportello" class="form-control" min="1" max="<?php echo "$max_sportelli" ?>" value="1" required>
        </div>

        <button type="button" id="execute-btn" name="execute" class="btn btn-primary" onclick="executeScript()">Avvia il controllo dello stato dello sportello</button>
    </div>
</body>
</html>
