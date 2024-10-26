<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}
require 'config.php';

if (isset($_POST['search'])) {
    $search = $_POST['search'];
    try {
        $query = "SELECT * FROM table_backup WHERE customer_name LIKE ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $searchParam);
        
        $searchParam = "%{$search}%";
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td  class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td  class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'><a href='details.php?id={$row['id']}' >" . htmlspecialchars($row['customer_name']) . "</a></td>";
                echo "<td  class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td  class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'>" . htmlspecialchars($row['type_of_service']) . "</td>";
                echo "<td  class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100'>" . htmlspecialchars($row['timestamp_field']) . "</td>";

                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No records found</td></tr>";
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
    }
    
    $conn->close();
}
?>
