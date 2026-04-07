# Service Management System (SMS)

A comprehensive web-based application for managing service bookings, inventory, staff assignments, and billing.

## Features
-   **User Roles**: Admin, Employee, Vendor, Customer.
-   **Service Booking**: Customers can book services online.
-   **Inventory Management**: Track spare parts and link them to services (BOM).
-   **Operations**: Assign tasks, track attendance, and manage notifications.
-   **Finance**: Generate invoices (with tax & parts cost) and track payments.
-   **Reports**: Financial and operational analytics.

## Installation
1.  **Clone the repository** to your server root (e.g., `htdocs` or `/var/www/html`).
2.  **Import Database**:
    -   Create a database named `sms_db`.
    -   Run `public/setup_db.php` in your browser or CLI to create initial tables.
    -   Run `public/update_db_phase3.php`, `public/update_db_phase4.php`, `public/update_db_phase5.php` to apply updates.
3.  **Configuration**:
    -   Edit `app/config/config.php` to match your DB credentials.
    -   Set `URLROOT` to your local path (e.g., `http://localhost/Service Management System`).

## Usage
-   **Admin Login**: `admin@gmail.com` / `123456`
-   **Customer Url**: `/users/register` to sign up.

## Tech Stack
-   **Backend**: PHP (MVC Pattern)
-   **Frontend**: HTML5, CSS3, Bootstrap 4, JavaScript
-   **Database**: MySQL
