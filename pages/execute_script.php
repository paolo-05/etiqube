<?php
$nSportello = $_POST['n_sportello'];
$output = shell_exec("python3 main.py " . escapeshellarg($nSportello));
echo $output;
?>
