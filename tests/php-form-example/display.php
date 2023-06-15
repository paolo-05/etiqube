<!DOCTYPE HTML>  
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
$name = isset($_GET['name']) ? $_GET['name'] : "";
$height = isset($_GET['height']) ? $_GET['height'] : "";
$weight = isset($_GET['weight']) ? $_GET['weight'] : "";
$email = isset($_GET['email']) ? $_GET['email'] : "";
$website = isset($_GET['website']) ? $_GET['website'] : "";
$comment = isset($_GET['comment']) ? $_GET['comment'] : "";
$gender = isset($_GET['gender']) ? $_GET['gender'] : "";
$dateString = isset($_GET['dateString']) ? $_GET['dateString'] : "";

echo "<h2>Your Input:</h2>";
echo "<i>this is from $dateString</i>";
echo "<br>";
echo $name;
echo "<br>";
echo $height;
echo "<br>";
echo $weight;
echo "<br>";
echo $email;
echo "<br>";
echo $website == "" ? "" : $website."<br>";
echo $comment == "" ? "" : $comment."<br>";
echo $gender;
?>
</body>
</html>
