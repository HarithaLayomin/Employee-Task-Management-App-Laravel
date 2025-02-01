<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Employee Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1598746242663-100086524f9f?crop=entropy&cs=tinysrgb&fit=max&ixid=MXwyMDg3fDB8MHxwaG90by1wYWNrZXx8fGVtcGxveWVlcnxlbnwwfHx8fDE2NjA3NzEyODk&ixlib=rb-1.2.1&q=80&w=1080') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            color: #ffffff;
        }

        .welcome-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            padding: 30px;
            animation: fadeIn 2s ease-out;
        }

        .welcome-container h1 {
            font-size: 3rem;
            font-weight: 600;
            margin-bottom: 20px;
            animation: slideInFromTop 1.5s ease-out;
        }

        .welcome-container p {
            font-size: 1.25rem;
            margin-bottom: 30px;
            animation: slideInFromBottom 1.5s ease-out;
        }

        .btn-lg {
            font-size: 1.2rem;
            padding: 15px 30px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-5px);
        }

        .btn-outline-secondary {
            background-color: transparent;
            color: #ffffff;
            border: 2px solid #ffffff;
        }

        .btn-outline-secondary:hover {
            background-color: #ffffff;
            color: #3498db;
            border-color: #3498db;
            transform: translateY(-5px);
        }

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
    </style>
</head>
<body>

    <div class="welcome-container">
        <h1>Welcome to Employee Task Management</h1>
        <p>Effortlessly manage your employees, tasks, and projects in one place.</p>
        
        <div class="d-flex gap-4">
            <!-- Get Started Button (Navigates to Login) -->
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Get Started</a>
            
            <!-- Register Button -->
            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg">Create an Account</a>
        </div>
    </div>

</body>
</html>
