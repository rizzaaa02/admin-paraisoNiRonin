<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activity_name = $_POST['activity_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Validate inputs
    if (empty($activity_name) || empty($description) || empty($price)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (!is_numeric($price) || $price < 0) {
        echo json_encode(["success" => false, "message" => "Price must be a positive number."]);
        exit;
    }

    // Insert the activity into the database
    $query = "INSERT INTO activities (activity_name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssd", $activity_name, $description, $price);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Activity added successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
