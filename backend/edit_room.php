<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $room_type = $_POST['room_type'];
    $price_per_night = $_POST['price_per_night'];
    $status = $_POST['status'];

    // Validate inputs
    if (empty($room_id) || empty($room_type) || empty($price_per_night) || empty($status)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if (!is_numeric($price_per_night) || $price_per_night <= 0) {
        echo json_encode(["success" => false, "message" => "Price must be a positive number."]);
        exit;
    }

    // Update room details in the database
    $query = "UPDATE rooms SET room_type = ?, price_per_night = ?, status = ? WHERE room_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdsi", $room_type, $price_per_night, $status, $room_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Room updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
