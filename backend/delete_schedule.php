<?php
include '../connect.php';

if (isset($_POST['scheduleID'])) {
    $scheduleID = $_POST['scheduleID'];
    error_log("Schedule ID: " . $scheduleID);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete corresponding records from staff_assignments
        $queryAssignments = "DELETE FROM staff_assignments WHERE scheduleID = ?";
        if ($stmt = $conn->prepare($queryAssignments)) {
            $stmt->bind_param("i", $scheduleID);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting from staff_assignments");
            }
            $stmt->close();
        } else {
            throw new Exception("Query preparation failed for staff_assignments");
        }

        // Delete the schedule from staff_schedules
        $querySchedule = "DELETE FROM staff_schedules WHERE scheduleID = ?";
        if ($stmt = $conn->prepare($querySchedule)) {
            $stmt->bind_param("i", $scheduleID);
            if ($stmt->execute()) {
                $conn->commit(); // Commit the transaction if everything is successful
                echo json_encode(['success' => true]);
            } else {
                throw new Exception("Error deleting from staff_schedules");
            }
            $stmt->close();
        } else {
            throw new Exception("Query preparation failed for staff_schedules");
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    // Close the database connection
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No schedule ID provided']);
}
