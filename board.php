<?php
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['name'];
$profile_picture = $_SESSION['profile_picture'];

// Variable to hold success/error messages
$message = '';
$message_type = '';

// Handle messages passed via URL parameter
if (isset($_GET['message'])) {
    if ($_GET['message'] === 'add_success') {
        $message = 'Study plan added successfully!';
        $message_type = 'success';
    } elseif ($_GET['message'] === 'mark_success') {
        $message = 'Study plan marked as fulfilled!';
        $message_type = 'success';
    }
}

// Handle new study plan submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_plan'])) {
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("INSERT INTO study_plans (student_id, course_name, set_date, due_date, description) VALUES (?, ?, NOW(), ?, ?)");
    $stmt->bind_param("isss", $student_id, $course_name, $due_date, $description);
    if ($stmt->execute()) {
        header("Location: dashboard.php?message=add_success");
        exit();
    } else {
        $message = 'Error: Could not add plan.';
        $message_type = 'error';
    }
    $stmt->close();
}

// Handle marking a plan as completed
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mark_completed'])) {
    $plan_id = $_POST['plan_id'];

    $stmt = $conn->prepare("UPDATE study_plans SET completed = 1 WHERE id = ? AND student_id = ?");
    $stmt->bind_param("ii", $plan_id, $student_id);
    if ($stmt->execute()) {
        header("Location: dashboard.php?message=mark_success");
        exit();
    } else {
        $message = 'Error: Could not update plan.';
        $message_type = 'error';
    }
    $stmt->close();
}

// Fetch all study plans for the logged-in student
$upcoming_plans = [];
$fulfilled_plans = [];

$stmt = $conn->prepare("SELECT * FROM study_plans WHERE student_id = ? ORDER BY due_date ASC");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

while ($plan = $result->fetch_assoc()) {
    if ($plan['completed'] == 1) {
        $fulfilled_plans[] = $plan;
    } else {
        $upcoming_plans[] = $plan;
    }
}
$stmt->close();
?>
