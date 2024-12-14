<?php
include '../connect.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['user_id'] ?? null;
    $fullName = $_POST['full_name'] ?? null;
    $email = $_POST['email'] ?? null;
    $phone = $_POST['phone'] ?? null;

    // Debugging
    error_log("Received data: " . json_encode($_POST));

    if (!$userID || !$fullName || !$email || !$phone) {
        echo "error: Missing parameters. Received: " . json_encode($_POST);
        exit;
    }

    // Database update logic
    $query = "UPDATE users SET full_name = ?, email = ?, phone = ? WHERE user_id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo "error: " . $conn->error;
        exit;
    }

    $stmt->bind_param("sssi", $fullName, $email, $phone, $userID);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "error: Invalid request method.";
}
