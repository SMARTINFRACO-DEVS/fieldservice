<?php
$host = 'mysql'; // Docker service name
$dbname = 'mydatabase';
$username = 'user';
$password = 'password';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
 
 
 
<?php
$host = 'mysql'; // Docker service name if using Docker Compose
$dbname = 'mydatabase';
$username = 'user';
$password = 'password';
 
// Create connection
$conn = new mysqli($host, $username, $password, $dbname);
 
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>