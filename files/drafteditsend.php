<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}


require 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and validate input
  $customer_name = htmlspecialchars($_POST['customer_name']);
  $date = htmlspecialchars($_POST['date']);
  $comments = htmlspecialchars($_POST['comments']);
  $EngName = htmlspecialchars($_POST['EngName']);
  $customer_location = htmlspecialchars($_POST['customer_location']);
  $customer_address = htmlspecialchars($_POST['customer_address']);
  $customer_contact = htmlspecialchars($_POST['customer_contact']);
  $customer_signature = htmlspecialchars($_POST['customer_signature']);
  $equipments_installed = array_map('htmlspecialchars', $_POST['equipments_installed']);
  $equipments_installed = implode(",", $equipments_installed);
  $config_details = htmlspecialchars($_POST['config_details']);
  $serial_numbers = htmlspecialchars($_POST['serial_numbers']);
  $creator = $_SESSION['user_name'];
  $type_of_service = htmlspecialchars($_POST['type_of_service']);
  $site_id = htmlspecialchars($_POST['site_id']);

  $is_draft = isset($_POST['save_draft']) ? 1 : 0;

  // Handle image uploads
  $imgFilesArray = $existing_images;
  $totalImgFiles = count($_FILES['fileImg']['name']);
  
  for ($i = 0; $i < $totalImgFiles; $i++) {
      if ($_FILES["fileImg"]["name"][$i]) {
          $imgName = $_FILES["fileImg"]["name"][$i];
          $imgTmpName = $_FILES["fileImg"]["tmp_name"][$i];
          $imgExtension = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));
          $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
          if (in_array($imgExtension, $allowedExtensions)) {
              $newImgName = uniqid() . '.' . $imgExtension;
              $targetImgFilePath = 'uploads/' . $newImgName;

              if (move_uploaded_file($imgTmpName, $targetImgFilePath)) {
                  $imgFilesArray[] = $newImgName;
              } else {
                  echo "Failed to upload image file {$imgName}.<br>";
              }
          } else {
              echo "Invalid file type for image {$imgName}.<br>";
          }
      }
  }

  // Handle text file upload
  if ($_FILES["fileTxt"]["name"]) {
      $txtName = $_FILES["fileTxt"]["name"];
      $txtTmpName = $_FILES["fileTxt"]["tmp_name"];
      $txtExtension = strtolower(pathinfo($txtName, PATHINFO_EXTENSION));
      if ($txtExtension === 'txt') {
          $newTxtName = uniqid() . '.' . $txtExtension;
          $targetTxtFilePath = 'uploads/' . $newTxtName;

          if (move_uploaded_file($txtTmpName, $targetTxtFilePath)) {
              $existing_text_file = $newTxtName;
          } else {
              echo "Failed to upload text file {$txtName}.<br>";
          }
      } else {
          echo "Invalid file type for text file {$txtName}.<br>";
      }
  }

  $imgFilesJson = json_encode($imgFilesArray);

  if ($record_id) {
      $query = "UPDATE tb_images SET customer_name=?, date=?, comments=?, image=?, text_file=?, EngName=?, customer_location=?, customer_address=?, customer_contact=?, customer_signature=?, equipments_installed=?, config_details=?, serial_numbers=?, type_of_service=?, site_id=?, creator=?,is_draft=? WHERE id=?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("ssssssssssssssssi", $customer_name, $date, $comments, $imgFilesJson, $existing_text_file, $EngName, $customer_location, $customer_address, $customer_contact, $customer_signature, $equipments_installed, $config_details, $serial_numbers, $type_of_service, $creator,$site_id, $is_draft, $record_id);
  } else {
      $query = "INSERT INTO tb_images (customer_name, date, comments, image, text_file, EngName, customer_location, customer_address, customer_contact, customer_signature, equipments_installed, config_details, serial_numbers, type_of_service, site_id,creator, is_draft) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("sssssssssssssssss", $customer_name, $date, $comments, $imgFilesJson, $existing_text_file, $EngName, $customer_location, $customer_address, $customer_contact, $customer_signature, $equipments_installed, $config_details, $serial_numbers, $type_of_service, $creator, $site_id, $is_draft);
  }

  if ($stmt->execute()) {
      echo "<script>
          alert('Record saved successfully.');
          window.location.href = 'home.php';
      </script>";
  } else {
      echo "Error: " . $stmt->error;
  }

  $stmt->close();
}
?>
