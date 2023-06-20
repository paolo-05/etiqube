<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: /pages/homepage.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the password is correct
    if ($password === '1111') {
        $_SESSION['username'] = $username;
        header("Location: /pages/homepage.php");
        exit();
    } else {
        $errorMessage = 'Incorrect password';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="google" content="notranslate">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/styles/login-page.css">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Login</h2>
        <form method="POST">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </div>
            <?php if (isset($errorMessage)) : ?>
                <p class="error text-center"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
