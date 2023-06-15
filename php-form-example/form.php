
<!DOCTYPE HTML>  
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>  
<?php
// define variables and set to empty values
$nameErr = $heightErr = $weightErr = $emailErr = $genderErr = $websiteErr = "";
$name = $height = $weight =  $email = $gender = $comment = $website = "";
$dateString = date('Y-m-d H:i:s');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }

  if(empty($_POST["height"])){
    $heightErr = "Height is required";
  } else{
    $height = test_input($_POST["height"]);
    
    if(!filter_var($height, FILTER_VALIDATE_INT)){
        $heightErr = "Please enter a valid height"; 
    }
    else{
        $height = floatval($height);
    }
  }
  
  if(empty($_POST["weight"])){
    $weightErr = "Weight is required";
  } else{
    $weight = test_input($_POST["weight"]);
    
    if(!filter_var($weight, FILTER_VALIDATE_FLOAT)){
        $weightErr = "Please enter a valid weight"; 
    }
    else{
        $weight = floatval($weight);
    }
  }

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }
  }
    
  if (empty($_POST["website"])) {
    $website = "";
  } else {
    $website = test_input($_POST["website"]);
    if(
        !preg_match(
            "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",
            $website)){
        $websiteErr = "Invalid URL";
    }
  }

  if (empty($_POST["comment"])) {
    $comment = "";
  } else {
    $comment = test_input($_POST["comment"]);
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }

  if ($nameErr == "" && $heightErr == "" && $weightErr == "" && $emailErr == "" && $genderErr == "" && $websiteErr == "") {
    // Redirect to the other page if there are no errors
    header("Location: display.php?name=$name&height=$height&weight=$weight&email=$email&website=$website&comment=$comment&gender=$gender&dateString=$dateString");
    exit();
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<!DOCTYPE HTML>  
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>  

<h2>PHP Form Validation Example</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<!--<form method="post" action="/api/test.php">-->  
  Name: <input type="text" name="name" value="<?php echo $name; ?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  Height: <input type="text" name="height" value="<?php echo $height; ?>"> cm
  <span class="error">* <?php echo $heightErr;?></span>
  <br><br>
  Weight: <input type="text" name="weight" value="<?php echo $weight; ?>"> kg
  <span class="error">* <?php echo $weightErr;?></span>
  <br><br>
  E-mail: <input type="text" name="email" value="<?php echo $email; ?>">
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  Website: <input type="text" name="website" value="<?php echo $website; ?>">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>
  Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea>
  <br><br>
  Gender:
  <input type="radio" name="gender" value="female" <?php if ($gender === 'female') echo 'checked'; ?>>Female
  <input type="radio" name="gender" value="male" <?php if ($gender === 'male') echo 'checked'; ?>>Male
  <span class="error">* <?php echo $genderErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
</body>
</html>

