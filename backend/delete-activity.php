<?php
include '../connect.php';

if (isset($_POST['activity_reservation_id'])) {
    $activity_reservation_id = $_POST['activity_reservation_id'];
    error_log("ID: " . $activity_reservation_id);

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Delete the corresponding record from activity_reservations
        $queryAssignments = "DELETE FROM activity_reservations WHERE activity_reservation_id = ?";
        if ($stmt = $conn->prepare($queryAssignments)) {
            $stmt->bind_param("i", $activity_reservation_id); // Bind the correct ID parameter
            if ($stmt->execute()) {
                $stmt->close();
                $conn->commit();
                echo json_encode(['success' => true]);
            } else {
                throw new Exception("Error deleting reservation");
            }
        } else {
            throw new Exception("Query preparation failed");
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    // Close the database connection
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No activity reservation ID provided']);
}
