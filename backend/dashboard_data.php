<?php
include 'connect.php'; // Database connection

// Fetch data for dashboard
function getDashboardData($conn)
{
    $data = [];

    // Revenue Data
    $revenueQuery = $conn->query("
        SELECT 
            SUM(`reservations`.`total_amount`) as total_revenue, 
            MONTH(`reservations`.`check_in_date`) as month, 
            YEAR(`reservations`.`check_in_date`) as year
        FROM `reservations`
        GROUP BY year, month
    ");
    $data['revenue'] = $revenueQuery->fetch_all(MYSQLI_ASSOC);

    // Total Customers
    $customerQuery = $conn->query("
        SELECT 
            COUNT(*) as total_customers, 
            MONTH(`reservations`.`check_in_date`) as month, 
            YEAR(`reservations`.`check_in_date`) as year
        FROM `reservations`
        GROUP BY year, month
    ");
    $data['customers'] = $customerQuery->fetch_all(MYSQLI_ASSOC);

    // Users per Room Type
    $roomTypeQuery = $conn->query("
        SELECT 
            `rooms`.`room_type`, 
            COUNT(`reservations`.`reservation_id`) as total_users
        FROM `reservations`
        JOIN `rooms` ON `reservations`.`room_id` = `rooms`.`room_id`
        GROUP BY `rooms`.`room_type`
    ");
    $data['roomTypes'] = $roomTypeQuery->fetch_all(MYSQLI_ASSOC);

    // Reservation Status
    $statusQuery = $conn->query("
        SELECT 
            `status`, 
            COUNT(`reservation_id`) as count 
        FROM `reservations`
        GROUP BY `status`
    ");
    $data['statuses'] = $statusQuery->fetch_all(MYSQLI_ASSOC);

    // Occupied Rooms
    $occupiedQuery = $conn->query("
        SELECT 
            COUNT(*) as occupied 
        FROM `reservations` 
        WHERE CURRENT_DATE BETWEEN `check_in_date` AND `check_out_date`
    ");
    $data['occupied'] = $occupiedQuery->fetch_assoc()['occupied'];

    return $data;
}

$data = getDashboardData($conn);
