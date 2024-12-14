<?php
include 'connect.php';

function getUserData($conn)
{
    $data = [];
    $customerQuery = $conn->query("

    SELECT
    users.user_id, 
    users.full_name, 
    users.email, 
    users.phone, 
    reservations.reservation_id,
    REPLACE(GROUP_CONCAT(rooms.room_type), ',', '<br>') AS room_types,  
    REPLACE(GROUP_CONCAT(reservations.check_in_date), ',', '<br>') AS check_in_dates,  
    REPLACE(GROUP_CONCAT(reservations.check_out_date), ',', '<br>') AS check_out_dates, 
    REPLACE(GROUP_CONCAT(reservations.status), ',', '<br>') AS status, 
    SUM(reservations.total_amount) AS total_amounts,
    REPLACE(GROUP_CONCAT(reservations.total_amount), ',', '<br>') AS amount
    FROM reservations
    JOIN users ON reservations.user_id = users.user_id
    JOIN rooms ON reservations.room_id = rooms.room_id
    GROUP BY users.user_id;

      

    ");

    // Fetch results and assign to $data
    if ($customerQuery) {
        $data['customers'] = $customerQuery->fetch_all(MYSQLI_ASSOC);
    } else {
        $data['customers'] = [];
    }
    return $data;
}

$data = getUserData($conn);
