<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Add Employee</h1>

        <form id="addEmployeeForm">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>

            <button type="submit" class="btn btn-success">Add Employee</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>

    <script>
        $('#addEmployeeForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: '/api/employees',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Employee added successfully');
                    window.location.href = "{{ route('admin.dashboard') }}";
                },
                error: function(xhr) {
                    alert('Failed to add employee');
                }
            });
        });
    </script>
</body>
</html>
