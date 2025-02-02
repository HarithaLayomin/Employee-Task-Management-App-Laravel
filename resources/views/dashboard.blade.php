<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            display: flex;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background-color: #212529;
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            transition: all 0.3s ease;
        }
        .sidebar h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px;
            border-radius: 5px;
            font-size: 1.1rem;
            transition: 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #495057;
            transform: translateX(5px);
        }
        .content {
            margin-left: 270px;
            width: calc(100% - 270px);
            padding: 30px;
        }
        .task-card {
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .task-card h3 {
            font-size: 2rem;
            font-weight: bold;
        }
        .table th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            .content {
                margin-left: 210px;
                padding: 20px;
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="sidebar">
        <h3><i class="fas fa-user"></i> Employee Panel</h3>
        <hr>
        <a href="#"><i class="fas fa-home"></i> Dashboard</a>
        <a href="#"id = "logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="content">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Pending Tasks</div>
                    <div class="card-body">
                        <h3 id="pending-tasks-count">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Completed Tasks</div>
                    <div class="card-body">
                        <h3 id="completed-tasks-count">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Your Assigned Tasks</div>
            <div class="card-body">
                <table class="table" id="tasks-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Task Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select id="task-status" class="form-select">
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-status-btn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var tasks = [
                { title: 'Task 1', description: 'Task 1 description', status: 'pending' },
                { title: 'Task 2', description: 'Task 2 description', status: 'completed' },
                { title: 'Task 3', description: 'Task 3 description', status: 'pending' },
            ];

            tasks.forEach(function(task) {
                $('#tasks-table tbody').append(`
                    <tr>
                        <td>${task.title}</td>
                        <td>${task.description}</td>
                        <td>${task.status}</td>
                        <td><button class="btn btn-warning update-status-btn" data-status="${task.status}" data-title="${task.title}">Update Status</button></td>
                    </tr>
                `);
            });

            $(document).on('click', '.update-status-btn', function() {
                var taskTitle = $(this).data('title');
                var currentStatus = $(this).data('status');
                $('#task-status').val(currentStatus);
                $('#updateStatusModal').modal('show');

                $('#save-status-btn').off().on('click', function() {
                    var newStatus = $('#task-status').val();
                    $('button[data-title="' + taskTitle + '"]').closest('tr').find('td:nth-child(3)').text(newStatus);
                    $('#updateStatusModal').modal('hide');
                });
            });

            
 // Logout functionality
 $("#logoutBtn").click(function () {
        if (confirm("Are you sure you want to log out?")) {
            $.ajax({
                url: '/logout',
                type: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                },
                success: function () {
                    window.location.href = '/login'; // Redirect to login page after logout
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    alert("Logout failed. Please try again.");
                }
            });
        }
    });



        });
    </script>
</body>
</html>
