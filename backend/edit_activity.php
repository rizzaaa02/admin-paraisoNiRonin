<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_id = $_POST['activity_id'];
    $activity_name = $_POST['activity_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Validate inputs
    if (empty($activity_id) || empty($activity_name) || empty($description) || empty($price)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (!is_numeric($price) || $price < 0) {
        echo json_encode(["success" => false, "message" => "Price must be a positive number."]);
        exit;
    }

    // Update the activity in the database
    $query = "UPDATE activities SET activity_name = ?, description = ?, price = ? WHERE activity_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdi", $activity_name, $description, $price, $activity_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Activity updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
