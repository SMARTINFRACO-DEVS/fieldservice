<?php
// Database connection
$dsn = 'mysql:host=mysql;dbname=mydatabase';
$username = 'user';
$password = 'password';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve all users with their plain text passwords
    $query = "SELECT id, password FROM users";
    $stmt = $pdo->query($query);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        $id = $user['id'];
        $plainPassword = $user['password'];

        // Hash the plain text password using bcrypt
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        // Update the user's password with the hashed version
        $updateQuery = "UPDATE users SET password = :hashedPassword WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':hashedPassword', $hashedPassword);
        $updateStmt->bindParam(':id', $id);
        $updateStmt->execute();
    }

    echo "Passwords have been securely hashed!";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
