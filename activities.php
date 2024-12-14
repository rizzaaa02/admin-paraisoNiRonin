<?php
include 'backend/activities_data.php';
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
                <h2>Activity Management</h2><br>
                <div style="margin-bottom: -40px;">
                    <a href="add-activity.php" type="button" class="btn btn-primary">Add New Schedule</a>
                </div>
                <table id="activitiesTable" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Activities</th>
                            <th>Scheduler Date</th>
                            <th>Status</th>
                            <th>Price</th>
                            <td>Total Price</td>
                            <td>Actions</td>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($data['activities'])) {
                            foreach ($data['activities'] as $row):
                        ?>
                                <tr>
                                    <td><?php echo $row['activity_reservation_id']; ?></td>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['activity_name']; ?></td>
                                    <td><?php echo $row['scheduled_date']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td><?php echo $row['total_price']; ?></td>
                                    <td>
                                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editScheduleModal"
                                            data-activity_reservation_id="<?php echo $row['activity_reservation_id']; ?>"
                                            data-activity_name="<?php echo $row['activity_name']; ?>"
                                            data-scheduled_date="<?php echo $row['scheduled_date']; ?>"
                                            data-price="<?php echo $row['price']; ?>">
                                            Edit
                                        </button>
                                        <button class="deleteButton btn btn-danger"
                                            data-activity_reservation_id="<?php echo $row['activity_reservation_id']; ?>">
                                            Delete
                                        </button>

                                    </td>
                                </tr>
                        <?php
                            endforeach;
                        } else {
                            echo '<tr><td colspan="6">No activities found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal for editing -->
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
                            <label for="editAssignedServices" class="form-label">Assigned Services</label>
                            <select class="form-control" id="editAssignedServices" required>
                                <option value="">Select Service</option>
                                <?php
                                include '../connect.php';

                                $sql = "SELECT activity_id, activity_name FROM activities";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['activity_id'] . '">' . $row['activity_name'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">No services available</option>';
                                }

                                $conn->close();
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="editDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPrice" class="form-label">Price</label>
                            <input type="text" class="form-control" id="editPrice" required disabled>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="saveScheduleChanges" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JS (make sure this is included before your closing </body> tag) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#activitiesTable').DataTable({
                "lengthChange": false,
                "info": false
            });

            $(".btn-warning").click(function() {
                var activityReservationID = $(this).data('activity_reservation_id');
                var activityName = $(this).data('activity_name');
                var scheduledDate = $(this).data('scheduled_date');
                var price = $(this).data('price');

                $("#editScheduleID").val(activityReservationID);
                $("#editAssignedServices").val(activityName); // Assuming 'Assigned Services' refers to activity name
                $("#editDate").val(scheduledDate);
                $("#editPrice").val(price);
            });
        });


        $("#saveScheduleChanges").click(function() {
            var activityReservationID = $("#editScheduleID").val(); // Use correct ID field
            var activityName = $("#editAssignedServices").val(); // This now holds the activity ID
            var date = $("#editDate").val();

            $.ajax({
                url: "backend/update-activity.php",
                type: "POST",
                data: {
                    activity_reservation_id: activityReservationID, // Pass the reservation ID
                    activityName: activityName, // activityName now corresponds to the activity ID
                    date: date,
                },
                success: function(response) {
                    if (response.trim() === "success") {
                        alert("Activity updated successfully!");
                        location.reload();
                    } else {
                        alert("Failed to update activity. " + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert("AJAX error: " + error);
                }
            });
        });

        //delete
        document.querySelectorAll('.deleteButton').forEach(button => {
            button.addEventListener('click', function() {
                var activity_reservation_id = this.getAttribute('data-activity_reservation_id'); // Get the activity reservation ID

                if (confirm("Are you sure you want to delete this reservation?")) {
                    fetch('backend/delete-activity.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'activity_reservation_id=' + activity_reservation_id // Pass the correct ID in the body
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Reservation deleted successfully.");
                                window.location.reload(); // Reload the page to reflect the changes
                            } else {
                                alert("Failed to delete reservation: " + data.error);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>
</body>

</html>