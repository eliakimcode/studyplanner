<?php
include 'database.php';

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $index_number = $_POST['index_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    // Handle profile picture upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir);
    }
    $file_extension = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
    $unique_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $unique_filename;
    
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        // Use a prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO student (name, index_number, profile_picture, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $index_number, $target_file, $password);
        
        if ($stmt->execute()) {
            // Redirect directly with PHP after successful registration
            header("Location: login.html");
            exit();
        } else {
            $errorMessage = "Error: Could not register. " . $stmt->error;
        }
        $stmt->close();
    } else {
        $errorMessage = "Error: Failed to upload profile picture.";
    }
}
?>