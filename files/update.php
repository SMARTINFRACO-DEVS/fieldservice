<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}


require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        die("Error: No record ID provided for updating.");
    }


    $id = $_POST['id'];
    $customer_name = $_POST['customer_name'];
    $customer_location = $_POST['customer_location'];
    $customer_address = $_POST['customer_address'];
    $customer_contact = $_POST['customer_contact'];
    $customer_signature = $_POST['customer_signature'];
    $equipments_installed = isset($_POST['equipments_installed']) ? $_POST['equipments_installed'] : []; 
    $config_details = $_POST['config_details'];
    $serial_numbers = $_POST['serial_numbers'];
    $type_of_service = $_POST['type_of_service'];
    $site_id = $_POST['site_id'];
    $date = $_POST['date'];
    $EngName = $_POST['EngName'];
    $comments = $_POST['comments'];


    $equipments_installed_string = is_array($equipments_installed) ? implode("\n", $equipments_installed) : ''; 
    try {
        // Prepare the update query
        $query = "UPDATE tb_images SET customer_name = ?, customer_location = ?, customer_address = ?, customer_contact = ?, customer_signature = ?, equipments_installed = ?, config_details = ?, serial_numbers = ?, type_of_service = ?, site_id = ?, date = ?, EngName = ?, comments = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssssssi", $customer_name, $customer_location, $customer_address, $customer_contact, $customer_signature, $equipments_installed_string, $config_details, $serial_numbers, $type_of_service, $site_id, $date, $EngName, $comments, $id);

        if ($stmt->execute()) {
          // Display a success message with a confirmation prompt
          echo "<script>
              if (confirm('Record has been updated successfully! Click OK to go back to the home page.')) {
                  window.location.href = 'home.php';
              }
          </script>";
      } else {
          echo "Error updating record: " . $conn->error;
      }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    die("Invalid request method.");
}
?>
