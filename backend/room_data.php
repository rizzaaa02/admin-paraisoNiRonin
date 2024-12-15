<?php
include 'connect.php';

function getRooms($conn)
{
    $activitiesQuery = $conn->query("select * from rooms");


    return $activitiesQuery->fetch_all(MYSQLI_ASSOC);;
}

$rooms = getRooms($conn);
