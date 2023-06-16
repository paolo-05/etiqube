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

// Function to update the dimension of a slot in the database
function updateSlotDimension($n_sportello, $n_scheda, $dimensione)
{
    global $db;

    try {
        $stmt = $db->prepare("UPDATE scomparti SET dimensione = ? WHERE n_sportello = ? AND n_scheda = ?");
        $stmt->execute([$dimensione, $n_sportello, $n_scheda]);
    } catch (PDOException $e) {
        die("Error updating slot dimension: " . $e->getMessage());
    }
}

// Function to update the enabled status of a slot in the database
function updateSlotEnabled($n_sportello, $n_scheda, $enabled)
{
    global $db;

    try {
        $stmt = $db->prepare("UPDATE scomparti SET enabled = ? WHERE n_sportello = ? AND n_scheda = ?");
        $stmt->execute([$enabled, $n_sportello, $n_scheda]);
    } catch (PDOException $e) {
        die("Error updating slot enabled status: " . $e->getMessage());
    }
}

// Function to increment the n_scheda and reset slots
function incrementNScheda($n_sportello, $current_n_scheda)
{
    global $db;

    $next_n_scheda = $current_n_scheda + 1;

    // Check if the next n_scheda is within the allowed range (1-10)
    if ($next_n_scheda <= 10) {
        try {
            // Reset slots for the next n_scheda
            $stmt = $db->prepare("UPDATE scomparti SET enabled = 0 WHERE n_sportello = ? AND n_scheda = ?");
            $stmt->execute([$n_sportello, $next_n_scheda]);
            
            return $next_n_scheda;
        } catch (PDOException $e) {
            die("Error incrementing n_scheda: " . $e->getMessage());
        }
    }

    return $current_n_scheda;
}


?>
