<?php
$dsn = 'mysql:host=localhost;dbname=locker;charset=utf8mb4';
$username = 'paolo';
$password = 'root';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to insert a new slot into the database
function insertSlot($n_sportello, $n_scheda, $n_serratura, $dimensione)
{
    global $db;

    $enabled = 1;

    try {
        $stmt = $db->prepare("INSERT INTO scomparti (n_sportello, n_scheda, n_serratura, dimensione, enabled) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$n_sportello, $n_scheda, $n_serratura, $dimensione, $enabled]);
    } catch (PDOException $e) {
        die("Error inserting slot: " . $e->getMessage());
    }
}

function insertSettings($n_scheda, $n_serratura){
    global $db;

    try{
        $stmt = $db->prepare("INSERT INTO settings (n_scheda, n_serratura) VALUES (?, ?)");
        $stmt->execute([$n_scheda, $n_serratura]);
    } catch (PDOException $e) {
        die("Error inserting settings: " . $e->getMessage());
    }
}

function getConfiguration(){
    global $db;

    try {
        $stmt = $db->query("SELECT * FROM scomparti");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
        die("Error fetching configuration: ". $e->getMessage());
    }
}

function getSettings(){
    global $db;

    try {
        $stmt = $db->query("SELECT * FROM settings");
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response[0];
        } catch (PDOException $e) {
        die("Error fetching settings: ". $e->getMessage());
    }
}

function getNumeroColonne(){
    global $db;
    
    try {
        $stmt = $db->query("SELECT * FROM `scomparti` ORDER BY ID DESC LIMIT 1;");
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response[0]['n_scheda'];
        } catch (PDOException $e) {
        die("Error fetching numero colonne: ". $e->getMessage());
    }
}

function getNumeroSportelli()
{
    global $db;
    try{
        $stmt = $db->query("SELECT COUNT(*) FROM `scomparti`;");
        $response =  $stmt->fetch(PDO::FETCH_ASSOC);
        return $response['COUNT(*)'];
    } catch (PDOException $e) {
        die("Error fetching numero sportelli: ". $e->getMessage());
    }
}

function deleteConfiguration(){
    global $db;
    try{
        $stmt = $db->query("DELETE FROM `scomparti`");
        $stmt->execute();
        $stmt = $db->query("DELETE FROM `settings`");
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo "Error deleting configuration: ". $e->getMessage();
        return false;
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']) && $_GET['action'] == 'deleteCofiguration'){
    $result = deleteConfiguration();
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Configuration deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete configuration']);
    }
}

?>