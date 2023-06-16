<!-- configura-colonna.php -->
<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    session_unset();
    session_destroy();
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

// Check if the database connection file exists
if (!file_exists("db_connection.php")) {
    die("Database connection file not found.");
}

// Se la pagina viene chiesta in POST ed è necessario passare alla prossima colonna
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['prossima-colonna'])) {
    $slot_rimanenti = 11;
    $n_sportello = 0;
    $n_scheda = $_POST['n_scheda'] + 1;
    $available = [false, true, false];
}
// inserimento vano tecnico
/* else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['inserimento-vano'])) {
    $slot_rimanenti = $_POST['slot_rimanenti'];
    $n_sportello = $_POST['n_sportello'];
    $dimensione = $_POST['dimensione'];
    $n_scheda = $_POST['n_scheda'];

    // rimozione 'x' dalla dimensione
    $dimension_int = intval(substr($dimensione, 0, -1));

    // decremento slot rimanenti in base alla dimensione precendente
    $slot_rimanenti -= $dimension_int;
    $n_serratura = $n_sportello;

    require_once "db_connection.php";
    insertSlot($n_sportello, $n_scheda, $n_serratura, $dimensione);

} */
// situazione nomrale
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot_rimanenti = $_POST['slot_rimanenti'];
    $n_sportello = $_POST['n_sportello'];
    $dimensione = $_POST['dimensione'];
    $n_scheda = $_POST['n_scheda'];

    // rimozione 'x' dalla dimensione
    $dimension_int = intval(substr($dimensione, 0, -1));

    // decremento slot rimanenti in base alla dimensione precendente
    $slot_rimanenti -= $dimension_int;
    $n_serratura = $n_sportello;

    require_once "db_connection.php";
    insertSlot($n_sportello, $n_scheda, $n_serratura, $dimensione);

    switch ($slot_rimanenti) {
        case 4:
            // possibile inserire uno slot da 1x o 2x
            $available = [true, true, false];
            break;
        case 3:
            // Caso critico: 3 slot rimanenti
            // Possibile inserire uno slot da 3x oppure uno da 1x
            $available = [true, false, true];
            break;
        case 2:
            // Reset dei dati della colonna passando alla successiva
            $available = [false, true, false];
            break;
        case 0:
            if ($_POST['n_scheda'] <= 10) {
                $available = [false, false, false];
            }
            break;
        default:
            $available = [true, true, true];
            if($slot_rimanenti == 9){
                /* $vano_tecnico_inseribile = true;*/
            }else{
                $vano_tecnico_inseribile = false;
            }
    }
    // incremento del numero degli sportelli usati
    $n_sportello++;

}
// Se la pagina viene chiesta in GET
else if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $slot_rimanenti = 11;
    $n_sportello = 0;
    $n_scheda = 1;
    $available = [false, true, false];

    $vano_tecnico_inseribile = false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Configura Colonna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="logout-btn">
            <form method="POST">
                <button type="submit" name="logout" class="btn btn-primary">Logout</button>
            </form>
        </div>

        <h2>Configura Colonna</h2>
        <form method="POST" action="configura-colonna.php">
            <input type="hidden" name="n_sportello" value="<?php echo $n_sportello ?>">
            <input type="hidden" name="n_scheda" value="<?php echo $n_scheda?>">
            <input type="hidden" name="slot_rimanenti" value="<?php echo "$slot_rimanenti" ?>">

            <div class="mb-3">
                <h5> Slot Rimanenti: <?php echo $slot_rimanenti ?>, Colonna numero: <?php echo $n_scheda ?></h5>
                <label for="dimensione" class="form-label">Dimensione Sportello:</label>
                <label for="dimensione"><small class="text-muted"> (partendo dall'alto, il primo slot deve avere dimensione 2x, il vano tecnico è inseribile solo dopo il primo slot)</small></label>
                <select class="form-select" name="dimensione" id="dimensione">
                <?php echo $available[0] ? "<option value='1x'>1x</option>" : ''?>
                    <?php echo $available[1] ? "<option value='2x'>2x</option>" : ''?>
                    <?php echo $available[2] ? "<option value='3x'>3x</option>" : ''?>
                </select>
            </div>
            <?php echo ($slot_rimanenti > 0) ? '<button type="submit" class="btn btn-primary">Prossimo Slot</button>' : '<button type="submit"disabled class="btn btn-primary">Prossimo Slot</button>'?>
        </form>
        <br>
        <div class="row">
            <div class="col">
                <form method="POST" action="configura-colonna.php">
                    <input type="hidden" name="n_sportello" value="<?php echo $n_sportello ?>">
                    <input type="hidden" name="n_scheda" value="<?php echo $n_scheda?>">
                    <input type="hidden" name="slot_rimanenti" value="<?php echo "$slot_rimanenti" ?>">
                    <input type="hidden" name="dimensione" value="2x">
                    <input type="hidden" name="inserimento-vano" value="true">
                    <?php echo $vano_tecnico_inseribile ? '<button type="submit" class="btn btn-primary">Inserisci Vano Tecnico</button>' : '<button type="submit" disabled class="btn btn-primary">Inserisci Vano Tecnico</button>'?>
                </form>

            </div>
            <div class="col">
                <form method="POST" action="configura-colonna.php">
                    <?php echo $slot_rimanenti == 0 ? "<input type='hidden' name='prossima-colonna' value='true'>" : '' ?>
                    <input type="hidden" name="n_scheda" value="<?php echo $n_scheda?>">
                    <?php echo $slot_rimanenti == 0 ? "<button type='submit' class='btn btn-primary'>Prossima Colonna</button>" : '' ?>
                </form>
            </div>
        </div>
        <br>
        <div class="row">
            <form method="POST" action="riepilogo.php">
                <?php echo $slot_rimanenti == 0 ? "<button type='submit' class='btn btn-primary'>Concludi la configurazione</button>" : '' ?>
            </form>
        </div>
    </div>
</body>
</html>
