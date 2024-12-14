<?php
include 'connect.php';

function getActivityData($conn)
{
    $data = [];
    $customerQuery = $conn->query("
        SELECT 
        activity_reservations.activity_reservation_id,
        GROUP_CONCAT(activity_reservations.user_id ORDER BY activity_reservations.user_id SEPARATOR ',') AS user_id,
        activities.activity_name,
        activity_reservations.scheduled_date,
        SUM(activities.price) AS actTotalPrice,
        activities.price AS actPrice
        FROM activity_reservations
        JOIN activities ON activity_reservations.activity_id = activities.activity_id
        GROUP BY activity_reservations.activity_reservation_id, activities.activity_name, activity_reservations.scheduled_date, activities.price;
    ");

    // Fetch results and assign to $data
    if ($customerQuery) {
        $data['activities'] = $customerQuery->fetch_all(MYSQLI_ASSOC);
    } else {
        $data['activities'] = [];
    }
    return $data;
}

function getRoomData($conn)
{
    $data = [];
    $roomQuery = $conn->query("
        SELECT
        reservations.reservation_id,
        GROUP_CONCAT(reservations.user_id ORDER BY reservations.user_id SEPARATOR ',') AS user_id,
        rooms.room_type,
        reservations.check_in_date,
        reservations.check_out_date,
        SUM(reservations.total_amount) AS roomTotalAmount,
        reservations.total_amount AS roomAmount
        FROM reservations
        JOIN rooms ON reservations.room_id = rooms.room_id
        GROUP BY reservations.reservation_id, rooms.room_type, reservations.check_in_date, reservations.check_out_date, reservations.total_amount;
    ");

    // Fetch results and assign to $data
    if ($roomQuery) {
        $data['rooms'] = $roomQuery->fetch_all(MYSQLI_ASSOC);
    } else {
        $data['rooms'] = [];
    }
    return $data;
}

$data = getActivityData($conn);
$roomData = getRoomData($conn);
