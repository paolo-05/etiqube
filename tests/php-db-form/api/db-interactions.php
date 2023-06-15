<?php
// Database connection settings
$host = 'localhost';
$dbName = 'phptest';
$username = 'paolo';
$password = 'root';

// Function to establish a database connection
function connectToDatabase() {
    global $host, $dbName, $username, $password;
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Error connecting to the database: " . $e->getMessage();
        return null;
    }
}

// Insert a new user
function insertUser($name, $email) {
    $pdo = connectToDatabase();
    if ($pdo) {
        try {
            $statement = $pdo->prepare("INSERT INTO users (name, email)
                                        VALUES (:name, :email)");

            $statement->bindParam(':name', $name);
            $statement->bindParam(':email', $email);

            $statement->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error inserting user: " . $e->getMessage();
            return false;
        }
    }
    return false;
}

// Update an existing user
function updateUser($id, $name, $email) {
    $pdo = connectToDatabase();
    if ($pdo) {
        try {
            $statement = $pdo->prepare("UPDATE users SET name = :name, email = :email
                                        WHERE id = :id");

            $statement->bindParam(':id', $id);
            $statement->bindParam(':name', $name);
            $statement->bindParam(':email', $email);
            $statement->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error updating user: " . $e->getMessage();
            return false;
        }
    }
    return false;
}

// Retrieve all users from the database
function getAllUsers()
{
    $pdo = connectToDatabase();
    if ($pdo) {
        try {
            $statement = $pdo->prepare("SELECT * FROM users");
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving users: " . $e->getMessage();
            return [];
        }
    }
    return [];
}

// Delete a user by ID
function deleteUser($id)
{
    $pdo = connectToDatabase();
    if ($pdo) {
        try {
            $statement = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $statement->bindParam(':id', $id);
            $statement->execute();
            return true;
        } catch (PDOException $e) {
            echo "Error deleting user: " . $e->getMessage();
            return false;
        }
    }
    return false;
}

// Retrieve a user by ID
function getUserById($id)
{
    $pdo = connectToDatabase();
    if ($pdo) {
        try {
            $statement = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $statement->bindParam(':id', $id);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error retrieving user: " . $e->getMessage();
            return null;
        }
    }
    return null;
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert new user
    if (isset($_POST['name']) && isset($_POST['email']) && !isset($_POST['id'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $result = insertUser($name, $email);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'User inserted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert user']);
        }
    }
    // Update existing user
    elseif (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $result = updateUser($id, $name, $email);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'User updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update user']);
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all users
    if (isset($_GET['action']) && $_GET['action'] === 'getAllUsers') {
        $users = getAllUsers();
        echo json_encode(['status' => 'success', 'data' => $users]);
    }
    // Retrieve user by ID
    elseif (isset($_GET['action']) && $_GET['action'] === 'getUserById' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $user = getUserById($id);

        if ($user) {
            echo json_encode(['status' => 'success', 'data' => $user]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'User not found']);
        }
    }
    // Delete user by ID
    elseif (isset($_GET['action']) && $_GET['action'] === 'deleteUser' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $result = deleteUser($id);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
        }
    }
}
?>