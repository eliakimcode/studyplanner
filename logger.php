<?php

/**
 * @param string
 * @param string 
 */
function log_activity($student_name, $action)
{
    $log_file = 'study_planner.log';
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] - $student_name - $action\n";

    // Append the log entry to the file
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}
