<?php
include 'backend/staff_data.php';
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
            <h2>Staff Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">Add Staff</button>
            <table id="activitiesTable" class="w-100 table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Full name</th>
                        <th>Email</th>

                        <th>Phone</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($staffs)) {
                        foreach ($staffs as $staff):
                    ?>
                            <tr>
                                <td><?php echo $staff['staffID']; ?></td>
                                <td><?php echo $staff['fullname']; ?></td>
                                <td><?php echo $staff['email']; ?></td>
                                <td><?php echo $staff['phone']; ?></td>
                                <td><?php echo $staff['role']; ?></td>
                                <td>
                                    <button
                                        class="btn btn-warning editButton"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editStaffModal"
                                        data-id="<?php echo $staff['staffID']; ?>"
                                        data-fullname="<?php echo $staff['fullname']; ?>"
                                        data-email="<?php echo $staff['email']; ?>"
                                        data-phone="<?php echo $staff['phone']; ?>"
                                        data-role="<?php echo $staff['role']; ?>">
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

        <!-- Add Staff Modal -->
        <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addStaffModalLabel">Add Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addStaffForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="Receptionist">Manager</option>
                                    <option value="Receptionist">Receptionist</option>
                                    <option value="Cleaner">Cleaner</option>
                                    <option value="Technician">Technician</option>
                                    <option value="Nurse">Nurse</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Staff</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Staff Modal -->
        <div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editStaffModalLabel">Edit Staff</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editStaffForm">
                        <div class="modal-body">
                            <input type="hidden" id="editStaffID" name="staffID">
                            <div class="mb-3">
                                <label for="editFullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="editFullname" name="fullname" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="editPhone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="editRole" class="form-label">Role</label>
                                <select class="form-select" id="editRole" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Receptionist">Receptionist</option>
                                    <option value="Cleaner">Cleaner</option>
                                    <option value="Technician">Technician</option>
                                    <option value="Nurse">Nurse</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">Update Staff</button>
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
            $("#addStaffForm").submit(function(e) {
                e.preventDefault();

                const password = $("#password").val();
                const confirmPassword = $("#confirmPassword").val();

                if (password !== confirmPassword) {
                    alert("Passwords do not match.");
                    return;
                }

                $.ajax({
                    url: "backend/add_staff.php",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.success) {
                                alert(res.message);
                                $("#addStaffForm")[0].reset();
                                $("#addStaffModal").modal('hide');
                                location.reload();
                            } else {
                                alert(res.message);
                            }
                        } catch (e) {
                            alert("An unexpected error occurred.");
                        }
                    },
                    error: function() {
                        alert("An error occurred while adding the staff.");
                    }
                });
            });

            $(document).ready(function() {
                // Populate the Edit Staff Modal with selected staff data
                $(".editButton").click(function() {
                    const staffID = $(this).data("id");
                    const fullname = $(this).data("fullname");
                    const email = $(this).data("email");
                    const phone = $(this).data("phone");
                    const role = $(this).data("role");

                    $("#editStaffID").val(staffID);
                    $("#editFullname").val(fullname);
                    $("#editEmail").val(email);
                    $("#editPhone").val(phone);
                    $("#editRole").val(role);
                });

                // Handle form submission using AJAX
                $("#editStaffForm").submit(function(e) {
                    e.preventDefault();

                    $.ajax({
                        url: "backend/edit_staff.php", // Backend script for updating staff
                        method: "POST",
                        data: $(this).serialize(),
                        success: function(response) {
                            try {
                                console.log(response);
                                const res = JSON.parse(response);
                                if (res.success) {
                                    alert(res.message);
                                    $("#editStaffForm")[0].reset();
                                    $("#editStaffModal").modal('hide');
                                    location.reload();
                                } else {
                                    alert(res.message);
                                }
                            } catch (e) {
                                alert("An unexpected error occurred.");
                            }
                        },
                        error: function() {
                            alert("An error occurred while updating the staff.");
                        }
                    });
                });
            });
        </script>
    </div>
</body>

</html>