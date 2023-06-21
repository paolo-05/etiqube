<?php
$nSportello = $_POST['n_sportello'];
echo shell_exec("python3 ./../var/main.py " . escapeshellarg($nSportello));
?>
