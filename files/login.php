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
        $query = "SELECT password, id, name FROM users WHERE user_name = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("s", $uname); // "s" specifies that $uname is a string
            $stmt->execute();

            // Fetch the result
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $storedHash = $row['password'];

            if ($storedHash && password_verify($pass, $storedHash)) {
                // Password is correct, proceed with the login
                $_SESSION['user_name'] = $uname;
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                header("Location: index.php?error=Incorrect User name or password");
                exit();
            }
        } else {
            die("Failed to prepare the statement: " . $conn->error);
        }
    }
} else {
    header("Location: index.php");
    exit();
}

ob_end_flush();
?>
