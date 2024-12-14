<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scheduleID = $_POST['scheduleID'] ?? null;
    $date = $_POST['date'] ?? null;
    $startTime = $_POST['start_time'] ?? null;
    $endTime = $_POST['end_time'] ?? null;
    $assignedServices = $_POST['assignedservices'] ?? null;

    error_log("Received data: " . json_encode($_POST));

    if (!$scheduleID || !$date || !$startTime || !$endTime) {
        echo "error: Missing parameters. Received: " . json_encode($_POST);
        exit;
    }
    $updateScheduleQuery = "
        UPDATE staff_schedules 
        SET date = ?, start_time = ?, end_time = ? 
        WHERE scheduleID = ?
    ";

    $stmt = $conn->prepare($updateScheduleQuery);
    if ($stmt === false) {
        echo "error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("ssss", $date, $startTime, $endTime, $scheduleID);

    if ($stmt->execute()) {
        if ($assignedServices) {
            $checkAssignQuery = "
                SELECT 1 FROM staff_assignments 
                WHERE scheduleID = ?
            ";

            $stmtCheck = $conn->prepare($checkAssignQuery);
            if ($stmtCheck === false) {
                echo "error: " . $conn->error;
                exit;
            }

            $stmtCheck->bind_param("i", $scheduleID);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                // If scheduleID exists, update the serviceroomI
                $updateAssignQuery = "
                    UPDATE staff_assignments
                    SET activity_id = ?
                    WHERE scheduleID = ?
                ";
                $stmtUpdateAssign = $conn->prepare($updateAssignQuery);
                if ($stmtUpdateAssign === false) {
                    echo "error: " . $conn->error;
                    exit;
                }
                $stmtUpdateAssign->bind_param("ii", $assignedServices, $scheduleID);
                $stmtUpdateAssign->execute();
            } else {
                // If scheduleID doesn't exist, insert a new record
                $assignServiceQuery = "
                    INSERT INTO staff_assignments (scheduleID, activity_id) 
                    VALUES (?, ?)
                ";
                $stmtAssign = $conn->prepare($assignServiceQuery);
                if ($stmtAssign === false) {
                    echo "error: " . $conn->error;
                    exit;
                }
                $stmtAssign->bind_param("ii", $scheduleID, $assignedServices);
                $stmtAssign->execute();
            }

            $stmtCheck->close();
        }

        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "error: Invalid request method.";
}
