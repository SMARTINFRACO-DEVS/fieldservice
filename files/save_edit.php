<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $id = $_POST['id'];
    $editedContent = $_POST['edited_content'];

    // Ensure the edited content is saved to the text file
    $filepath = 'uploads/config.txt';
    if (file_put_contents($filepath, $editedContent) !== false) {
        // Update the database with the new text file name or content
        $query = "UPDATE tb_images SET text_file = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $filepath, $id);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: details.php?id=$id");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    } else {
        echo "Error writing to file.";
    }
} else {
    echo "Invalid request.";
}
?>
