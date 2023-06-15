<?php
$sportelliRimanenti = 10;
$configuration = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sportelliRimanenti = intval($_POST["sportelli"]);
    $dimensione = 1;

    switch ($_POST["dimensione"]) {
        case "1x":
            $dimensione = 1;
            break;
        case "2x":
            $dimensione = 2;
            break;
        case "3x":
            $dimensione = 3;
            break;
    }
    $sportelliRimanenti -= $dimensione;

    $columnNumber = isset($_GET["column-number"]) ? intval($_GET["column-number"]) : 1;
    $configuration[] = array("column" => $columnNumber, "dimension" => $dimensione);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["configuration"])) {
    $configuration = $_POST["configuration"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="google" content="nostranslate">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuratore</title>
</head>
<body>
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="column-number">Numero della colonna: </label>
        <input type="number" name="column-number" required value="1" min="1" max="10">
        <input type="submit" name="submit" value="comincia a configurare">
    </form>
    <br>
    <?php if (isset($_GET['column-number']) || $_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="hidden" name="sportelli" value="<?php echo $sportelliRimanenti; ?>">
            <input type="hidden" name="configuration" value="<?php echo htmlspecialchars(json_encode($configuration)); ?>">
            <?php
            echo "Slot rimanenti: $sportelliRimanenti<br>";
            echo "Grandezza: <select name='dimensione'><option value='1x'>1x</option><option value='2x'>2x</option><option value='3x'>3x</option></select>";
            if ($sportelliRimanenti > 0) {
                echo "<br><input type='submit' name='submit' value='Prossimo sportello'>";
            } else {
                echo "<br><input type='submit' disabled name='submit' value='Prossimo sportello'>";
            }
            ?>
        </form>
    <?php endif; ?>

    <?php if (!empty($configuration)) : ?>
        <table>
            <thead>
                <tr>
                    <th>Column</th>
                    <th>Dimension</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($configuration as $config) : ?>
                    <tr>
                        <td><?php echo $config["column"]; ?></td>
                        <td><?php echo $config["dimension"]; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
