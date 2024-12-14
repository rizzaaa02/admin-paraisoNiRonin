<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $staffID = $_POST['staffID'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $status = $_POST['status'];
    $activity_id = $_POST['activity_id'];

    // Check the number of schedules for the staff on the given date
    $sqlCount = "SELECT COUNT(*) as schedule_count FROM staff_schedules WHERE staffID = ? AND date = ?";
    $stmtCount = $conn->prepare($sqlCount);
    $stmtCount->bind_param("is", $staffID, $date);
    $stmtCount->execute();
    $resultCount = $stmtCount->get_result();
    $rowCount = $resultCount->fetch_assoc();
    $scheduleCount = $rowCount['schedule_count'];

    if ($scheduleCount >= 5) {
        // Staff has reached the daily limit
        echo "Error: The staff has already reached the maximum of 5 schedules for the day.";
        exit();
    }

    // Check for conflicting schedules
    $sqlCheck = "SELECT * FROM staff_schedules 
                 WHERE staffID = ? 
                 AND date = ? 
                 AND (
                    (start_time <= ? AND end_time > ?)  -- New start_time overlaps
                    OR
                    (start_time < ? AND end_time >= ?)  -- New end_time overlaps
                    OR
                    (start_time >= ? AND end_time <= ?) -- New range is within an existing range
                 )";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param(
        "isssssss",
        $staffID,
        $date,
        $start_time,
        $start_time,
        $end_time,
        $end_time,
        $start_time,
        $end_time
    );

    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        // There is a conflicting schedule
        echo "Error: The staff has an existing schedule that overlaps with the proposed time.";
    } else {
        // No conflict, proceed with inserting the new schedule
        $conn->begin_transaction();

        try {
            $sql = "INSERT INTO staff_schedules (staffID, date, start_time, end_time, status) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $staffID, $date, $start_time, $end_time, $status);
            $stmt->execute();
            $scheduleID = $stmt->insert_id;

            $sqlAssignment = "INSERT INTO staff_assignments (scheduleID, activity_id) 
                              VALUES (?, ?)";
            $stmtAssignment = $conn->prepare($sqlAssignment);
            $stmtAssignment->bind_param("ii", $scheduleID, $activity_id);
            $stmtAssignment->execute();

            $conn->commit();

            header("Location: ../staff-scheduling.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    }

    $stmtCheck->close();
    $stmtCount->close();
}

$conn->close();
