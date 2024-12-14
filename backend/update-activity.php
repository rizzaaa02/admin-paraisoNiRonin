<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activityReservationID = $_POST['activity_reservation_id'] ?? null;
    $activityName = $_POST['activityName'] ?? null;  // Adjusted to reflect the field in the form
    $date = $_POST['date'] ?? null;

    error_log("Received data: " . json_encode($_POST));

    if (!$activityReservationID || !$activityName || !$date) {
        echo "error: Missing parameters. Received: " . json_encode($_POST);
        exit;
    }

    // Corrected SQL Query
    $updateScheduleQuery = "
        UPDATE activity_reservations
        SET activity_id = ?, scheduled_date = ?
        WHERE activity_reservation_id = ?
    ";

    $stmt = $conn->prepare($updateScheduleQuery);
    if ($stmt === false) {
        echo "error: " . $conn->error;
        exit;
    }

    // Correct number of bind parameters: activityName (string), date (string), activityReservationID (integer)
    $stmt->bind_param("ssi", $activityName, $date, $activityReservationID);

    // Execute the statement and check if the update was successful
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
