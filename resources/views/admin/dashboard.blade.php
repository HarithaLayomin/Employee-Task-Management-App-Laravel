<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard</title>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
         @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideInFromTop {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideInFromBottom {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 2.5rem;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .badge {
            font-size: 1.2rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }
        .modal-content {
            border-radius: 10px;
        }
        /* Logout Button */
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 20px;
        }
    </style>
</head>
<body>

    

        <!-- Logout Button -->
        <button class="btn btn-danger btn-sm logout-btn" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</button>

    <div class="container mt-5">
        <h1 class="text-center mb-4 text-primary">Admin Dashboard</h1>

        <!-- Task Stats Row -->
        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Tasks</h5>
                        <h2 class="card-text" id="taskCount">Loading...</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pending Tasks</h5>
                        <h2 class="card-text" id="pendingTasks">Loading...</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Completed Tasks</h5>
                        <h2 class="card-text" id="completedTasks">Loading...</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Task Status Distribution Chart -->
        <div class="mt-4">
            <canvas id="taskStatusChart"></canvas>
        </div>

        <!-- Top 5 Employees with Most Completed Tasks -->
        <div class="mt-4">
            <h3 class="text-center mb-4 text-primary">Top 5 Employees with Most Completed Tasks</h3>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Completed Tasks</th>
                    </tr>
                </thead>
                <tbody id="topEmployeesTableBody"></tbody>
            </table>
            <div id="noDataMessage" class="text-center text-danger" style="display: none;">No top employees found.</div>
        </div>

        <!-- Add Employee Button -->
        <div class="text-center mt-4">
            <a href="#addEmployeeModal" data-bs-toggle="modal" class="btn btn-primary btn-lg"><i class="fas fa-user-plus"></i> Add Employee</a>
        </div>

        <!-- Add Employee Modal -->
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEmployeeForm">
                            <div class="mb-3">
                                <label for="employeeName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="employeeName" required>
                            </div>
                            <div class="mb-3">
                                <label for="employeeEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="employeeEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="employeePhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="employeePhone" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Employee Modal -->
        <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editEmployeeForm">
                            <div class="mb-3">
                                <label for="editEmployeeName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="editEmployeeName" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEmployeeEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmployeeEmail" required>
                            </div>
                            <div class="mb-3">
                                <label for="editEmployeePhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="editEmployeePhone" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="mt-4">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody"></tbody>
            </table>
        </div>
    </div>
    <!-- Add Task Button -->
    <div class="text-center mt-4">
        <a href="#addTaskModal" data-bs-toggle="modal" class="btn btn-primary btn-lg"><i class="fas fa-tasks"></i> Add Task</a>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addTaskForm">
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="taskTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" id="taskDescription">
                        </div>
                        <div class="mb-3">
                            <label for="taskDueDate" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="taskDueDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskStatus" class="form-label">Status</label>
                            <select class="form-control" id="taskStatus" required>
                                <option value="pending">Pending</option>
                                <option value="in-progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="taskEmployee" class="form-label">Assigned Employee</label>
                            <select class="form-control" id="taskEmployee" required>
                                <!-- Employee options will be populated here -->
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Add Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="editTaskTitle" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editTaskDescription" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="taskDueDate" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="editTaskDueDate" required>
                    </div>
                    <div class="mb-3">
                        <label for="taskStatus" class="form-label">Status</label>
                        <select class="form-control" id="editTaskStatus" required>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="taskEmployee" class="form-label">Assigned Employee</label>
                        <select class="form-control" id="editTaskEmployee" required></select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Task Table-->
<div class="mt-4">
    <h3 class="text-center mb-4 text-primary">Tasks</h3>
    <table class="table table-striped table-bordered">
        <thead class="table-dark"> <!-- Added table-dark for better visibility -->
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Assigned Employee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="taskTableBody">
            <!-- Task rows will be inserted here dynamically via JavaScript -->
        </tbody>
    </table>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            fetchEmployees();
            fetchTaskStats();
            fetchTopEmployees();
            fetchTasks();
            populateEmployeeDropdown(); 

            // Fetch employees and populate the task's assigned employee dropdown
            function populateEmployeeDropdown() {
                $.ajax({
                    url: '/api/employees', // Assuming this endpoint returns all employees
                    type: 'GET',
                    success: function(data) {
                        let employeeOptions = '<option value="">Select Employee</option>'; // Default option
                        data.forEach(employee => {
                            employeeOptions += `
                                <option value="${employee.id}">${employee.name}</option>
                            `;
                        });
                        $('#taskEmployee').html(employeeOptions); // Populate the dropdown
                    },
                    error: function() {
                        alert('Failed to fetch employees');
                    }
                });
            }

    
            // Fetch employees and update table
            function fetchEmployees() {
                $.ajax({
                    url: '/api/employees',
                    type: 'GET',
                    success: function(data) {
                        let tableBody = '';
                        data.forEach(employee => {
                            tableBody += `
                                <tr id="row-${employee.id}">
                                    <td>${employee.id}</td>
                                    <td>${employee.name}</td>
                                    <td>${employee.email}</td>
                                    <td>${employee.phone}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${employee.id}" data-name="${employee.name}" data-email="${employee.email}" data-phone="${employee.phone}"><i class="fas fa-edit"></i> Edit</button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${employee.id}"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#employeeTableBody').html(tableBody);
                    },
                    error: function() {
                        alert('Failed to fetch employees');
                    }
                });
            }
    
            // Fetch task statistics
            function fetchTaskStats() {
                $.ajax({
                    url: '/api/tasks',
                    type: 'GET',
                    success: function(data) {
                        let totalTasks = data.length;
                        let pendingTasks = data.filter(task => task.status === 'pending').length;
                        let completedTasks = data.filter(task => task.status === 'completed').length;
                        $('#taskCount').text(totalTasks);
                        $('#pendingTasks').text(pendingTasks);
                        $('#completedTasks').text(completedTasks);
                        updateTaskChart(pendingTasks, completedTasks);
                    },
                    error: function() {
                        $('#taskCount').text('Error');
                        $('#pendingTasks').text('Error');
                        $('#completedTasks').text('Error');
                    }
                });
            }
    
            // Update task chart
            function updateTaskChart(pending, completed) {
                const ctx = document.getElementById('taskStatusChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Pending', 'Completed'],
                        datasets: [{
                            label: 'Task Status Distribution',
                            data: [pending, completed],
                            backgroundColor: ['#f0ad4e', '#28a745'],
                            borderColor: ['#f0ad4e', '#28a745'],
                            borderWidth: 1
                        }]
                    },
                    options: { responsive: true, scales: { y: { beginAtZero: true } } }
                });
            }

                // Fetch task stats and update the table
        function fetchTasks() {
        $.ajax({
            url: '/api/tasks',  // Adjust URL to match your API
            type: 'GET',
            success: function(data) {
                let tableBody = '';
                let totalTasks = data.length;
                let pendingCount = 0;
                let completedCount = 0;
                
                // Loop through tasks to create table rows
                data.forEach(task => {
                    tableBody += `
                        <tr>
                            <td>${task.id}</td>
                            <td>${task.title}</td>
                            <td>${task.description}</td>
                            <td>${task.due_date}</td>
                            <td>${task.status}</td>
                            <td>${task.employee_name}</td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-id="${task.id}"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${task.id}"><i class="fas fa-trash-alt"></i> Delete</button>
                            </td>
                        </tr>
                    `;
                    
                    // Count task statuses
                    if (task.status === 'pending') pendingCount++;
                    if (task.status === 'completed') completedCount++;
                });

                // Update task stats
                $('#taskCount').text(totalTasks);
                $('#pendingTasks').text(pendingCount);
                $('#completedTasks').text(completedCount);

                // Append rows to task table
                $('#taskTableBody').html(tableBody);
            },
            error: function() {
                alert('Failed to fetch tasks');
            }
        });
    }
    
            // Fetch top employees with most completed tasks
            function fetchTopEmployees() {
                $.ajax({
                    url: '/api/employees/top',
                    type: 'GET',
                    success: function(data) {
                        let tableBody = '';
                        if (data.length > 0) {
                            data.forEach(employee => {
                                tableBody += `
                                    <tr>
                                        <td>${employee.id}</td>
                                        <td>${employee.name}</td>
                                        <td>${employee.tasks_count}</td>
                                    </tr>
                                `;
                            });
                            $('#topEmployeesTableBody').html(tableBody);
                            $('#noDataMessage').hide();
                        } else {
                            $('#topEmployeesTableBody').html('');
                            $('#noDataMessage').show();
                        }
                    },
                    error: function() {
                        $('#topEmployeesTableBody').html('');
                        $('#noDataMessage').show();
                    }
                });
            }
    
            // Add employee functionality
            $('#addEmployeeForm').submit(function(e) {
                e.preventDefault();
                const name = $('#employeeName').val();
                const email = $('#employeeEmail').val();
                const phone = $('#employeePhone').val();
    
                $.ajax({
                    url: '/api/employees',
                    type: 'POST',
                    data: { name, email, phone },
                    success: function() {
                        $('#addEmployeeModal').modal('hide');
                        fetchEmployees();
                    },
                    error: function() {
                        alert('Failed to add employee');
                    }
                });
            });
    
            // Edit employee functionality
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let phone = $(this).data('phone');
                
                // Open the modal with pre-filled data
                $('#editEmployeeName').val(name);
                $('#editEmployeeEmail').val(email);
                $('#editEmployeePhone').val(phone);
                $('#editEmployeeModal').modal('show');
                
                // Set form action for updating
                $('#editEmployeeForm').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: `/api/employees/${id}`,
                        type: 'PUT',
                        data: {
                            name: $('#editEmployeeName').val(),
                            email: $('#editEmployeeEmail').val(),
                            phone: $('#editEmployeePhone').val()
                        },
                        success: function() {
                            $('#editEmployeeModal').modal('hide');
                            fetchEmployees();
                        },
                        error: function() {
                            alert('Failed to update employee');
                        }
                    });
                });
            });
    
            // Delete employee functionality
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');
                if (confirm('Are you sure you want to delete this employee?')) {
                    $.ajax({
                        url: `/api/employees/${id}`,
                        type: 'DELETE',
                        success: function() {
                            fetchEmployees();
                        },
                        error: function() {
                            alert('Failed to delete employee');
                        }
                    });
                }
            });

            $(document).ready(function () {
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


            
        });
    </script>
    
</body>
</html>
