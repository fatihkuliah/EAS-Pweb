# MieME Application

A web-based food ordering system built with a native PHP MVC architecture. It provides a seamless experience for customers to browse menus, manage their carts, and place orders, while offering a comprehensive administrative dashboard for managing the catalog, verifying payments, and tracking sales.

## Features

- **MVC Architecture**: Clear separation of concerns utilizing Models, Views, and Controllers.
- **Role-Based Access Control**: Differentiated interfaces and functionalities for Customers and Administrators.
- **Customer Portal**:
  - Menu catalog with categorization and search functionality.
  - Shopping cart and seamless checkout system.
  - Payment processing supporting QRIS and manual bank transfers with proof of payment upload.
  - Order history tracking and review submission.
- **Admin Dashboard**:
  - Key Performance Indicators (KPIs) for daily revenue, total orders, and pending verifications.
  - Menu and category management (Create, Read, Update, Delete).
  - Payment verification system (Approve or Reject uploaded transaction receipts).
  - Order status management (Processing, Shipping, Completed).
  - User management and customer review moderation.
- **Custom Database Tools**: Built-in CLI tool (`db.php`) for managing database migrations and seeding.
- **Secure File Storage**: Uploaded files (such as payment proofs) are securely stored outside the public directory and served via controlled application routes.

## System Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- PHP PDO Extension enabled

## Installation and Setup

### 1. Database Configuration

1. Clone the repository and navigate to the project root directory.
2. Copy the environment configuration template:
   ```bash
   cp .env.example .env
   ```
3. Open the `.env` file and update it with your local database credentials:
   ```env
   DB_HOST=127.0.0.1
   DB_NAME=mieme_db
   DB_USER=root
   DB_PASS=your_database_password
   ```

### 2. Database Initialization

The application includes a custom CLI utility to manage the database schema and seed initial data.

Run the following command to reset the database, run all schema migrations, and populate it with seed data:
```bash
php db.php reset
```

**Additional Database Commands:**
- Run Migrations (Create tables): `php db.php migrate`
- Rollback Migrations (Drop tables): `php db.php rollback`
- Run Seeders (Insert mock data): `php db.php seed`

### 3. Running the Development Server

To serve the application locally during development, use the built-in PHP development server. It is crucial to set the `public` directory as the document root. This ensures that static assets are served correctly and internal application files remain secure.

```bash
php -S localhost:8000 -t public public/index.php
```

Access the application in your web browser at: `http://localhost:8000`

## Directory Structure

```text
.
├── README.md                 # Project documentation
├── db.php                    # CLI tool for database migrations and seeding
├── .env                      # Environment configuration variables
├── .env.example              # Template for environment variables
├── .gitignore                # Git ignore rules to prevent committing sensitive files
├── app                       # Application core logic (MVC Structure)
│   ├── Controllers           # Handles incoming HTTP requests and application flow
│   ├── Core                  # Base framework classes (Controller, Database, Env, Router)
│   ├── Helpers               # Global utility functions (formatting, path resolution, http)
│   ├── Models                # Data access layer interacting with the database
│   ├── Services              # Business logic layer (Cart, Checkout, Payment, Orders)
│   └── Views                 # Presentation layer (PHP templates and HTML structures)
├── config                    # Application configurations
│   ├── app.php               # General application settings
│   ├── database.php          # Database connection instantiation settings
│   └── routes.php            # URL routing definitions mapped to specific Controllers
├── database                  # Database schema definitions
│   ├── migrations            # PHP scripts to create and modify database tables
│   └── seeds                 # Scripts to populate the database with initial application data
├── public                    # Document root accessible by the web server
│   ├── .htaccess             # Apache configuration for URL rewriting
│   ├── assets                # Publicly accessible static files (CSS, JS, Images)
│   └── index.php             # Front controller entry point for all HTTP requests
└── storage                   # Secure storage for application-generated files
    ├── exports               # Generated export files (CSV, Reports)
    ├── invoices              # Generated order invoice documents
    └── uploads               # User-uploaded files (e.g., payment proofs)
```

## Security and Deployment Notes

- **Document Root**: Always ensure that your production web server (Nginx or Apache) points its document root specifically to the `public/` directory. This prevents direct web access to the `app/` and `config/` directories containing sensitive logic.
- **Storage Security**: The `storage` directory is intentionally located outside the `public` document root. Files within this directory are served securely via application-controlled routes (`/storage/...` intercepted in `index.php`), ensuring that internal uploads cannot be accessed directly without passing through the application's verification logic.
