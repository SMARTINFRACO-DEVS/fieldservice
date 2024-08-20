<?php
// Database connection parameters
$host = 'mysql';
$dbname = 'mydatabase';
$username = 'user';
$password = 'password';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve and sanitize the input
        $user = htmlspecialchars($_POST['username']);
        $pass = htmlspecialchars($_POST['password']);
        $namepass = htmlspecialchars($_POST['name']);

        // Hash the password using bcrypt
        $hashedPassword = password_hash($pass, PASSWORD_BCRYPT);

        // Prepare the SQL statement
        $sql = "INSERT INTO users (user_name, password,name) VALUES (:username, :password,:namepass)";
        $stmt = $pdo->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':username', $user);
        $stmt->bindParam(':namepass', $namepass);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            echo "User created successfully!";
        } else {
            echo "Error creating user.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- HTML form for user input -->
<!DOCTYPE html>
<html>
<head>
    <title>Create User</title>
</head>
<body>
    <h2>Create User Reg</h2>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>
        <input type="submit" value="Create User">
    </form>
</body>
</html>
