<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffID = $_POST['staffID'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    // Check if email is already in use by another user
    $checkQuery = "SELECT * FROM users WHERE email = ? AND user_id != (SELECT user_id FROM staff WHERE staffId = ?)";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("si", $email, $staffID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email is already in use."]);
    } else {
        // Update users table
        $updateUserQuery = "UPDATE users SET email = ?, phone = ? WHERE user_id = (SELECT user_id FROM staff WHERE staffId = ?)";
        $stmt = $conn->prepare($updateUserQuery);
        $stmt->bind_param("ssi", $email, $phone, $staffID);

        if ($stmt->execute()) {
            // Update staff table
            $updateStaffQuery = "UPDATE staff SET fullname = ?, role = ?, phone = ? WHERE staffId = ?";
            $stmt = $conn->prepare($updateStaffQuery);
            $stmt->bind_param("sssi", $fullname, $role, $phone, $staffID);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Staff updated successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update staff details."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update user details."]);
        }
    }

    $stmt->close();
    $conn->close();
}
?>
