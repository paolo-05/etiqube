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

// Check if the database connection file exists
if (!file_exists("db_connection.php")) {
    die("Database connection file not found.");
}

include_once 'db_connection.php';
$n_colonne = getNumeroColonne();
$sportelli = getConfiguration();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Riepilogo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
    <style>
    .table-container {
        display: flex;
    }

    .table-container table {
        margin-right: 20px;
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

        <h2>Riepilogo configurazione Locker</h2>
        <div class="table-container">
            <?php $counter = 0;

            for($i = 0; $i < $n_colonne; $i++) {
                echo "<table>";
                while(true){
                    if($sportelli[$counter]['n_scheda'] != ($i+1)){
                        break;
                    }
                    $dimensione = $sportelli[$counter]['dimensione'];
                    $dimensione_int = intval(substr($dimensione, 0, -1));
                    $height = $dimensione_int * 30;

                    if($sportelli[$counter]['n_sportello'] == $sportelli[$counter + 1]['n_sportello']){
                        echo "<tr><td><svg style='border: solid #000' width='100' height='{$height}'><rect width='100' height='{$height}' fill='#fff' /></svg></td></tr>";
                    } else {
                        echo "<tr><td><svg width='100' height='{$height}'><rect width='100' height='{$height}' fill='#000' /></svg></td></tr>";
                    }
                    $counter+=1;
                }
                echo "</table>";
            }
            ?>
        </div>
        <br><br>
        <div class="row">
            <div class="col">

                <form method="GET" action="/homepage.php">
                    <svg width='25' height='25' style="border: solid #000"><rect width='25' height='25' fill='#fff' /></svg></td>
                    Blocco bianco: Vano tecnico
                    <br><br>
                    <button type="submit" class="btn btn-success">Va bene questa configurazione</button>
                </form>

            </div>
            <div class="col">
            <svg width='25' height='25' style="border: solid #000"><rect width='25' height='25' fill='#000' /></svg></td>
            Blocco Nero: Sportello normale
            <br><br>
            <button type='button' id="deleteConfiguration" class='btn btn-danger'>Ricomincia la configurazione</button>
            </div>
        </div>
    </div>
<script>
const deleteConfigurationButton =document.getElementById('deleteConfiguration');
deleteConfigurationButton.addEventListener('click', function(event) {
    var confirmation = confirm("Sicuro di voler cancellare la configurazione? Questa operazione non può essere ripristinata.");

    if (confirmation) {
        var deleteUrl = 'http://127.0.0.1:8080/pages/db_connection.php?action=deleteCofiguration';

        var xhr = new XMLHttpRequest();
        xhr.open("POST", deleteUrl, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Configurazione cancellata con successo!");

                confirmation = confirm("Ricominciare la configurazione?");
                if(confirmation) {
                    window.location = "configura-colonna.php";
                }else{
                    window.location = "/homepage.php";
                }
           }
        };
        xhr.send();
    }
});
</script>
</body>
</html>
