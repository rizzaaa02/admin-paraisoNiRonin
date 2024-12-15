<?php
include 'backend/room_data.php';
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
            <h2>Room Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomModal">Add Room</button>

            <table id="activitiesTable" class="w-100 table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Price per night</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($rooms)) {
                        foreach ($rooms as $room):
                    ?>
                            <tr>
                                <td><?php echo $room['room_id']; ?></td>
                                <td><?php echo $room['room_type']; ?></td>
                                <td><?php echo $room['price_per_night']; ?></td>
                                <td><?php echo $room['status']; ?></td>
                                <td>
                                    <button class="btn btn-warning editButton">
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
        <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRoomModalLabel">Create Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="createRoomForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="room_type" class="form-label">Room Type</label>
                                <input type="text" class="form-control" id="room_type" name="room_type" required>
                            </div>
                            <div class="mb-3">
                                <label for="price_per_night" class="form-label">Price per Night</label>
                                <input type="number" class="form-control" id="price_per_night" name="price_per_night" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Room</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editRoomForm">
                        <div class="modal-body">
                            <input type="hidden" id="room_id" name="room_id">
                            <div class="mb-3">
                                <label for="room_type_edit" class="form-label">Room Type</label>
                                <input type="text" class="form-control" id="room_type_edit" name="room_type" required>
                            </div>
                            <div class="mb-3">
                                <label for="price_per_night_edit" class="form-label">Price per Night</label>
                                <input type="number" class="form-control" id="price_per_night_edit" name="price_per_night" required>
                            </div>
                            <div class="mb-3">
                                <label for="status_edit" class="form-label">Status</label>
                                <select class="form-select" id="status_edit" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

        <!-- jQuery -->

        <!-- DataTables JS -->

        <!-- Bootstrap JS (make sure this is included before your closing </body> tag) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
            $("#createRoomForm").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "backend/create_room.php", // Backend script to handle room creation
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        try {
                            console.log(response);
                            const res = JSON.parse(response);
                            if (res.success) {
                                $("#createRoomForm")[0].reset();
                                $("#createRoomModal").modal('hide');
                                location.reload();
                            } else {
                                alert(res.message);
                            }
                        } catch (e) {
                            alert("An unexpected error occurred.");
                        }
                    },
                    error: function() {
                        alert("An error occurred while creating the room.");
                    }
                });
            });

            $(".editButton").click(function() {
                const row = $(this).closest("tr");
                const room_id = row.find("td:eq(0)").text(); // Assuming room ID is in the first column
                const room_type = row.find("td:eq(1)").text();
                const price_per_night = row.find("td:eq(2)").text();
                const status = row.find("td:eq(3)").text();

                // Populate the edit modal fields
                $("#room_id").val(room_id);
                $("#room_type_edit").val(room_type);
                $("#price_per_night_edit").val(price_per_night);
                $("#status_edit").val(status);

                // Show the modal
                $("#editRoomModal").modal("show");
            });

            $("#editRoomForm").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: "backend/edit_room.php", // Backend script to handle room editing
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        try {
                            console.log(response);
                            const res = JSON.parse(response);
                            if (res.success) {
                                $("#editRoomForm")[0].reset();
                                $("#editRoomModal").modal('hide');
                                location.reload();
                            } else {
                                alert(res.message);
                            }
                        } catch (e) {
                            alert("An unexpected error occurred.");
                        }
                    },
                    error: function() {
                        alert("An error occurred while updating the room.");
                    }
                });
            });
        </script>
    </div>
</body>

</html>