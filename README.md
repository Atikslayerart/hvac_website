# CoolAir HVAC Website

A responsive PHP and MySQL website for **CoolAir HVAC**, a company providing HVAC, electrical, and structural solutions.

The website allows clients to explore services and projects, register and log in, book appointments, raise complaints, and track their requests. It also includes an admin panel for managing clients, appointments, complaints, projects, services, and website content.

## Features

### Client Features

- Home page with company introduction and highlights
- Services page
- Projects page
- About page
- Contact page
- User registration and login
- Appointment booking
- Complaint submission
- View personal appointment and complaint requests
- Profile management

### Admin Features

- Admin login
- Client management
- Appointment management
- Complaint management
- Project management
- Service management
- Website content management
- Dashboard overview

## Technologies Used

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- Bootstrap
- Font Awesome
- phpMyAdmin
- XAMPP

## Project Structure

```text
innovation-website/
├── all_pages/
│   ├── index.php
│   ├── about.php
│   ├── services.php
│   ├── projects.php
│   ├── contact.php
│   ├── schedule.php
│   └── ...
├── includes/
│   ├── header.php
│   └── footer.php
├── database/
│   ├── database.sql
│   └── db_connect.php
├── login/
│   ├── login.php
│   ├── register.php
│   └── ...
├── admin/
│   └── ...
└── assets/
    ├── css/
    ├── js/
    └── images/
```

## Requirements

Before running this project, install the following:

- XAMPP
- PHP 8.0 or higher
- MySQL or MariaDB
- A web browser such as Chrome, Edge, or Firefox

XAMPP includes Apache, PHP, MySQL/MariaDB, and phpMyAdmin, which are required to run the website locally.

## Installation

### 1. Install XAMPP

Download and install XAMPP from:

https://www.apachefriends.org/

After installation, open the **XAMPP Control Panel**.

Start:

- Apache
- MySQL

### 2. Move the Project Folder

Copy or move the project folder to:

```text
C:\xampp\htdocs\
```

Your project folder should look like this:

```text
C:\xampp\htdocs\innovation-website\
```

### 3. Create the Database

1. Open your browser.
2. Go to:

```text
http://localhost/phpmyadmin
```

3. Click **New** in the left sidebar.
4. Enter the database name:

```text
hvac_company
```

5. Select **utf8mb4_unicode_ci** as the collation.
6. Click **Create**.

### 4. Import the SQL File

1. Select the `hvac_company` database in phpMyAdmin.
2. Click the **Import** tab.
3. Click **Choose File**.
4. Select the SQL file included in the project:

```text
database/database.sql
```

5. Click **Import**.
6. Wait until the import is completed.

### 5. Configure the Database Connection

Open this file:

```text
database/db_connect.php
```

Update the database settings if needed:

```php
$host = 'localhost';
$dbname = 'hvac_company';
$username = 'root';
$password = '';
```

By default, XAMPP uses:

- Host: `localhost`
- Username: `root`
- Password: empty
- Database name: `hvac_company`

## Run the Website

After starting Apache and MySQL, open this URL in your browser:

```text
http://localhost/innovation-website/all_pages/index.php
```

You can also open the main website using:

```text
http://localhost/innovation-website/
```

## Default URLs

| Page | URL |
|---|---|
| Home | `http://localhost/innovation-website/all_pages/index.php` |
| Services | `http://localhost/innovation-website/all_pages/services.php` |
| Projects | `http://localhost/innovation-website/all_pages/projects.php` |
| Contact | `http://localhost/innovation-website/all_pages/contact.php` |
| Register | `http://localhost/innovation-website/login/register.php` |
| Login | `http://localhost/innovation-website/login/login.php` |
| Admin Login | `http://localhost/innovation-website/admin/login.php` |

## User Roles

### Client

Clients can register, log in, book appointments, submit complaints, view their requests, and update their profile.

### Admin

Admins can manage clients, appointments, complaints, projects, services, and website content through the admin dashboard.

## Security Notes

- Passwords are stored securely using PHP password hashing.
- Forms use CSRF protection.
- Database queries use prepared statements to reduce SQL injection risk.
- User input is escaped when displayed on web pages.
- Database credentials should not be uploaded publicly to GitHub.

## GitHub Setup

Before uploading to GitHub, make sure the following files are not uploaded:

```text
/database/hvac_company.sql
/database/db_connect.php
/login/config/database.php
```

Create a `.gitignore` file in the root folder and add:

```gitignore
/database/hvac_company.sql
/database/db_connect.php
/login/config/database.php
/vendor/
/node_modules/
.DS_Store
Thumbs.db
*.log
```

The `database/database.sql` file should contain only the database structure and safe sample data. Do not include real client details, passwords, phone numbers, emails, or private business data.

## Contributing

If you want to contribute:

1. Fork the repository.
2. Create a new branch.
3. Make your changes.
4. Test the website locally.
5. Submit a pull request.

## License

This project is created for educational and business website development purposes.

You can add a license based on your preference, such as MIT License.