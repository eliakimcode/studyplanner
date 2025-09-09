<?php

/**
 * Logs user activity to a file.
 *
 * @param string $student_name The name of the student.
 * @param string $action The action performed (e.g., 'Logged in', 'Logged out').
 */
function log_activity($student_name, $action)
{
    $log_file = 'study_planner.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] - $student_name - $action\n";

    // Append the log entry to the file
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}
