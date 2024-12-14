<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<div class="sidebar bg-dark text-white p-3">
    <div class="sidebar-header text-center mb-4">
        <h4 class="fw-bold">Staff Dashboard</h4>
    </div>
    <nav class="nav flex-column">
        <a href="dashboard.php" class="nav-link text-white">
            <i class="bi bi-house-door me-2"></i> Overview
        </a>

        <!-- Grouped section for Bookings and Activities with visible title -->
        <div class="nav-item">
            <h6 class="text-white ps-3 pt-3  p-2 d-flex align-items-center">
                <i class="bi bi-calendar-event me-2"></i> Reservations
            </h6>
            <a href="bookings.php" class="nav-link text-white ms-4">
                <i class="bi bi-calendar-check me-2"></i> Bookings
            </a>
            <a href="activities.php" class="nav-link text-white ms-4">
                <i class="bi bi-activity me-2"></i> Activities
            </a>
        </div>
        <a href="payment.php" class="nav-link text-white">
            <i class="bi bi-credit-card-fill me-2"></i> Payment Tracking
        </a>
        <a href="staff-scheduling.php" class="nav-link text-white">
            <i class="bi bi-person-lines-fill me-2"></i> Staff Scheduling
        </a>
        <a href="logout.php" class="nav-link text-white">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </nav>



</div>