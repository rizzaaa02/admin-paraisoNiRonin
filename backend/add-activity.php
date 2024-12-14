<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $user_id = $_POST['user_id'];
    $scheduled_date = $_POST['date'];
    $activity_id = $_POST['activity_id'];


    $conn->begin_transaction();

    try {

        $sql = "INSERT INTO activity_reservations (user_id, scheduled_date, activity_id) 
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("iss", $user_id, $scheduled_date, $activity_id);
        $stmt->execute();


        $scheduleID = $stmt->insert_id;
        $conn->commit();

        header("Location: ../activities.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();
}

$conn->close();
