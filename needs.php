<?php
include 'connect.php'; // Include your database connection

// Fetch bookings
$bookings = $conn->query("
    SELECT 
        `reservations`.`reservation_id`, 
        `users`.`full_name`, 
        `rooms`.`room_type`, 
        `reservations`.`check_in_date`, 
        `reservations`.`check_out_date`, 
        `reservations`.`status` 
    FROM `reservations`
    JOIN `users` ON `reservations`.`user_id` = `users`.`user_id`
    JOIN `rooms` ON `reservations`.`room_id` = `rooms`.`room_id`
");

if (!$bookings) {
    die("Error in query: " . $conn->error); // Debugging
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Custom CSS -->
</head>

<body>
    <div class="sidebar">
        <h4 class="text-center py-3">Staff Dashboard</h4>
        <a href="#">Dashboard</a>
        <a href="#">Booking Management</a>
        <a href="#">Customer Management</a>
        <a href="#">Staff Scheduling</a>
        <a href="#">Payment Tracking</a>
        <a href="#">Service Management</a>
        <a href="#">Event Management</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h2>Bookings</h2>
        <table id="bookingsTable" class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Room Type</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['reservation_id']; ?></td>
                        <td><?php echo $row['full_name']; ?></td>
                        <td><?php echo $row['room_type']; ?></td>
                        <td><?php echo $row['check_in_date']; ?></td>
                        <td><?php echo $row['check_out_date']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <h2>Manage Rooms</h2>
        <a href="add_room.php" class="btn btn-primary me-2">Add Room</a>
        <a href="view_rooms.php" class="btn btn-secondary">View Rooms</a>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bookingsTable').DataTable();
        });
    </script>
</body>

</html>