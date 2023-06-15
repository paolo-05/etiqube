<!DOCTYPE HTML>
<html>
<head>
    <meta name="google" content="notranslate">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>User Management - User Form</title>
</head>
<body>
    <?php
    require_once 'validate.php';
    
    $nameErr = $emailErr = $operationError = "";
    
    // Retrieve the user by ID using cURL
    function getUserById($id) {
        $url = 'http://127.0.0.1:8080/php-db-form/api/db-interactions.php?action=getUserById&id=' . $id;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
        curl_close($ch);
    
        // Handle the response from the API endpoint
        if ($response === false) {
            echo "Error: " . curl_error($ch);
            return null;
        } else {
            $user = json_decode($response, true);
            return $user['data'];
        }
    }

    function checkData() {
        global $operationError;
        global $nameErr;
        $nameErr = validateName($_POST['name']);
        
        global $emailErr;
        $emailErr = validateEmail($_POST['email']);
        
        if ($nameErr !== "" || $emailErr !== "") {
            return;
        }
        
        if ($_POST['id'] > 0) {
            $data = array(
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'email' => $_POST['email']
            );
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8080/php-db-form/api/db-interactions.php');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $response = curl_exec($ch);
            curl_close($ch);
    
            // Handle the response from the API endpoint
            if ($response === false) {
                $operationError = curl_error($ch);
            }
        } else {
            $data = array(
                'name' => $_POST['name'],
                'email' => $_POST['email']
            );
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8080/php-db-form/api/db-interactions.php');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
            $response = curl_exec($ch);
            curl_close($ch);
    
            // Handle the response from the API endpoint
            if ($response === false) {
                $operationError =  curl_error($ch);
                
            }
        }
         // Create the alert message
        if (!empty($operationError)) {
            $alert = '<div class="alert alert-danger" role="alert">' . $operationError . '</div>';
        } else {
            $alert = '<div class="alert alert-success" role="alert">User operation successful.</div>';
        }

    echo $alert;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        checkData();
    }
    
    $name = $email = "";
    $action = "Insert";
    
    // Check if the ID is provided in the query parameters
    $id = isset($_GET['id']) ? $_GET['id'] : -1;
    $user = ($id != -1) ? getUserById($id) : null;
    
    if ($user != null) {
        $name = $user['name'];
        $email = $user['email'];
        $action = "Update";
    }
    ?>

    <div class="container">
        <h2>User Form</h2>
        <p><span>* required field</span></p>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="input-group mb-3">
                <span class="input-group-text">Name</span>
                <input class="form-control" type="text" name="name" value="<?php echo $name; ?>">
                <span class="input-group-text">*<?php echo $nameErr; ?></span>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Email</span>
                <input class="form-control" type="text" name="email" value="<?php echo $email; ?>">
                <span class="input-group-text">*<?php echo $emailErr; ?></span>
            </div>

            <input class="btn btn-primary" type="submit" name="submit" value="<?php echo $action; ?>">
            <button type="button" class="btn btn-primary" onclick="document.location.href = '/php-db-form/'">Home</button>
            <button type="button" class="btn btn-primary" onclick="document.location.href = 'users.php'">See all users</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
