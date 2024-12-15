<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_type = $_POST['room_type'];
    $price_per_night = $_POST['price_per_night'];
    $status = $_POST['status'];

    // Validate inputs
    if (empty($room_type) || empty($price_per_night) || empty($status)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (!is_numeric($price_per_night) || $price_per_night <= 0) {
        echo json_encode(["success" => false, "message" => "Price must be a positive number."]);
        exit;
    }

    // Insert room into the database
    $query = "INSERT INTO rooms (room_type, price_per_night, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sds", $room_type, $price_per_night, $status);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Room created successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
