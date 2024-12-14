<?php
include 'backend/booking_data.php'; // Include the backend PHP that fetches the booking data
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <div class="col-md-3">
                <?php include 'includes/sidebar.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 p-4">
                <h2>Booking Management</h2>


                <table id="bookingsTable" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Customer Name</th>
                            <th>Room Type</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($data['reservation'])) {
                            foreach ($data['reservation'] as $row):
                        ?>
                                <tr>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['room_type']; ?></td>
                                    <td><?php echo $row['check_in_date']; ?></td>
                                    <td><?php echo $row['check_out_date']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                </tr>
                        <?php
                            endforeach;
                        } else {
                            echo '<tr><td colspan="6">No bookings found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bookingsTable').DataTable({
                "lengthChange": false,
                "info": false
            });

        });
    </script>
</body>

</html>




$(document).ready(function() {
$('#activitiesTable').DataTable({
"lengthChange": false,
"info": false,
"searching": false,
"paging": false
});
});

$(document).ready(function() {
$('#roomsTable').DataTable({
"lengthChange": false,
"info": false,
"searching": false,
"paging": false
});
});