<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/your-repo/employee-task-management/actions"><img src="https://github.com/your-repo/employee-task-management/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Employee Task Management System (ETMS)

The Employee Task Management System (ETMS) is a web-based application designed to allow admins to create, assign, and manage tasks for employees, while employees can view and update their task statuses. The system features a dashboard displaying key metrics like pending and completed tasks and task distribution among employees.

## Technologies Used

- **Backend**: Laravel (PHP Framework)
- **Frontend**: HTML, CSS (Bootstrap), jQuery
- **Database**: MySQL
- **AJAX**: For seamless API interactions
- **Chart.js**: For visual representation of task distribution

## Setup Instructions

### Prerequisites

Ensure you have the following installed on your system:

- PHP (greater than or equal to 8.0)
- Composer
- Laravel (latest version)
- MySQL or any supported database
- Node.js & npm (for frontend dependencies, if needed)

### Installation Steps

1. **Clone the Repository**:

    ```bash
    git clone https://github.com/HarithaLayomin/Employee-Task-Management-App-Laravel.git
    cd employee-task-management
    ```

2. **Install Dependencies**:

    ```bash
    composer install
    npm install  # If using frontend packages
    ```

3. **Environment Configuration**:

    Duplicate `.env.example` and rename it as `.env`

    Update database credentials in `.env`

4. **Generate Application Key**:

    ```bash
    php artisan key:generate
    ```

5. **Run Database Migrations & Seeders**:

    ```bash
    php artisan migrate --seed
    ```

6. **Run the Application**:

    ```bash
    php artisan serve
    ```

    The application will be accessible at [http://127.0.0.1:8000](http://127.0.0.1:8000).

## API Endpoints

### Authentication

- **Login**: `POST /api/login`
- **Logout**: `POST /api/logout`

### Employee Management

- **Get all employees**: `GET /api/employees`
- **Create an employee**: `POST /api/employees`
- **Update an employee**: `PUT /api/employees/{id}`
- **Delete an employee**: `DELETE /api/employees/{id}`

### Task Management

- **Get all tasks**: `GET /api/tasks`
- **Create a task**: `POST /api/tasks`
- **Update a task**: `PUT /api/tasks/{id}`
- **Delete a task**: `DELETE /api/tasks/{id}`
- **Get assigned tasks**: `GET /api/tasks/assigned`

### Dashboard Metrics

- **Total, Pending, Completed Tasks**: `GET /api/tasks/statistics`
- **Top Employees (by completed tasks)**: `GET /api/employees/top`

## Logical and Mathematical Implementations

### Task Statistics Calculation:

The system calculates total, pending, and completed tasks using Laravel’s query builder.

```php
$totalTasks = Task::count();
$pendingTasks = Task::where('status', 'pending')->count();
$completedTasks = Task::where('status', 'completed')->count();
