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
        
        body {
        background-color: #eef2f7;
        
        font-family: 'Poppins', sans-serif;
        color: #333;
        }

        .card {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #ffffff;
        animation: fadeIn 0.8s ease-in-out;
        }

        .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .card-title {
        font-size: 1.7rem;
        font-weight: bold;
        color: #ffffff;
        }

        .card-text {
        font-size: 2.8rem;
        font-weight: bold;
        color: #ffffff;
        }

        .table th, .table td {
        vertical-align: middle;
        text-align: center;
        }

        .btn {
        transition: background-color 0.3s ease, transform 0.2s ease;
        border-radius: 6px;
        font-weight: 600;
        }

        .btn:hover {
        background-color: #0056b3;
        transform: scale(1.05);
        }

        .badge {
        font-size: 1.2rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        }

        .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Logout Button */
        .logout-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 10px 15px;
        border-radius: 6px;
        transition: transform 0.2s ease;
        }

        .logout-btn:hover {
        background: #c82333;
        transform: scale(1.1);
        }

        /* Task Status Chart */
        #taskStatusChartContainer {
        height: 320px;
        width: 100%;
        max-width: 650px;
        margin: 20px auto;
        background: #ffffff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }



        /* Top Employees Table */
        #topEmployeesTableContainer {
        margin: 0 auto;
        width: 85%;
        max-width: 850px;
        background: #ffffff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #topEmployeesTableContainer h3 {
        font-size: 1.6rem;
        font-weight: 600;
        color: #007bff;
        text-align: center;
        }

        #topEmployeesTable {
        font-size: 1rem;
        border-radius: 8px;
        }

        #topEmployeesTable th, #topEmployeesTable td {
        text-align: center;
        padding: 12px;
        border-bottom: 1px solid #ddd;
        }




    </style>

    
</head>
<body>

 <!-- Sidebar -->
 <div class="sidebar bg-dark text-white">
    <div class="text-center mb-4"><br>
        <h3 class="text-white">
            
            <i class="fas fa-tachometer-alt"></i> Admin Panel
            
        </h3><br>
    </div>
    
</div>

    

        <!-- Logout Button -->
        
        <button class="btn btn-danger btn-sm logout-btn" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</button>

    

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
        <div class="mt-4" id="taskStatusChartContainer">
            <canvas id="taskStatusChart"></canvas>
        </div>

        <!-- Top 5 Employees with Most Completed Tasks -->
        <div class="mt-4" id="topEmployeesTableContainer">
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
        </div><br>
        <h3 class="text-center mb-4 text-primary">Employees</h3>
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

    <script>
        $(document).ready(function() {
    // Fetching initial data
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
                $('#taskEmployee, #editTaskEmployee').html(employeeOptions); // Populate the dropdowns
            },
            error: function() {
                alert('Failed to fetch employees');
            }
        });
    }

    // Fetch employees and update employee table
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

// Fetch task statistics and update the chart
function fetchTaskStats() {
    $.ajax({
        url: '/api/tasks',  // Ensure this returns all tasks with their status
        type: 'GET',
        success: function(data) {
            let totalTasks = data.length;
            let pendingTasks = data.filter(task => task.status === 'pending').length;
            let completedTasks = data.filter(task => task.status === 'completed').length;
            let inProgressTasks = data.filter(task => task.status === 'in_progress').length;  // Count In Progress tasks

            // Update the stats display
            $('#taskCount').text(totalTasks);
            $('#pendingTasks').text(pendingTasks);
            $('#completedTasks').text(completedTasks);

            // Pass the counts to the chart function
            updateTaskChart(pendingTasks, completedTasks, inProgressTasks);
        },
        error: function() {
            $('#taskCount').text('Error');
            $('#pendingTasks').text('Error');
            $('#completedTasks').text('Error');
        }
    });
}


// Update task chart with Chart.js
function updateTaskChart(pending, completed, inProgress) {
    const ctx = document.getElementById('taskStatusChart').getContext('2d');
    

    // If there's an existing chart, destroy it before creating a new one to avoid multiple charts stacking on each other
    if (window.taskChart) {
        window.taskChart.destroy();
    }

    // Create a new chart
    window.taskChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending', 'In Progress', 'Completed'],  
            datasets: [{
                label: 'Task Status Distribution',
                data: [pending, inProgress, completed],  
                backgroundColor: ['#f0ad4e', '#ffc107', '#28a745'],  
                borderColor: ['#f0ad4e', '#ffc107', '#28a745'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Tasks'
                    }
                }
            }
        }
    });
}



    // Fetch tasks and update task table
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
                                <button class="btn btn-warning btn-sm edit-task-btn" data-id="${task.id}" data-title="${task.title}" data-description="${task.description}" data-due-date="${task.due_date}" data-status="${task.status}" data-employee-id="${task.employee_id}"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-danger btn-sm delete-task-btn" data-id="${task.id}"><i class="fas fa-trash-alt"></i> Delete</button>
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

    // Add task functionality
    $('#addTaskForm').submit(function(e) {
        e.preventDefault();
        const title = $('#taskTitle').val();
        const description = $('#taskDescription').val();
        const dueDate = $('#taskDueDate').val();
        const status = $('#taskStatus').val();
        const employeeId = $('#taskEmployee').val();

        $.ajax({
            url: '/api/tasks',
            type: 'POST',
            data: { title, description, due_date: dueDate, status, employee_id: employeeId },
            success: function() {
                $('#addTaskModal').modal('hide');
                fetchTasks(); // Refresh tasks table
                $('#addTaskForm')[0].reset(); // Reset form fields after success
            },
            error: function() {
                alert('Failed to add task');
            }
        });
    });

    // Edit task functionality
$(document).on('click', '.edit-task-btn', function() {
    let taskId = $(this).data('id');
    let taskTitle = $(this).data('title');
    let taskDescription = $(this).data('description');
    let taskDueDate = $(this).data('due-date');
    let taskStatus = $(this).data('status');
    let taskEmployeeId = $(this).data('employee-id');
    
    // Open the modal with pre-filled data
    $('#editTaskTitle').val(taskTitle);
    $('#editTaskDescription').val(taskDescription);
    $('#editTaskDueDate').val(taskDueDate);
    $('#editTaskEmployee').val(taskEmployeeId);
    
    // Populate the status dropdown with all options, including 'In Progress'
    let statusOptions = `
        <option value="pending" ${taskStatus === 'pending' ? 'selected' : ''}>Pending</option>
        <option value="in_progress" ${taskStatus === 'in_progress' ? 'selected' : ''}>In Progress</option>
        <option value="completed" ${taskStatus === 'completed' ? 'selected' : ''}>Completed</option>
    `;
    $('#editTaskStatus').html(statusOptions); // Populate the status dropdown
    
    $('#editTaskModal').modal('show');
    
    // Set form action for updating
    $('#editTaskForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/tasks/${taskId}`,
            type: 'PUT',
            data: {
                title: $('#editTaskTitle').val(),
                description: $('#editTaskDescription').val(),
                due_date: $('#editTaskDueDate').val(),
                status: $('#editTaskStatus').val(),
                employee_id: $('#editTaskEmployee').val()
            },
            success: function() {
                $('#editTaskModal').modal('hide');
                fetchTasks(); // Refresh tasks table
            },
            error: function() {
                alert('Failed to update task');
            }
        });
    });
});

    // Delete task functionality
    $(document).on('click', '.delete-task-btn', function() {
        let taskId = $(this).data('id');
        if (confirm('Are you sure you want to delete this task?')) {
            $.ajax({
                url: `/api/tasks/${taskId}`,
                type: 'DELETE',
                success: function() {
                    fetchTasks(); // Refresh tasks table
                },
                error: function() {
                    alert('Failed to delete task');
                }
            });
        }
    });

    // Edit employee functionality
$(document).on('click', '.edit-btn', function() {
    let employeeId = $(this).data('id');
    let employeeName = $(this).data('name');
    let employeeEmail = $(this).data('email');
    let employeePhone = $(this).data('phone');
    
    // Open the modal with pre-filled data
    $('#editEmployeeName').val(employeeName);
    $('#editEmployeeEmail').val(employeeEmail);
    $('#editEmployeePhone').val(employeePhone);
    $('#editEmployeeModal').modal('show');
    
    // Set form action for updating
    $('#editEmployeeForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `/api/employees/${employeeId}`,
            type: 'PUT',
            data: {
                name: $('#editEmployeeName').val(),
                email: $('#editEmployeeEmail').val(),
                phone: $('#editEmployeePhone').val()
            },
            success: function() {
                $('#editEmployeeModal').modal('hide');
                fetchEmployees(); // Refresh employee table
            },
            error: function() {
                alert('Failed to update employee');
            }
        });
    });
});

// Delete employee functionality
$(document).on('click', '.delete-btn', function() {
    let employeeId = $(this).data('id');
    if (confirm('Are you sure you want to delete this employee?')) {
        $.ajax({
            url: `/api/employees/${employeeId}`,
            type: 'DELETE',
            success: function() {
                fetchEmployees(); // Refresh employee table
            },
            error: function() {
                alert('Failed to delete employee');
            }
        });
    }
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
