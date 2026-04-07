# Service Management System (SMS) — Project Documentation

> **Version:** 2.0 | **Last Updated:** February 2026  
> **Tech Stack:** PHP (Custom MVC) · MySQL · Bootstrap 4 · Font Awesome 5 · Chart.js

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Architecture & Directory Structure](#2-architecture--directory-structure)
3. [Database Schema](#3-database-schema)
4. [User Roles & Access Control](#4-user-roles--access-control)
5. [Module Guide](#5-module-guide)
   - [Authentication & User Management](#51-authentication--user-management)
   - [Admin Dashboard](#52-admin-dashboard)
   - [Services Management](#53-services-management)
   - [Items (Inventory & Products)](#54-items-inventory--products)
   - [Parties Management](#55-parties-management)
   - [Bookings](#56-bookings)
   - [Invoices & Billing](#57-invoices--billing)
   - [Complaints](#58-complaints)
   - [Tasks](#59-tasks)
   - [Attendance & Leave Management](#510-attendance--leave-management)
   - [Expenses](#511-expenses)
   - [Reports & Analytics](#512-reports--analytics)
   - [Notifications](#513-notifications)
   - [Teams](#514-teams)
6. [Key Workflows](#6-key-workflows)
7. [Security](#7-security)
8. [Configuration](#8-configuration)
9. [Setup & Installation](#9-setup--installation)

---

## 1. Project Overview

The **Service Management System (SMS)** is a full-featured web application for businesses that offer repair, maintenance, or service-based operations. It manages the complete lifecycle from service booking → assignment → execution → invoicing → reporting, while also handling inventory (spare parts), employee attendance, expenses, complaints, and customer relations.

### Key Capabilities
- Multi-role access (Admin, Manager, Employee, Vendor, Customer)
- End-to-end service booking and tracking
- Automated invoice generation with parts + labor costing
- Bill of Materials (BOM) per service
- Employee attendance with automatic late/overtime calculation
- Leave management with approval workflow
- Financial reports with charts
- Real-time notifications
- Inventory stock tracking with low-stock alerts
- Complaint management with assignment system

---

## 2. Architecture & Directory Structure

The project follows a custom **MVC (Model-View-Controller)** framework built with core PHP.

```
Service Management System/
├── app/
│   ├── config/
│   │   └── config.php              # DB credentials, URLROOT, SITENAME constants
│   ├── controllers/                # 19 controllers handle all business logic
│   │   ├── Admin.php               # Admin dashboard + user listing
│   │   ├── AdminAttendance.php     # Manage attendance logs, leaves, settings
│   │   ├── AdminExpenses.php       # View/manage expense claims
│   │   ├── AdminUsers.php          # Ban/activate users, KYC verification
│   │   ├── Bookings.php            # CRUD for service bookings
│   │   ├── Complaints.php          # CRUD for complaints
│   │   ├── Employees.php           # Employee dashboard, tasks, attendance, leaves
│   │   ├── Inventories.php         # Inventory CRUD (legacy)
│   │   ├── Invoices.php            # Generate, view, pay invoices
│   │   ├── Items.php               # Unified items management (products + services)
│   │   ├── Notifications.php       # Mark notifications as read
│   │   ├── Pages.php               # Static pages (home, about)
│   │   ├── Parties.php             # Customers, vendors, suppliers management
│   │   ├── Reports.php             # Financial reports & analytics
│   │   ├── Seeder.php              # Database seeding utility
│   │   ├── Services.php            # Full service lifecycle management
│   │   ├── Tasks.php               # Internal task assignment system
│   │   ├── Teams.php               # Team/department management
│   │   └── Users.php               # Login, registration, profile management
│   ├── helpers/
│   │   ├── session_helper.php      # Session functions (isLoggedIn, flash, redirect)
│   │   └── notification_helper.php # Create/get notification functions
│   ├── libraries/
│   │   ├── Core.php                # URL routing (parses controller/method/params)
│   │   ├── Controller.php          # Base controller (model, view loading)
│   │   └── Database.php            # PDO wrapper (query, bind, execute, resultSet)
│   ├── models/                     # 13 models handle all database interaction
│   │   ├── Attendance.php          # Check-in/out, leave CRUD, settings, reports
│   │   ├── Booking.php             # Booking queries & assignment
│   │   ├── Complaint.php           # Complaint CRUD & assignment
│   │   ├── Expense.php             # Expense claims CRUD
│   │   ├── Inventory.php           # Stock management (legacy)
│   │   ├── Invoice.php             # Invoice creation, payment, line items
│   │   ├── Item.php                # Unified product/service item management
│   │   ├── Notification.php        # Notification queries
│   │   ├── Party.php               # Party/contact management
│   │   ├── Service.php             # Service CRUD, categories, parts (BOM)
│   │   ├── Task.php                # Task queries
│   │   ├── Team.php                # Team queries
│   │   └── User.php                # User CRUD, role-based queries, auth
│   └── views/                      # 15 view directories with PHP templates
│       ├── admin/                  # Admin panel views (dashboard, users, attendance, expenses)
│       ├── bookings/               # Booking list, detail, management
│       ├── complaints/             # Complaint list, detail, creation
│       ├── employees/              # Employee dashboard, tasks, attendance, leaves
│       ├── inc/                    # Shared includes (header, footer, sidebar)
│       ├── inventory/              # Inventory views
│       ├── invoices/               # Invoice list, detail, generation
│       ├── items/                  # Item management views
│       ├── pages/                  # Static pages (home, about)
│       ├── parties/                # Party management views
│       ├── reports/                # Reports & charts
│       ├── services/               # Service catalog, CRUD, parts, categories
│       ├── tasks/                  # Task views
│       ├── teams/                  # Team management views
│       └── users/                  # Login, register, profile, employee creation
└── public/
    ├── index.php                   # Application entry point
    ├── .htaccess                   # URL rewriting rules
    ├── css/                        # Stylesheets (style.css, admin.css)
    ├── js/                         # JavaScript files
    └── img/                        # Image assets
```

### How Routing Works
```
URL: /Service Management System/bookings/create
         ↓
Core.php parses → Controller: Bookings | Method: create | Params: []
         ↓
Bookings::create() → loads model, processes logic → renders view
```

---

## 3. Database Schema

### Core Tables

| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `users` | All system users | `id`, `name`, `email`, `phone`, `address`, `password`, `role_id`, `status`, `kyc_document`, `kyc_status`, `profile_image`, `designation` |
| `roles` | User role definitions | `id`, `name` (Admin, Manager, Employee, Vendor, Customer) |
| `services` | Service catalog | `id`, `name`, `description`, `price`, `category_id`, `image`, `status` |
| `categories` | Service categories | `id`, `name`, `description`, `icon` |
| `bookings` | Service bookings | `id`, `user_id`, `service_id`, `booking_date`, `booking_time`, `status`, `assigned_to`, `notes` |
| `invoices` | Financial invoices | `id`, `booking_id`, `customer_id`, `service_id`, `total_amount`, `tax`, `status` |
| `invoice_items` | Line items on invoices | `id`, `invoice_id`, `description`, `quantity`, `unit_price`, `total` |

### Inventory & Items

| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `products` | Inventory/spare parts | `id`, `name`, `sku`, `price`, `stock`, `category_id` |
| `items` | Unified items (products + services) | `id`, `name`, `type`, `sku`, `unit`, `sale_price`, `purchase_price`, `stock`, `category` |
| `service_inventory` | BOM linking (which parts a service needs) | `id`, `service_id`, `inventory_id`, `quantity_needed` |

### People & Communications

| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `parties` | Customers, suppliers, vendors | `id`, `name`, `type`, `email`, `phone`, `gstin`, `billing_address` |
| `complaints` | Customer complaints | `id`, `user_id`, `subject`, `description`, `status`, `assigned_to` |
| `notifications` | System alerts | `id`, `user_id`, `message`, `type`, `is_read` |
| `teams` | Departments/teams | `id`, `name`, `description` |

### HR & Finance

| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `attendance` | Daily attendance records | `id`, `user_id`, `date`, `check_in`, `check_out`, `status`, `work_hours`, `late_minutes`, `overtime_minutes`, `notes`, `marked_by` |
| `leaves` | Leave requests | `id`, `user_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `approved_by` |
| `attendance_settings` | Shift configuration | `id`, `setting_key`, `setting_value` |
| `expenses` | Expense claims | `id`, `user_id`, `description`, `amount`, `status`, `receipt` |
| `tasks` | Internal task assignments | `id`, `title`, `description`, `assigned_to`, `status`, `priority` |

---

## 4. User Roles & Access Control

The system uses **Role-Based Access Control (RBAC)** enforced at the controller level via session checks.

### Role Hierarchy

| Role ID | Role | Access Level |
|---------|------|-------------|
| 1 | **Admin** | Full system access. Manages all modules. |
| 2 | **Manager** | Similar to admin with limited scope (future expansion). |
| 3 | **Employee** | Employee dashboard, tasks, attendance, leaves, expenses. |
| 4 | **Vendor** | Limited access, can be assigned to services. |
| 5 | **Customer** | Front-end access: bookings, complaints, invoices, profile. |

---

### Role-wise Feature Access Matrix

| Feature | Admin | Manager | Employee | Vendor | Customer |
|---------|:-----:|:-------:|:--------:|:------:|:--------:|
| **Admin Dashboard** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **User Management** (ban, activate, KYC) | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Add Staff/Vendor** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Service Management** (CRUD) | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Category Management** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Items Management** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Parties Management** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Browse/Book Services** | ✅ | ✅ | ❌ | ❌ | ✅ |
| **Manage All Bookings** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **View Own Bookings** | ❌ | ❌ | ❌ | ❌ | ✅ |
| **Assign Bookings to Staff** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Generate Invoices** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **View/Pay Invoices** | ✅ | ✅ | ❌ | ❌ | ✅ |
| **File Complaints** | ✅ | ❌ | ❌ | ❌ | ✅ |
| **Manage Complaints** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Employee Dashboard** | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Check In/Out (Attendance)** | ❌ | ❌ | ✅ | ❌ | ❌ |
| **View Own Attendance** | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Apply for Leave** | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Manage Attendance (all staff)** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Approve/Reject Leaves** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Attendance Settings** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **My Tasks** | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Submit Expenses** | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Manage Expenses** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Reports & Analytics** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Notifications** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Profile Management** | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 5. Module Guide

### 5.1 Authentication & User Management

**Controller:** `Users.php` | **Model:** `User.php`

#### Registration & Login
- **Customer Registration**: Any visitor can register as a Customer (`role_id = 5`). On registration, status is set to `active`.
- **Staff/Vendor Registration**: Only Admins can create Employee (`role_id = 3`) or Vendor (`role_id = 4`) accounts via the "Add Staff/Vendor" page. Includes fields for designation, KYC document upload, and profile image.
- **Login**: Email + password authentication. Passwords are validated using `password_verify()`. Session stores `user_id`, `user_name`, `user_email`, and `role_id`.
- **Logout**: Destroys the active session.

#### User Management (Admin Only)
- **URL**: `/admin/users`
- **Tabs**: All Users | Vendors | Employees (filtered by role)
- **Actions per user**:
  - **Ban/Activate** user accounts
  - **KYC Verification**: Approve or Reject uploaded KYC documents
- **Profile Display**: Avatar (image or initial), name, email, designation, role badge, KYC status, account status

#### Navigation
- **Customer**: Top navbar dropdown → Dashboard, My Profile, Invoices, Logout
- **Employee**: Top navbar dropdown → Dashboard (links to Employee Dashboard), My Profile, Invoices, Logout
- **Admin**: Top navbar dropdown → Dashboard (links to Admin Panel), My Profile, Invoices, Logout

---

### 5.2 Admin Dashboard

**Controller:** `Admin.php` | **URL:** `/admin`

The Admin Dashboard is the central hub showing system-wide KPIs:

| Card | Data | Links To |
|------|------|----------|
| Total Users | Count of all registered users | `/admin/users` |
| Services | Count of active services | `/services/manage` |
| Inventory | Count of inventory items | `/inventories` |
| Revenue | Total revenue (₹) | `/reports` |

- **Recent Activity Feed**: Shows latest system actions
- **Financial Overview Chart**: Revenue visualization (Chart.js)

**Sidebar Navigation:**
Dashboard → Items → Parties → Bookings → Invoices → Add Staff/Vendor → Complaints → Expenses → Attendance → Reports

---

### 5.3 Services Management

**Controller:** `Services.php` | **Model:** `Service.php`

#### For Customers (Front-end)
- **Browse Services** (`/services`): View all services in a card-based catalog with images, descriptions, and pricing
- **Service Detail** (`/services/show/{id}`): Full service details with "Book Now" button
- **Search & Filter**: Filter services by category

#### For Admin (Back-end)
- **Manage Services** (`/services/manage`): Full CRUD table with status toggles
- **Add/Edit Services** (`/services/add`, `/services/edit/{id}`): Form with name, description, price, category, image upload
- **Category Management** (`/services/categories`): CRUD for service categories with icon picker
- **Parts/BOM Management** (`/services/parts/{id}`): Link inventory items to a service as required parts, specifying quantity needed per service execution

---

### 5.4 Items (Inventory & Products)

**Controller:** `Items.php` | **Model:** `Item.php`

A unified management system for all items:

| Field | Description |
|-------|-------------|
| Name | Item name |
| Type | `product` or `service` |
| SKU | Unique stock-keeping identifier |
| Unit | Measurement unit (pcs, kg, litre, etc.) |
| Sale Price | Selling price (₹) |
| Purchase Price | Cost price (₹) |
| Stock | Current stock quantity |
| Category | Item category |

**Features:**
- Full CRUD (Create, Read, Update, Delete)
- Tabbed view: All Items | Products | Services
- Stock tracking and low-stock indicators
- Category-based filtering

---

### 5.5 Parties Management

**Controller:** `Parties.php` | **Model:** `Party.php`

Manages business contacts and relationships:

| Field | Description |
|-------|-------------|
| Name | Party/company name |
| Type | `customer`, `supplier`, `vendor` |
| Email | Contact email |
| Phone | Contact number |
| GSTIN | GST Identification Number |
| Billing Address | Full address |
| Opening Balance | Initial account balance |

**Features:**
- Full CRUD operations
- Tabbed view: All | Customers | Suppliers | Vendors
- Transaction history per party
- Balance tracking

---

### 5.6 Bookings

**Controller:** `Bookings.php` | **Model:** `Booking.php`

#### Booking Lifecycle

```
Customer creates booking → Status: PENDING
      ↓
Admin reviews & assigns to Employee/Vendor → Status: CONFIRMED
      ↓
Employee completes the work → Status: COMPLETED
      ↓
Admin generates invoice → Status: INVOICED
```

#### Customer Features
- **Create Booking** (`/bookings/create`): Select service, date, time, address, notes
- **My Bookings** (`/bookings`): View all bookings with status tracking

#### Admin Features
- **Manage All Bookings** (`/bookings/manage`): View all bookings across the system
- **Assign Staff** (`/bookings/assign/{id}`): Assign Employee or Vendor to handle the booking
- **Update Status**: Change booking status (pending, confirmed, in_progress, completed, cancelled)

---

### 5.7 Invoices & Billing

**Controller:** `Invoices.php` | **Model:** `Invoice.php`

#### Invoice Generation
When a booking is marked `completed`, admin can generate an invoice:

```
Invoice Total = Service Price + Parts Cost (from BOM) + Tax
```

- **Auto-calculates** parts cost based on linked BOM items and their quantities
- Applies configurable tax rate
- Creates individual `invoice_items` line entries

#### Invoice Statuses
| Status | Meaning |
|--------|---------|
| `unpaid` | Invoice generated, awaiting payment |
| `paid` | Payment received and recorded |
| `cancelled` | Invoice voided |

#### Features
- **View Invoices** (`/invoices`): List all invoices with filters
- **Invoice Detail** (`/invoices/show/{id}`): Detailed breakdown with line items
- **Mark as Paid** (`/invoices/pay/{id}`): Records payment and decrements inventory stock
- **Print-ready**: Formatted for printing with company details
- **Currency**: All amounts displayed in ₹ (Indian Rupees)

---

### 5.8 Complaints

**Controller:** `Complaints.php` | **Model:** `Complaint.php`

#### Customer Features
- **File Complaint** (`/complaints/create`): Submit subject + detailed description
- **My Complaints** (`/complaints`): Track complaint status

#### Admin Features
- **View All Complaints** (`/complaints/index`): List with status filters
- **Assign Complaints**: Route complaints to employees for resolution
- **Update Status**: Open → In Progress → Resolved / Closed

---

### 5.9 Tasks

**Controller:** `Tasks.php` | **Model:** `Task.php`

Internal task management for employees:

| Field | Description |
|-------|-------------|
| Title | Task name |
| Description | Task details |
| Assigned To | Employee user ID |
| Priority | High, Medium, Low |
| Status | Pending, In Progress, Completed |

**Employee Dashboard** shows pending task count and recent tasks. Employees can view tasks via sidebar → "My Tasks".

---

### 5.10 Attendance & Leave Management

**Controller:** `AdminAttendance.php` (Admin), `Employees.php` (Employee) | **Model:** `Attendance.php`

This is a comprehensive HR module with two sides: Employee self-service and Admin management.

#### Employee Side

**Check In/Out Process:**
1. Employee logs in → navigates to Employee Dashboard
2. Clicks **"Check In"** button → records `check_in` time
3. System automatically calculates if late (based on shift settings + grace period)
4. At end of day, clicks **"Check Out"** → records `check_out` time
5. System auto-calculates: `work_hours`, `overtime_minutes`, and half-day detection

**My Attendance** (`/employees/attendance`):
- Monthly calendar with color-coded days:
  - 🟢 Present | 🟡 Late | 🟠 Half Day | 🔴 Absent | 🔵 On Leave | ⚪ Weekly Off
- Monthly stats: present count, absent count, late count, total hours
- Navigate between months

**My Leaves** (`/employees/my_leaves`):
- Leave balance display (Casual, Sick, Earned)
- Leave history table with status badges
- **Apply Leave** modal: select type, date range, reason
- Statuses: Pending → Approved / Rejected

#### Admin Side

**Attendance Dashboard** (`/adminAttendance`):
- Stats cards: Present today, Late today, Half-day, Pending leaves
- Date + Employee filters
- Attendance table: date, employee, check-in, check-out, hours, status, late minutes, source
- **Manual Entry** modal for adding attendance records

**Calendar View** (`/adminAttendance/calendar/{user_id}`):
- Monthly calendar per employee with color-coded days
- Attendance stats row
- Employee selector + month navigation

**Monthly Report** (`/adminAttendance/monthly_report`):
- Summary for all employees in a single table
- Columns: Present, Absent, Late, Half-day, Total Hours, Overtime, Attendance %
- Visual attendance percentage bar

**Leave Management** (`/adminAttendance/leaves`):
- Filter tabs: All | Pending | Approved | Rejected
- Approve/Reject leave requests with one click
- Leave type badges and approver info

**Settings** (`/adminAttendance/settings`):
- Configure shift start/end times
- Set late threshold (grace period in minutes)
- Set half-day threshold (minimum hours)
- Configure weekly off days

---

### 5.11 Expenses

**Controller:** `AdminExpenses.php` (Admin), `Employees.php` (Employee) | **Model:** `Expense.php`

- **Employees** can submit expense claims with description, amount, and receipt upload
- **Admin** reviews all expense claims via the Expenses page in the admin panel
- Status flow: Pending → Approved / Rejected

---

### 5.12 Reports & Analytics

**Controller:** `Reports.php` | **URL:** `/reports`

Financial dashboard with:

| Metric | Source |
|--------|--------|
| **Total Income** (₹) | Sum of paid invoices |
| **Total Expenses** (₹) | Sum of approved expense claims |
| **Net Profit** (₹) | Income − Expenses |

**Charts:**
- Monthly revenue trend (Chart.js bar or line chart)
- Service-wise revenue breakdown

**Additional Reports:**
- Top performing services
- Low stock alerts (items with stock ≤ 5)

---

### 5.13 Notifications

**Controller:** `Notifications.php` | **Model:** `Notification.php`

- Bell icon in the top navbar displays unread notification count
- Dropdown shows recent notifications with type-based styling
- Click notification → marks as read and redirects
- System events that trigger notifications:
  - New booking created
  - Booking status changed
  - Invoice generated
  - Complaint assigned
  - Leave request status update

---

### 5.14 Teams

**Controller:** `Teams.php` | **Model:** `Team.php`

- Manage departments/teams within the organization
- Assign employees to teams
- Basic CRUD operations

---

## 6. Key Workflows

### 6.1 Complete Service Workflow (End-to-End)

```
┌─────────────┐    ┌───────────────┐    ┌──────────────┐    ┌────────────────┐
│  CUSTOMER   │───→│    ADMIN      │───→│   EMPLOYEE   │───→│     ADMIN      │
│ Books Service│    │ Assigns Staff │    │ Completes Job│    │ Generates Bill │
│ (Pending)   │    │ (Confirmed)   │    │ (Completed)  │    │ (Invoice)      │
└─────────────┘    └───────────────┘    └──────────────┘    └────────────────┘
                                                                    │
                                                            ┌───────┴───────┐
                                                            │   CUSTOMER    │
                                                            │ Pays Invoice  │
                                                            │ (Stock Updated)│
                                                            └───────────────┘
```

### 6.2 Employee Daily Workflow

```
Login → Dashboard → Check In → View Tasks → Complete Tasks → Check Out → Logout
                        │
                        └── Auto-detects late arrival
                        └── Auto-calculates work hours & overtime
```

### 6.3 Invoice Auto-Calculation

```
Invoice Total = Service Base Price
              + Σ (Part Unit Price × BOM Quantity)
              + Tax Amount
              ─────────────────────────────────
              = Final Amount (₹)
```

### 6.4 Leave Request Workflow

```
Employee applies → Status: PENDING → Admin reviews
     ↓                                    ↓
   Waits              ┌─────────────┬─────────────┐
                      │  APPROVED ✅ │  REJECTED ❌ │
                      └─────────────┴─────────────┘
```

---

## 7. Security

| Feature | Implementation |
|---------|---------------|
| **Password Hashing** | `password_hash()` with Bcrypt algorithm |
| **SQL Injection Prevention** | PDO prepared statements with parameter binding |
| **Input Sanitization** | `filter_input_array()` on all form inputs |
| **Session-based Auth** | Role ID stored in `$_SESSION['role_id']` |
| **Route Protection** | Controller constructors check login status + role |
| **CSRF** | Form-based submissions with session validation |

### Access Control Pattern (Code)
```php
// Every admin controller checks:
public function __construct(){
    if(!isLoggedIn()){ redirect('users/login'); }
    if($_SESSION['role_id'] != 1){ redirect('pages/index'); }
}
```

---

## 8. Configuration

All configuration is in `app/config/config.php`:

```php
// Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'sms_db');

// App
define('APPROOT', dirname(dirname(__FILE__)));
define('URLROOT', 'http://localhost:8080/Service Management System');
define('SITENAME', 'SMS');
```

---

## 9. Setup & Installation

### Prerequisites
- PHP 7.4+ with PDO extension
- MySQL 5.7+ or MariaDB
- Apache with `mod_rewrite` enabled (XAMPP recommended)

### Steps

1. **Clone/Copy** the project to your web server directory:
   ```
   C:\xampp\htdocs\Service Management System\
   ```

2. **Create the database**:
   ```sql
   CREATE DATABASE sms_db;
   ```

3. **Import SQL files** (in order):
   ```
   database.sql                    → Core tables (users, roles, services, bookings, etc.)
   database_services.sql           → Services & categories
   database_bookings.sql           → Bookings table enhancements
   database_complaints_tasks.sql   → Complaints & tasks
   database_inventory.sql          → Inventory/products tables
   database_items_parties.sql      → Items & parties tables
   database_users_update.sql       → User table enhancements (KYC, profile, designation)
   database_team.sql               → Teams table
   ```

4. **Update configuration** in `app/config/config.php`:
   - Set `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`
   - Set `URLROOT` to match your server

5. **Run migrations** (browser):
   ```
   http://localhost:8080/Service Management System/run_migration_attendance.php
   ```

6. **Seed sample data** (optional — browser):
   ```
   http://localhost:8080/Service Management System/seeder
   ```

7. **Default Admin Login**:
   ```
   Email: admin@sms.com
   Password: password123
   ```

---

> **Built with ❤️ for Service-Based Businesses**
