<?php
include 'connect.php';

function getStaffs($conn)
{
    $activitiesQuery = $conn->query("select staff.*,users.email from staff join users on users.user_id = staff.user_id");


    return $activitiesQuery->fetch_all(MYSQLI_ASSOC);;
}

$staffs = getStaffs($conn);
