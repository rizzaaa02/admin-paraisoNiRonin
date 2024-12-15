<?php
include 'connect.php';

function getUsers($conn)
{
    $activitiesQuery = $conn->query("select * from users where role = 'user'");


    return $activitiesQuery->fetch_all(MYSQLI_ASSOC);;
}

$users = getUsers($conn);
