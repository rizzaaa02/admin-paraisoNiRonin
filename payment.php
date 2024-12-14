<?php
include 'backend/payment-tracker.php';
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
    <style>
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .table-container {
            text-align: center;
            background-color: #f0f0f0;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .table-container h3 {
            margin-bottom: 20px;
        }

        .form-control {
            margin-bottom: 20px;
        }

        .btn-cancel {
            background-color: #6c757d;
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }
    </style>
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
                <h2>Payment Tracking</h2><br>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">Select Customer</option>
                            <?php
                            include 'connect.php';

                            // Fetch users
                            $sql = "SELECT user_id, full_name FROM users";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['user_id'] . "'>" . $row['full_name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Adjusted the col-md-3 width and added a custom margin for better positioning -->
                    <div class="col-md-5" style="margin-left: auto;">
                        <div class="table-container" id="overallTotal">
                            <!-- Overall Total content goes here -->
                        </div>
                    </div>
                </div>



                <!-- Activities Table -->
                <div class="table-container">
                    <h3>Activity Table</h3>
                    <table id="activitiesTable" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Activities</th>
                                <th>Scheduled Date</th>
                                <th>Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalActivityPrice = 0;
                            if (!empty($data['activities'])) {
                                foreach ($data['activities'] as $row) {
                                    echo "<tr data-user-id='" . $row['user_id'] . "'>
                        <td>" . $row['user_id'] . "</td>
                        <td>" . $row['activity_name'] . "</td>
                        <td>" . $row['scheduled_date'] . "</td>
                        <td>" . $row['actPrice'] . "</td>
                        <td>" . $row['actTotalPrice'] . "</td>
                    </tr>";
                                    $totalActivityPrice += $row['actTotalPrice'];
                                }
                            } else {
                                echo '<tr><td colspan="5">No activities found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <div id="activityTotal"></div>
                </div>

                <!-- Rooms Table -->
                <div class="table-container">
                    <h3>Rooms Table</h3>
                    <table id="roomsTable" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Room Type</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalRoomPrice = 0;
                            if (!empty($roomData['rooms'])) {
                                foreach ($roomData['rooms'] as $row) {
                                    echo "<tr data-user-id='" . $row['user_id'] . "'>
                        <td>" . $row['user_id'] . "</td>
                        <td>" . $row['room_type'] . "</td>
                        <td>" . $row['check_in_date'] . "</td>
                        <td>" . $row['check_out_date'] . "</td>
                        <td>" . $row['roomAmount'] . "</td>
                        <td>" . $row['roomTotalAmount'] . "</td>
                    </tr>";
                                    $totalRoomPrice += $row['roomTotalAmount'];
                                }
                            } else {
                                echo '<tr><td colspan="6">No bookings found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <div id="roomTotal"></div>
                </div>

            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log("Document ready!");

            // Function to calculate the total for each table and the overall total
            function calculateTotal() {
                let activityTotal = 0;
                let roomTotal = 0;

                console.log("Calculating totals...");

                // Calculate the activity total for visible rows
                $('#activitiesTable tbody tr').each(function() {
                    var userId = $(this).data('user-id');
                    console.log("User ID in Activity row: ", userId);

                    if (userId == selectedUserId || selectedUserId === '') {
                        var total = parseFloat($(this).find('td:nth-child(5)').text()) || 0;
                        console.log("Activity Total: ", total);
                        activityTotal += total;
                    }
                });

                // Calculate the room total for visible rows
                $('#roomsTable tbody tr').each(function() {
                    var userId = $(this).data('user-id');
                    console.log("User ID in Room row: ", userId);

                    if (userId == selectedUserId || selectedUserId === '') {
                        var total = parseFloat($(this).find('td:nth-child(6)').text()) || 0;
                        console.log("Room Total: ", total);
                        roomTotal += total;
                    }
                });

                // Update the totals on the page
                $('#activityTotal').text("Total Activity Price: " + activityTotal.toFixed(2));
                $('#roomTotal').text("Total Room Price: " + roomTotal.toFixed(2));

                // Display the overall total
                var overallTotal = activityTotal + roomTotal;
                $('#overallTotal').text("Overall Total: " + overallTotal.toFixed(2));
            }

            // Initially, show all rows and calculate the total for all rows
            var selectedUserId = ''; // For showing all users initially
            $('#activitiesTable tbody tr').show();
            $('#roomsTable tbody tr').show();
            calculateTotal();

            // Filter the tables based on the selected user_id and calculate totals
            $('#user_id').on('change', function() {
                selectedUserId = $(this).val(); // Get the selected user_id

                // Filter Activities Table
                $('#activitiesTable tbody tr').each(function() {
                    var userId = $(this).data('user-id');
                    if (selectedUserId === '' || userId == selectedUserId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Filter Rooms Table
                $('#roomsTable tbody tr').each(function() {
                    var userId = $(this).data('user-id');
                    if (selectedUserId === '' || userId == selectedUserId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Calculate and display totals
                calculateTotal();
            });
        });
    </script>

</body>

</html>