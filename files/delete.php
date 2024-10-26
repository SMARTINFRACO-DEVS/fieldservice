<?php
require 'config.php';

// Ensure ID parameter is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid request. Missing record ID.");
}

$id = $_GET['id'];

try {
    // Delete record from database
    $query = "DELETE FROM tb_images WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "
    <script>
    alert('Successfully deleted from Database');
    window.location.href = 'home.php';
</script>";
    exit();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
