<?php
ob_start();
session_start();
include "config.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    if (empty($uname)) {
        header("Location: index.php?error=User Name is required");
        exit();
    } else if (empty($pass)) {
        header("Location: index.php?error=Password is required");
        exit();
    } else {
        // Prepare a statement to prevent SQL injection
        $query = "SELECT password, id, name FROM users WHERE user_name = :user_name";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_name', $uname);
        $stmt->execute();

        // Fetch the stored hashed password
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $storedHash = $row['password'];

        if ($storedHash && password_verify($pass, $storedHash)) {
            // Password is correct, proceed with the login
            $_SESSION['user_name'] = $uname;
            $_SESSION['name'] = $row['name'];
            $_SESSION['id'] = $row['id'];
            header("Location: home.php");
            exit();
        } else {
            // Password is incorrect
            header("Location: index.php?error=Incorrect User name or password");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}

ob_end_flush();
?>
