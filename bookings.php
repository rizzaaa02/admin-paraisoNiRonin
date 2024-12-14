<?php
include 'backend/customer_data.php';
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
            /* Restrict height to 70% of the viewport */
            overflow-y: auto;
            /* Enable vertical scrolling */
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
                <h2>Booking Management </h2>


                <table id="bookingsTable" class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Email Address</th>
                            <th>Phone Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($data['customers'])) {
                            foreach ($data['customers'] as $row):
                        ?>
                                <tr>
                                    <td><?php echo $row['reservation_id']; ?></td>
                                    <td><?php echo $row['full_name']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone']; ?></td>
                                    <td>
                                        <button class="btn btn-primary view-bookings"
                                            data-room-type="<?php echo $row['room_types']; ?>"
                                            data-check-in-date="<?php echo $row['check_in_dates']; ?>"
                                            data-check-out-date="<?php echo $row['check_out_dates']; ?>"
                                            data-status="<?php echo $row['status']; ?>"
                                            data-total-amount="<?php echo $row['total_amounts']; ?>"
                                            data-amount="<?php echo $row['amount']; ?>">
                                            View
                                        </button>
                                        <button class="btn btn-warning edit-customer"
                                            data-user-id="<?php echo $row['user_id']; ?>"
                                            data-full-name="<?php echo $row['full_name']; ?>"
                                            data-email="<?php echo $row['email']; ?>"
                                            data-phone="<?php echo $row['phone']; ?>">
                                            Edit
                                        </button>
                                    </td>
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

    <!-- view modal -->
    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingDetailsModalLabel">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Table container for booking details -->
                    <div class="table-responsive" id="bookingDetailsBody">
                        <!-- Booking details will be injected here by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCustomerForm">
                        <!-- Hidden field for user_id -->
                        <input type="hidden" id="editUserId">

                        <div class="mb-3">
                            <label for="editFullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="editFullName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editPhone" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveCustomerChanges">Save Changes</button>
                </div>
            </div>
        </div>
    </div>





    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        //Data Table
        $(document).ready(function() {
            $('#bookingsTable').DataTable({
                "lengthChange": false,
                "info": false
            });

        });

        //View Modal
        $(".view-bookings").click(function() {
            // Retrieve the booking details from the data attributes of the clicked button
            var roomType = $(this).data('room-type');
            var checkInDate = $(this).data('check-in-date');
            var checkOutDate = $(this).data('check-out-date');
            var status = $(this).data('status');
            var totalAmount = $(this).data('total-amount');
            var amount = $(this).data('amount');

            // Construct the table rows with the fetched data
            var bookingRow = `
        <tr>
            <td>${roomType}</td>
            <td>${checkInDate}</td>
            <td>${checkOutDate}</td>
            <td>${status}</td>
             <td>${amount}</td>
           
        </tr>
        <tr>
            <th>Total Amount</th>
            <td></td>
            <td></td>
            <td></td>
            <th>PHP${totalAmount}</th>
        </tr>
    `;

            // Insert the table row into the modal body
            var tableContent = `
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Room Type</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                ${bookingRow}
            </tbody>
        </table>
    `;

            // Insert the table into the modal body
            $('#bookingDetailsBody').html(tableContent);

            // Show the modal with the booking details
            $('#bookingDetailsModal').modal('show');
        });

        // Open Edit Modal and Populate Fields
        $(".edit-customer").click(function() {
            // Fetch data attributes from the clicked button
            var userID = $(this).data("user-id");
            var fullName = $(this).data("full-name");
            var email = $(this).data("email");
            var phone = $(this).data("phone");

            // Populate modal fields
            $("#editUserId").val(userID);
            $("#editFullName").val(fullName);
            $("#editEmail").val(email);
            $("#editPhone").val(phone);

            // Show modal
            $("#editCustomerModal").modal("show");
        });


        // Save Changes
        $("#saveCustomerChanges").click(function() {
            var userID = $("#editUserId").val();
            var fullName = $("#editFullName").val();
            var email = $("#editEmail").val();
            var phone = $("#editPhone").val();

            $.ajax({
                url: "backend/update_customer.php",
                type: "POST",
                data: {
                    user_id: userID,
                    full_name: fullName,
                    email: email,
                    phone: phone
                },
                success: function(response) {
                    if (response.trim() === "success") {
                        alert("Customer updated successfully!");
                        location.reload();
                    } else {
                        alert("Failed to update customer. " + response);
                    }
                },
                error: function(xhr, status, error) {
                    alert("AJAX error: " + error);
                }
            });
        });
    </script>
</body>