<?php
include 'connect.php';

function getActivity($conn)
{
    $activitiesQuery = $conn->query("select * from activities");


    return $activitiesQuery->fetch_all(MYSQLI_ASSOC);;
}

$activities = getActivity($conn);
