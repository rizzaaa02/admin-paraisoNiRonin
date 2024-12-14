<?php
include 'backend/staff_schedules.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Scheduling</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>



    <style>
        .modal-body {
            max-height: 70vh;
            overflow-y: auto;
        }

        .btn-primary {
            position: relative;
            z-index: 1000;
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
                <h2>Staff Scheduling </h2><br>
                <div style="margin-bottom: -40px;">
                    <a href="add-staff-schedule.php" type="button" class="btn btn-primary">Add New Schedule</a>
                </div>
                <table id="staffTable" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Staff Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Assigned Services</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($data['schedules'])) {
                            foreach ($data['schedules'] as $row):
                        ?>
                                <tr>
                                    <td><?php echo $row['scheduleID']; ?></td>
                                    <td><?php echo $row['fullname']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $row['start_time'] . ' - ' . $row['end_time']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td><?php echo $row['assigned_services']; ?></td>
                                    <td>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editScheduleModal"
                                            data-schedule-id="<?php echo $row['scheduleID']; ?>"
                                            data-full-name="<?php echo $row['fullname']; ?>"
                                            data-date="<?php echo $row['date']; ?>"
                                            data-start-time="<?php echo $row['start_time']; ?>"
                                            data-end-time="<?php echo $row['end_time']; ?>"
                                            data-assigned-services="<?php echo $row['assigned_services']; ?>">
                                            Edit
                                        </button>
                                        <button class="deleteScheduleButton btn btn-danger"
                                            data-schedule-id="<?php echo $row['scheduleID']; ?>">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                        <?php
                            endforeach;
                        } else {
                            echo '<tr><td colspan="7" class="text-center">There is no schedule staff</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- Modal for editing schedule -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editScheduleModalLabel">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Edit Schedule Form -->
                    <form id="editScheduleForm">
                        <input type="hidden" id="editScheduleID">
                        <div class="mb-3">
                            <label for="editFullname" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editFullname" required disabled>
                        </div>
                        <div class="mb-3">
                            <label for="editDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="editDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStartTime" class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="editStartTime" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEndTime" class="form-label">End Time</label>
                            <input type="time" class="form-control" id="editEndTime" required>
                        </div>


                        <!-- display current Assigned Services -->
                        <div class="mb-3">
                            <label for="editAssignedServices" class="form-label">Assigned Services</label>
                            <select class="form-control" id="editAssignedServices" required>
                                <option value="">Select Service</option>
                                <?php
                                include '../connect.php';

                                $sql = "SELECT activity_id, activity_name FROM activities";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['activity_id'] . '" ' . $selected . '>' . $row['activity_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No services available</option>';
                                }

                                $conn->close();
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveScheduleChanges">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <!-- Bootstrap JS (make sure this is included before your closing </body> tag) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        //Data Table
        $(document).ready(function() {
            // Initialize the table with DataTables
            var table = $('#staffTable').DataTable({
                "lengthChange": false,
                "info": false,
                "language": {
                    "emptyTable": "There is no schedule staff available." // Custom message for empty table
                }
            });

            // Check if the error message exists in the PHP data
            <?php if (isset($data['error'])): ?>
                alert("<?php echo $data['error']; ?>"); // Display the error message (or you can use console.log)
            <?php endif; ?>

            // Open Edit Modal and Populate Fields
            $("#staffTable").on("click", ".btn-warning", function() {
                var scheduleID = $(this).data('schedule-id');
                var fullname = $(this).data('full-name');
                var date = $(this).data('date');
                var startTime = $(this).data('start-time');
                var endTime = $(this).data('end-time');
                var assignedservices = $(this).data('assigned-services');

                $("#editScheduleID").val(scheduleID);
                $("#editFullname").val(fullname);
                $("#editDate").val(date);
                $("#editStartTime").val(startTime);
                $("#editEndTime").val(endTime);
                $("#editAssignedServices").val(assignedservices);
            });
        });


        // Save Changes
        $("#saveScheduleChanges").click(function() {
            var scheduleID = $("#editScheduleID").val();
            var date = $("#editDate").val();
            var startTime = $("#editStartTime").val();
            var endTime = $("#editEndTime").val();


            var assignedservices = $("#editAssignedServices").val();


            if (!assignedservices) {
                assignedservices = '';
            }

            $.ajax({
                url: "backend/update-schedule.php",
                type: "POST",
                data: {
                    scheduleID: scheduleID,
                    date: date,
                    start_time: startTime,
                    end_time: endTime,
                    assignedservices: assignedservices
                },
                success: function(response) {
                    if (response.trim() === "success") {
                        alert("Schedule updated successfully!");
                        location.reload();
                    } else {
                        alert("Failed to update schedule. " + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert("AJAX error: " + error);
                }
            });
        });


        //delete
        document.querySelectorAll('.deleteScheduleButton').forEach(button => {
            button.addEventListener('click', function() {
                var scheduleId = this.getAttribute('data-schedule-id');

                if (confirm("Are you sure you want to delete this schedule?")) {
                    fetch('backend/delete_schedule.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'scheduleID=' + scheduleId
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Schedule deleted successfully.");
                                window.location.reload();
                            } else {
                                alert("Failed to delete schedule.");
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
</body>