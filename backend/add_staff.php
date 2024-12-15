<?php
include '../connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    // Check if email already exists
    $checkQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email is already in use."]);
    } else {
        // Insert into users table
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userQuery = "INSERT INTO users (email, password, role, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($userQuery);
        $staff = 'staff';
        $stmt->bind_param("ssss", $email, $hashedPassword,    $staff, $phone);

        if ($stmt->execute()) {
            $user_id = $conn->insert_id;

            // Insert into staff table
            $staffQuery = "INSERT INTO staff (fullname, role, phone, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($staffQuery);
            $stmt->bind_param("sssi", $fullname, $role, $phone, $user_id);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Staff added successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to add staff details."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add user."]);
        }
    }

    $stmt->close();
    $conn->close();
}
