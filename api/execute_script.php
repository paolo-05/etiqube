<?php
$nSportello = $_POST['n_sportello'];
$output = shell_exec("python3 ./../var/main.py " . escapeshellarg($nSportello));
echo $output;
?>
