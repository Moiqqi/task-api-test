# Task Management System RESTful API

A RESTful API for a Task Management System built with Laravel, Docker, and MySQL...

---

## Features

- **Dockerized**: Using Docker for setup and deployment.
- **Task CRUD Management**: Create, read, update, and delete tasks.
- **Validation**: Validate input for task creation and update requests.
- **Testing**: Includes features and unit tests for all endpoints and services.

---

## Design Choices

### 1. **Repository and Service Pattern**
- **Repository Pattern**: Encapsulating database logic in `TaskRepository` and making it easier to test and maintain.
- **Service Layer**: Handling business logic in `TaskService` and separating it from the controller and repository.

### 2. **Enum for Task Status**
- We use `TaskStatus` enum to ensure type safety and readability for task status values.

### 3. **Validation and Error Handling**
- Laravel's built-in validation ensures valid input for `POST` and `PUT` requests.
- Proper error handling returns appropriate HTTP status codes (e.g., `422` for validation errors, `404` for not found).

### 4. **Testing**
- **Feature Tests**: Ensure the API endpoints behave as expected.
- **Unit Tests**: Isolated unit tests for `TaskService` and `TaskRepository`.

### 5. **Dockerization**
- The application is containerized using Docker, making it easy to set up and run in any environment.
- Includes services for the Laravel app, MySQL databases (development and testing), and phpMyAdmin.

---

## Setup Instructions

### Prerequisites

- Verify Docker, Docker Compose and Git are installed.
  - Install Docker: https://docs.docker.com/get-docker/
  - Install Docker Compose: https://docs.docker.com/compose/install/
  - Install Git: https://git-scm.com/downloads

---

### Step 1: Clone the repository:

   ```bash
   git clone https://github.com/Moiqqi/task-api-test.git
   cd task-api-test
   ```

### Step 2: Set Up Environment Configuration:

   1. Copy `.env.example` to `.env`:

      ```bash
      cp .env.example .env
      ```

   2. Update `.env` for Development:

      Modify the `.env` file with the following database configuration:
      ```env
      DB_CONNECTION=mysql
      DB_HOST=db
      DB_PORT=3306
      DB_DATABASE=laravel
      DB_USERNAME=laravel_user
      DB_PASSWORD=secret
      ```

   3. Create `.env.testing` for Testing:

      Create a `.env.testing` file with the following configuration:
      ```env
      APP_ENV=testing
      DB_CONNECTION=mysql
      DB_HOST=db_testing
      DB_PORT=3306
      DB_DATABASE=laravel_testing
      DB_USERNAME=laravel_user
      DB_PASSWORD=secret
      ```

### Step 3: Build and Start the Docker Containers:

   Run the following commands to build and start the Docker containers:
   ```bash
   docker-compose build
   docker-compose up -d
   ```

### Step 4: Install Composer Dependencies:

   Install PHP dependencies inside the `app` container:
   ```bash
   docker-compose exec app composer install
   ```

### Step 5: Generate Application Key:

   Generate a unique application key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

### Step 6: Run Migrations:

   Run migrations for the development database:
   ```bash
   docker-compose exec app php artisan migrate
   ```
   
   Run migrations for the testing database:
   ```bash
   docker-compose exec app php artisan migrate --env=testing
   ```

### Step 7: Run Seeders (Optional):

   Seed the development database:
   ```bash
   docker-compose exec app php artisan db:seed
   ```
   
   Seed the testing database:
   ```bash
   docker-compose exec app php artisan db:seed --env=testing
   ```

### Step 8: Run Tests:

   Run PHPUnit tests:
   ```bash
   docker-compose exec app ./vendor/bin/phpunit
   ```

### Step 9: Application Access:

   - Development Laravel App:
     - URL: http://localhost:8080

   - Development Database:
     - URL: http://localhost:8081
     - Username: laravel_user
     - Password: secret

## API Endpoints
Tasks:
- `GET /api/tasks` – List all tasks.
- `GET /api/tasks/{uuid}` – Retrieve details of a specific task.
- `POST /api/tasks` – Create a new task.
- `PUT /api/tasks/{uuid}` – Update an existing task.
- `DELETE /api/tasks/{uuid}` – Delete a task.
