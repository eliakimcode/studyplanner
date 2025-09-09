<?php
include 'database.php';

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $index_number = $_POST['index_number'];
    $password = $_POST['password'];

    // Prepare statement to fetch student data
    $stmt = $conn->prepare("SELECT id, name, profile_picture, password FROM student WHERE index_number = ?");
    $stmt->bind_param("s", $index_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $student['password'])) {
            // Set session variables upon successful login
            $_SESSION['student_id'] = $student['id'];
            $_SESSION['name'] = $student['name'];
            $_SESSION['profile_picture'] = $student['profile_picture'];
            
            // Redirect to the dashboard
            header("Location: dashboard.html");
            exit();
        } else {
            $errorMessage = "Invalid password.";
        }
    } else {
        $errorMessage = "No student found with that index number.";
    }
    $stmt->close();
}
?>
