<?php
include 'connect.php';

function getActivitiesData($conn)
{
    $data = [];
    $activitiesQuery = $conn->query("

    SELECT
    activity_reservations.activity_reservation_id,
    users.full_name, 
    activities.activity_name,
    activity_reservations.scheduled_date , 
    activity_reservations.status,
    SUM(activities.price) AS total_price,
    activities.price
    FROM activity_reservations
    JOIN users ON activity_reservations.user_id = users.user_id
    JOIN activities ON activity_reservations.activity_id = activities.activity_id
    GROUP BY activity_reservations.activity_id;

    ");

    // Fetch results and assign to $data
    if ($activitiesQuery) {
        $data['activities'] = $activitiesQuery->fetch_all(MYSQLI_ASSOC);
    } else {
        $data['activities'] = [];
    }
    return $data;
}

$data = getActivitiesData($conn);
