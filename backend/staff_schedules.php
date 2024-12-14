<?php
include 'connect.php';

function getStaffSchedules($conn)
{
    $data = [];

    try {
        $query = "
        SELECT
            staff_schedules.scheduleID,
            staff.fullname,
            staff_schedules.date,
            staff_schedules.start_time,
            staff_schedules.end_time,
            staff_schedules.status,
            GROUP_CONCAT(activities.activity_name) AS assigned_services
            FROM staff_schedules
            LEFT JOIN staff ON staff_schedules.staffID = staff.staffID
            LEFT JOIN staff_assignments ON staff_schedules.scheduleID = staff_assignments.scheduleID
            LEFT JOIN activities 
                ON staff_assignments.activity_id = activities.activity_id
            WHERE DATE(staff_schedules.date) = CURDATE() 
            GROUP BY staff_schedules.scheduleID
            ORDER BY staff_schedules.date ASC;
        ";

        $result = $conn->query($query);

        if ($result) {
            $schedules = $result->fetch_all(MYSQLI_ASSOC);

            foreach ($schedules as &$schedule) {
                if (empty($schedule['assigned_services'])) {
                    $schedule['status'] = 'Available';
                } else {
                    $schedule['status'] = 'Assigned';
                }
            }

            if (count($schedules) > 0) {
                $data['schedules'] = $schedules;
            } else {
                $data['schedules'] = []; // Return empty array if no schedules
                $data['error'] = "No staff schedules found.";
            }
        } else {
            $data['schedules'] = []; // Ensure empty array if query fails
            $data['error'] = "Failed to fetch staff schedules.";
        }
    } catch (Exception $e) {
        $data['schedules'] = []; // Ensure empty array in case of exception
        $data['error'] = "An error occurred: " . $e->getMessage();
    }

    return $data;
}

$data = getStaffSchedules($conn);
