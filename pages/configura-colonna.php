<!-- configura-colonna.php -->
<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['username'])) {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

// Verifica se il file di connessione al database esiste
if (!file_exists("./../api/db_connection.php")) {
    die("File di connessione al database non trovato.");
}

// Se la pagina viene richiesta con il metodo POST ed è necessario passare alla prossima colonna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prossima-colonna'])) {
    $slot_rimanenti = 11;
    $n_sportello = $_POST['n_sportello'];
    $n_scheda = $_POST['n_scheda'] + 1;
    $available = [false, true, false];
    $n_serratura = 1;

    $configuratore_attivo = true;
}
// Inserimento vano tecnico
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inserimento-vano'])) {
    $slot_rimanenti = $_POST['slot_rimanenti'];
    $n_sportello = $_POST['n_sportello'];
    $dimensione = $_POST['dimensione'];
    $n_scheda = $_POST['n_scheda'];
    $n_serratura = $_POST['n_serratura'];

    // Rimozione 'x' dalla dimensione
    $dimension_int = intval(substr($dimensione, 0, -1));

    // Decremento degli slot rimanenti in base alla dimensione precedente
    $slot_rimanenti -= $dimension_int;

    require_once "./../api/db_connection.php";
    insertSettings($n_scheda, $n_serratura);

    $available = [true, true, true];
    $vano_tecnico_inseribile = false;
    $_SESSION['vano-inserito'] = true;
    $n_serratura++;

    $configuratore_attivo = true;
}
// Situazione normale
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slot_rimanenti = $_POST['slot_rimanenti'];
    if($slot_rimanenti < 0){
        return;
    }
    $n_sportello = $_POST['n_sportello'];
    $dimensione = $_POST['dimensione'];
    $n_scheda = $_POST['n_scheda'];
    $n_serratura = $_POST['n_serratura'];

    // Rimozione 'x' dalla dimensione
    $dimension_int = intval(substr($dimensione, 0, -1));

    // Decremento degli slot rimanenti in base alla dimensione precedente
    $slot_rimanenti -= $dimension_int;

    require_once "./../api/db_connection.php";
    insertSlot($n_sportello, $n_scheda, $n_serratura, $dimensione);

    switch ($slot_rimanenti) {
        case 4:
            // È possibile inserire uno slot da 1x o 2x
            $available = [true, true, false];
            break;
        case 3:
            // Caso critico: 3 slot rimanenti
            // È possibile inserire uno slot da 3x oppure uno da 1x
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
            if ($slot_rimanenti == 9 && !$_SESSION['vano-inserito']) {
                $vano_tecnico_inseribile = true;
            } else {
                $vano_tecnico_inseribile = false;
            }
    }
    // Incremento del numero degli sportelli usati
    $n_sportello++;
    $n_serratura++;

    $configuratore_attivo = true;
}
// Se la pagina viene richiesta con il metodo GET
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $slot_rimanenti = 11;
    $n_sportello = $n_serratura = 1;
    $n_scheda = 1;
    $available = [false, true, false];

    $vano_tecnico_inseribile = false;
    $_SESSION['vano-inserito'] = false;

    $configuratore_attivo = true;
}

include_once './../api/db_connection.php';
$sportelli = getConfiguration();
$vano_tecnico = getSettings();

if($sportelli != null && $n_sportello == 1 ){
    $configuratore_attivo = false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="google" content="notranslate">
    <title>Configuratore Colonna</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/styles/page.css">
    <link rel="stylesheet" type="text/css" href="../styles/table.css">
</head>
<body>
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirma Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sicuro di effettuare il logout?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancella</button>
                <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
            </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="logout-btn">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-primary" id="home" disabled>Torna alla Homepage</button>
                <button type="button" class="btn btn-danger" id="logout">Logout</button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h2>Configuratore Colonna</h2>
                <form method="POST" action="configura-colonna.php">
                    <input type="hidden" name="n_sportello" value="<?= $n_sportello ?>" id="n_sportello">
                    <input type="hidden" name="slot_rimanenti" value="<?= $slot_rimanenti ?>">

                    <div class="mb-3">
                        <h5>Slot Rimanenti: <?= $slot_rimanenti ?>, Colonna numero: <?= $n_scheda ?></h5>
                        <label for="dimensione" class="form-label">Dimensione Sportello:</label>
                        <label for="dimensione"><small class="text-muted">(partendo dall'alto, il primo slot deve avere dimensione 2x, il vano tecnico è inseribile solo dopo il primo slot)</small></label>
                        <select class="form-select" name="dimensione" id="dimensione">
                            <?php for($i = 0; $i < count($available); $i++): ?>
                                <?php $dimensione = ($i +1).'x' ?>
                                <?= $available[$i] ? "<option value='$dimensione'>$dimensione</option>" : '' ?>
                            <?php endfor;?>
                        </select>

                        <label for="n_scheda"class="form-label">Numero della scheda</label>
                        <input type="number" class="form-control" name="n_scheda" id="n_scheda" value="<?= $n_scheda?>" required>
                        <label for="n_serratura" class="form-label">Numero della serratura</label>
                        <input type="number" class="form-control" id="n_serratura" name="n_serratura" id="n_serratura" value="<?= $n_serratura ?>" required>
                    </div>
                    <div class="d-grid gap-2 mb-2">
                        <button type="submit" class="btn btn-primary" <?= ($slot_rimanenti > 0 && $configuratore_attivo) ? '' : 'disabled' ?>>Prossimo Slot</button>
                    </div>
                </form>
                <form method="POST" action="configura-colonna.php">
                    <input type="hidden" name="n_sportello" value="<?= $n_sportello ?>">
                    <input type="hidden" name="n_scheda" value="<?= $n_scheda?>">
                    <input type="hidden" name="n_serratura" value="<?= "$n_serratura" ?>" id="n_serratura-hidden">
                    <input type="hidden" name="slot_rimanenti" value="<?= $slot_rimanenti ?>" id="slot_rimanenti">
                    <input type="hidden" name="dimensione" value="2x">
                    <input type="hidden" name="inserimento-vano" value="true">
                    <div class="d-grid gap-2 mb-2">
                        <button type="submit" class="btn btn-primary" <?= $vano_tecnico_inseribile ? '' : 'disabled' ?>>Inserisci Vano Tecnico</button>
                    </div>
                </form>
                <form method="POST" action="configura-colonna.php">
                    <?= ($slot_rimanenti == 0 && $configuratore_attivo) ? "<input type='hidden' name='prossima-colonna' value='true'>" : '' ?>
                    <input type="hidden" name="n_sportello" value="<?= $n_sportello ?>">
                    <input type="hidden" name="n_scheda" value="<?= $n_scheda?>">
                    <div class="d-grid gap-2 mb-2">
                        <button type='submit' class='btn btn-primary' <?= ($slot_rimanenti == 0 && $configuratore_attivo) ? '' : 'disabled' ?>>Prossima Colonna</button>
                    </div>
                </form>
            </div>
            <div class="col">
                <h2>Riepilogo configurazione Locker</h2>
                <div class="table-container">
                    <?php $i = 0;
                    $rimanenti = 11;
                    echo "<table>";
                    while($i < count($sportelli)) {
                        if($vano_tecnico['n_scheda'] == $sportelli[$i]['n_scheda'] && $rimanenti == 9){
                            $_n_scheda = $vano_tecnico['n_scheda'];
                            $tooltip_html = "<b>Vano Tecnico</b><br><b>Numero colonna</b>: $_n_scheda";
                            echo "<tr><td><a href='#' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-html='true' data-bs-title='$tooltip_html'><svg width='100' height='{$height}' style='border: solid #000'><rect width='100' height='{$height}' fill='#fff' /></svg></a></td></tr>";
                            $rimanenti -= $_dimensione_int;
                        }else{
                            $_dimensione = $sportelli[$i]['dimensione'];
                            $_dimensione_int = intval(substr($_dimensione, 0, -1));
                            $height = $_dimensione_int * 30;

                            $_n_sportello = $sportelli[$i]['n_sportello'];
                            $_n_scheda = $sportelli[$i]['n_scheda'];
                            $_n_serratura = $sportelli[$i]['n_serratura'];

                            $tooltip_html = "<b>Numero sportello</b>: $_n_sportello<br><b>Dimensione</b>: $_dimensione<br><b>Numero colonna</b>: $_n_scheda<br><b>Numero serratura</b>: $_n_serratura";
                            echo "<tr><td><a href='#' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-html='true' data-bs-title='$tooltip_html'><svg width='100' height='{$height}'><rect width='100' height='{$height}' fill='#000' /></svg></a></td></tr>";

                            $rimanenti -= $_dimensione_int;
                            if(end($sportelli) == $sportelli[$i] && $rimanenti != 0){
                                $height = $rimanenti * 30 + $rimanenti * 1.5;
                                echo "<tr><td><a href='#' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-html='true' data-bs-title='Spazio Rimanente'><svg width='100' height='{$height}'><rect width='100' height='{$height}' fill='#bbb' /></svg></a></td></tr>";
                            }

                            $i += 1;
                        }
                        if($rimanenti == 0){
                            echo "</table><table>";
                            $rimanenti = 11;
                        }
                    }
                    echo "</table>";
                    ?>
                </div>
                <br><br>
                <div class="row">
                    <div class="col">
                        <svg width="25" height="25" style="border: solid #000"><rect width="25" height="25" fill="#fff" /></svg></td>
                        Blocco bianco: Vano tecnico
                        <br><br>
                        <svg width="25" height="25" style="border: solid #000"><rect width="25" height="25" fill="#000" /></svg></td>
                        Blocco Nero: Sportello normale
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4 mb-4">
            <div class="row">
                <div class="col">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" id="btn-concludi" disabled>Concludi la configurazione</button>
                    </div>
                </div>
                <div class="col">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger" id="deleteConfiguration">Ricomincia la configurazione</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../scripts/configura-colonna.js"></script>
    <script src="../scripts/logout.js"></script>
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    <script>
        window.localStorage.setItem('vano-inserito', "<?= $_SESSION['vano-inserito'] ? true : false ?>");
    </script>
</body>
</html>
