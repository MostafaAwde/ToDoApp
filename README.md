# Task Management Project

A full-stack **PHP To-Do application** built with a clean, **n-tier architecture**.  
Manage tasks with titles, descriptions, due dates, priorities, and single user-defined categories.

Features include drag-and-drop reordering, search, filters, Bootstrap-powered UI, delete confirmation modals, CSV/PDF export, and a DI container.

---

## Table of Contents

- [Features](#features)
- [Architecture](#architecture)
- [Tech Stack](#tech-stack)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Migrations & Seeding](#database-migrations--seeding)
- [Database Stored Procedures & Views](#database-stored-procedures--views)
- [Running the App](#running-the-app)
- [Routes & Endpoints](#routes--endpoints)
- [Project Structure](#project-structure)
- [Improvements & Future Work](#improvements--future-work)
- [Contributing](#contributing)
- [License](#license)

---

## Features

- Create, edit, delete, and reorder tasks via drag-and-drop
- Single, user-defined categories (e.g. Work, Learning, Fitness)
- Search by title/description, filter by status (Pending/Completed)
- Delete confirmation via Bootstrap modal
- Export tasks to CSV and PDF (Dompdf)
- Responsive, accessible UI with Bootstrap 5
- Flash messages for errors & validations
- PHP sessions for user authentication (signup, login, logout)

---

## Architecture

This project follows a layered **n-tier architecture**:

1. **Presentation Layer**

   - Controllers (`src/Controller`) handle HTTP, sessions, and view rendering
   - Views (`public/views`) contain HTML templates (tasks, categories, dashboard)

2. **Business Logic Layer**

   - Services (`src/Service`) implement use cases (`TaskService`, `CategoryService`)

3. **Data Access Layer**

   - Repositories (`src/Repository`) abstract database operations
   - Stored procedures & views for optimized queries

4. **Database Layer**

   - MariaDB/MySQL with Doctrine Migrations for schema & seeds
   - Stored procedures for CRUD and reordering
   - Views for consistent, pre-joined result sets

5. **Cross-Cutting Concerns**
   - Dependency Injection via PHP-DI (`config/di.php`)
   - Error handling middleware (`ErrorHandlingMiddleware`)
   - Session helper (`App\Helper\Session`)

---

## Tech Stack

- PHP 8.2+
- Composer for dependency management
- MariaDB / MySQL
- Doctrine Migrations for schema versioning
- PHP-DI container for interface-to-implementation wiring
- Bootstrap 5 & Bootstrap Icons for UI
- Dompdf for PDF generation
- Custom Router (PSR-7 style)

---

## Requirements

- PHP 8.2 or later
- Composer
- MariaDB / MySQL
- Git
- Web server (Apache, Nginx) or PHP’s built-in server

---

## Installation

```bash
# 1. Clone the repo
git clone https://github.com/yourusername/task-management.git
cd task-management

# 2. Install PHP dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Update DB credentials in .env
# DB_HOST=localhost
# DB_PORT=3306
# DB_DATABASE=TodoApp
# DB_USERNAME=your_user
# DB_PASSWORD=your_pass

# 5. Run database migrations & seed initial categories
vendor/bin/doctrine-migrations migrate

# 6. Start local server
php -S localhost:8000 -t public
```

---

## Configuration

- **config/di.php** → interface bindings for repositories, services, controllers
- **bootstrap.php** → loads environment, builds DI container, invokes router
- **routes/web.php** → HTTP routes mapped to controller methods

Customize session settings, error-handling flags, or router prefixes here.

---

## Database Migrations & Seeding

Migrations are located in `src/Migration`:

- **Schema Migration**

  - `Version20250903120000`: creates `categories` table and adds `category_id` to `tasks`.
  - `Version20250903112546`: adds stored procedures for category CRUD and `categories_view`.

- **Seed Migration**
  - `Version20250903113957`: inserts ten default categories.

Run migrations:

```bash
vendor/bin/doctrine-migrations migrate
```

Rollback:

```bash
vendor/bin/doctrine-migrations rollback
```

---

## Database Stored Procedures & Views

### Category Stored Procedures

- `sp_insert_category(IN p_name VARCHAR(100))` → Inserts a new category.
- `sp_update_category(IN p_id INT, IN p_name VARCHAR(100))` → Updates category by ID.
- `sp_delete_category(IN p_id INT)` → Deletes a category by ID.

### Category View

```sql
CREATE VIEW categories_view AS
  SELECT
    id   AS category_id,
    name AS category_name
  FROM categories
  ORDER BY name;
```

### Task Stored Procedures

- `sp_insert_task(...)` → Creates a new task.
- `sp_update_task(...)` → Updates task details.
- `sp_toggle_task(IN p_id INT, IN p_completed TINYINT)` → Toggles completion status.
- `sp_delete_task(IN p_id INT)` → Deletes a task by ID.
- `sp_reorder_tasks(IN p_user_id INT, IN p_order JSON)` → Updates task ordering.

### Task View

```sql
CREATE VIEW tasks_with_users AS
  SELECT
    t.id           AS task_id,
    t.user_id,
    t.category_id,
    t.title,
    t.description,
    t.due_date,
    t.priority,
    t.completed,
    t.position,
    t.created_at,
    u.name         AS user_name,
    c.name         AS category_name
  FROM tasks t
    JOIN users u ON u.id = t.user_id
    LEFT JOIN categories c ON c.id = t.category_id;
```

## Database Access

1- We should give permission for user to execute sp:
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_insert_user` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_update_user` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_delete_user` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_insert_task` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_update_task` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_delete_task` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_toggle_task` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_reorder_tasks` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_insert_category` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_update_category` TO 'mostafa'@'localhost';
GRANT EXECUTE ON PROCEDURE `TodoApp`.`sp_delete_category` TO 'mostafa'@'localhost';

FLUSH PRIVILEGES;

2- Also for views:
GRANT EXECUTE ON `TodoApp`.\* TO 'mostafa'@'localhost';
GRANT SELECT ON `TodoApp`.`tasks_with_users` TO 'mostafa'@'localhost';
FLUSH PRIVILEGES;

GRANT EXECUTE ON `TodoApp`.\* TO 'mostafa'@'localhost';
GRANT SELECT ON `TodoApp`.`categories_view` TO 'mostafa'@'localhost';
FLUSH PRIVILEGES;

## Running the App

- Signup → `/signup`
- Login → `/login`
- Dashboard → `/dashboard`
- Manage Tasks → `/tasks`
- Manage Categories → `/categories`
- Export CSV → `/tasks/export/csv`
- Export PDF → `/tasks/export/pdf`

---

## Routes & Endpoints

| Method | Path                    | Action                  |
| ------ | ----------------------- | ----------------------- |
| GET    | /signup                 | Show signup form        |
| POST   | /signup                 | Process signup          |
| GET    | /login                  | Show login form         |
| POST   | /login                  | Process login           |
| GET    | /logout                 | Logout user             |
| GET    | /dashboard              | Show dashboard stats    |
| GET    | /tasks                  | List tasks              |
| GET    | /tasks/create           | Show create form        |
| POST   | /tasks/create           | Create new task         |
| GET    | /tasks/edit/{id}        | Show edit form          |
| POST   | /tasks/edit/{id}        | Update task             |
| POST   | /tasks/delete/{id}      | Delete task             |
| POST   | /tasks/toggle/{id}      | Toggle complete         |
| POST   | /tasks/reorder          | Reorder tasks (AJAX)    |
| GET    | /tasks/export/csv       | Download tasks as CSV   |
| GET    | /tasks/export/pdf       | Download tasks as PDF   |
| GET    | /categories             | List categories         |
| GET    | /categories/create      | Show new category form  |
| POST   | /categories/create      | Create category         |
| GET    | /categories/edit/{id}   | Show edit category form |
| POST   | /categories/edit/{id}   | Update category         |
| POST   | /categories/delete/{id} | Delete category         |

---

## Project Structure

```
├── config
│   └── di.php
├── public
│   ├── index.php
│   └── views
│       ├── tasks
│       ├── categories
│       └── dashboard.php
├── routes
│   └── web.php
├── src
│   ├── Controller
│   ├── Dto
│   ├── Service
│   ├── Repository
│   ├── Model
│   ├── Helper
│   └── Migration
├── composer.json
└── bootstrap.php
```

---

## Improvements & Future Work

- Swap the custom router for **Slim Framework**
- Adopt **Doctrine ORM + DBAL** for richer entity management
- Add **unit & integration tests** (PHPUnit)
- Implement **RESTful API endpoints** with JWT authentication
- Enhance PDF styling or integrate a SPA frontend (Vue/React)

---

## Contributing

1. Fork the repo
2. Create your feature branch → `git checkout -b feature/Name`
3. Commit your changes → `git commit -m "Add feature"`
4. Push to branch → `git push origin feature/Name`
5. Open a pull request

Please follow **PSR-12 coding standards** and write tests for new functionality.

---

## License

This project is licensed under the **MIT License**.
