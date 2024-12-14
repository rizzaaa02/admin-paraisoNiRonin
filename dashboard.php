<?php
include 'backend/dashboard_data.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <h2>Dashboard Overview</h2>

                <div class="row">
                    <!-- Revenue Chart -->
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <h5>Revenue</h5>
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    <!-- Customer Chart -->
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <h5>Total Customers</h5>
                            <canvas id="customerChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Room Type Chart -->
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <h5>Users per Room Type</h5>
                            <canvas id="roomTypeChart"></canvas>
                        </div>
                    </div>
                    <!-- Reservation Status -->
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <h5>Reservation Status</h5>
                            <ul>
                                <?php foreach ($data['statuses'] as $status): ?>
                                    <li><?php echo ucfirst($status['status']) . ': ' . $status['count']; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Occupancy Rate -->
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <h5>Occupied Rooms</h5>
                            <p><?php echo $data['occupied']; ?> currently occupied rooms</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart data preparation
        const revenueData = {
            labels: [<?php foreach ($data['revenue'] as $row) {
                            echo '"' . $row['month'] . '/' . $row['year'] . '",';
                        } ?>],
            datasets: [{
                label: 'Revenue',
                data: [<?php foreach ($data['revenue'] as $row) {
                            echo $row['total_revenue'] . ',';
                        } ?>],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        const customerData = {
            labels: [<?php foreach ($data['customers'] as $row) {
                            echo '"' . $row['month'] . '/' . $row['year'] . '",';
                        } ?>],
            datasets: [{
                label: 'Total Customers',
                data: [<?php foreach ($data['customers'] as $row) {
                            echo $row['total_customers'] . ',';
                        } ?>],
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        };

        const roomTypeData = {
            labels: [<?php foreach ($data['roomTypes'] as $row) {
                            echo '"' . $row['room_type'] . '",';
                        } ?>],
            datasets: [{
                label: 'Users per Room Type',
                data: [<?php foreach ($data['roomTypes'] as $row) {
                            echo $row['total_users'] . ',';
                        } ?>],
                backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                borderWidth: 1
            }]
        };

        // Render charts
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: revenueData
        });
        new Chart(document.getElementById('customerChart'), {
            type: 'line',
            data: customerData
        });
        new Chart(document.getElementById('roomTypeChart'), {
            type: 'bar',
            data: roomTypeData
        });
    </script>
</body>

</html>