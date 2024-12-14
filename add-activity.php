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
        .main-content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
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
                <h2 class="mb-4">Activity Management</h2>
                <div class="main-content">
                    <p class="mb-4 text-white p-2 text-center fs-4 font-weight-bold" style="background-color: gray; width: 100%; border-radius: 10px;">Add New Activity</p>
                    <form method="POST" action="backend/add-activity.php">
                        <div class="row">
                            <!-- Fullname Dropdown (Left Column) -->
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">Customer Name</label>
                                <select class="form-control" id="user_id" name="user_id" required>
                                    <option value="">Select Customer</option>
                                    <?php
                                    include 'connect.php'; // Assuming connect.php handles DB connection

                                    // Fetch users
                                    $sql = "SELECT user_id, full_name from users ";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['user_id'] . "'>" . $row['full_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Date (Right Column) -->
                            <div class="col-md-6 mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                        </div>

                        <!-- Activity Dropdown (Left Column) -->
                        <div class="col-md-6 mb-3">
                            <label for="activity_id" class="form-label">Activities</label>
                            <select class="form-control" id="activity_id" name="activity_id" required>
                                <option value="">Select Activities</option>
                                <?php
                                // Fetch activities
                                $serviceRoomSql = "SELECT activity_id, activity_name, price FROM activities";
                                $serviceRoomResult = $conn->query($serviceRoomSql);

                                if ($serviceRoomResult->num_rows > 0) {
                                    while ($row = $serviceRoomResult->fetch_assoc()) {
                                        // Display activity name with price
                                        echo "<option value='" . $row['activity_id'] . "'>" . $row['activity_name'] . " - PHP" . number_format($row['price'], 2) . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="activities.php" type="button" class="btn btn-secondary w-auto me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary w-auto">Save Schedule</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>