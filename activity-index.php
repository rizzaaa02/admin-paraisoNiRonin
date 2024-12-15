<?php
include 'backend/activity_data.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

</head>

<body>
    <?php include 'includes/sidebar.php'; ?>
    <div style="margin-left: 440px;">
        <div class="container-fluid">
            <h2>Activity Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createActivityModal">Add Activity</button>

            <table id="activitiesTable" class="w-100 table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Activity name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($activities)) {
                        foreach ($activities as $activity):
                    ?>
                            <tr>
                                <td><?php echo $activity['activity_id']; ?></td>
                                <td><?php echo $activity['activity_name']; ?></td>
                                <td><?php echo $activity['description']; ?></td>
                                <td><?php echo $activity['price']; ?></td>
                                <td>
                                    <button class="btn btn-warning">
                                        Edit
                                    </button>
                                    <button class="deleteButton btn btn-danger">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                    <?php
                        endforeach;
                    } else {
                        echo '<tr><td colspan="6">No staffs found</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="createActivityModal" tabindex="-1" aria-labelledby="createActivityModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createActivityModalLabel">Add Activity</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createActivityForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="activity_name" class="form-label">Activity Name</label>
                                <input type="text" class="form-control" id="activity_name" name="activity_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="price" name="price" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Add Activity</button>
                        </div>
                    </form>
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
            });
        </script>
        <script>
            // Handle form submission using AJAX
            $("#createActivityForm").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "backend/create_activity.php", // Backend script to handle activity creation
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.success) {

                                $("#createActivityForm")[0].reset();
                                $("#createActivityModal").modal('hide');
                                location.reload();
                            } else {
                                alert(res.message);
                            }
                        } catch (e) {
                            alert("An unexpected error occurred.");
                        }
                    },
                    error: function() {
                        alert("An error occurred while adding the activity.");
                    }
                });
            });
        </script>
        <!-- Edit Activity Modal -->
        <div class="modal fade" id="editActivityModal" tabindex="-1" aria-labelledby="editActivityModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editActivityModalLabel">Edit Activity</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editActivityForm">
                        <div class="modal-body">
                            <input type="hidden" id="edit_activity_id" name="activity_id">
                            <div class="mb-3">
                                <label for="edit_activity_name" class="form-label">Activity Name</label>
                                <input type="text" class="form-control" id="edit_activity_name" name="activity_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Description</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="edit_price" class="form-label">Price</label>
                                <input type="number" class="form-control" id="edit_price" name="price" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Handle edit button click
                $(".btn-warning").click(function() {
                    const row = $(this).closest("tr");
                    const activityId = row.find("td:eq(0)").text().trim();
                    const activityName = row.find("td:eq(1)").text().trim();
                    const description = row.find("td:eq(2)").text().trim();
                    const price = row.find("td:eq(3)").text().trim();

                    // Populate the edit modal fields
                    $("#edit_activity_id").val(activityId);
                    $("#edit_activity_name").val(activityName);
                    $("#edit_description").val(description);
                    $("#edit_price").val(price);

                    // Show the modal
                    $("#editActivityModal").modal("show");
                });

                // Handle form submission for editing
                $("#editActivityForm").submit(function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: "backend/edit_activity.php", // Backend script to handle activity editing
                        method: "POST",
                        data: $(this).serialize(),
                        success: function(response) {
                            try {
                                const res = JSON.parse(response);
                                if (res.success) {
                                    $("#editActivityForm")[0].reset();
                                    $("#editActivityModal").modal('hide');
                                    location.reload();
                                } else {
                                    alert(res.message);
                                }
                            } catch (e) {
                                alert("An unexpected error occurred.");
                            }
                        },
                        error: function() {
                            alert("An error occurred while editing the activity.");
                        }
                    });
                });
            });
        </script>

    </div>
</body>

</html>