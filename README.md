# Un-tren - Train Reservation System

A comprehensive PHP-based train reservation and booking system that provides both user and administrator functionalities for managing train schedules, seat reservations, and passenger bookings.

## 📋 Table of Contents

- [Features](#features)
- [Project Overview](#project-overview)
- [Technology Stack](#technology-stack)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
  - [User Features](#user-features)
  - [Administrator Features](#administrator-features)
- [Core Components](#core-components)
- [Database](#database)
- [Security](#security)
- [Contributors](#contributors)
- [License](#license)

## ✨ Features

### User Features
- **User Registration & Authentication**: Secure signup and login system
- **Train Schedule Browsing**: View available train schedules and routes
- **Seat Selection**: Interactive seat selection interface for train carriages
- **Booking Management**: Create, view, and manage train reservations
- **Personal Booking History**: Access detailed records of past and current bookings
- **Carriage Information**: View carriage details and seat availability

### Administrator Features
- **Administrative Dashboard**: Comprehensive admin control panel
- **Train Schedule Management**: Create and modify train schedules
- **Carriage Management**: Configure train carriages and seat layouts
- **Booking Oversight**: Monitor and manage all passenger reservations
- **Admin Protection**: Restricted access with authentication

## 📊 Project Overview

**Un-tren** is a modern train reservation system designed to streamline the booking process for both passengers and railway administrators. The system supports multi-carriage trains with customizable seat layouts and provides a user-friendly interface for browsing schedules and making reservations.

## 🛠 Technology Stack

- **Language**: PHP (100%)
- **Server-Side**: PHP
- **Database**: MySQL/MariaDB
- **Frontend**: HTML, CSS, JavaScript
- **License**: The Unlicense (Public Domain)

## 📁 Project Structure

```
Un-tren/
├── LICENSE                      # License file
├── README.md                    # This file
└── Train_reserve/               # Main application directory
    ├── admin_page.php           # Administrator dashboard
    ├── admin_protection.php     # Admin authentication & protection
    ├── carriage.php             # Carriage management
    ├── connect.php              # Database connection
    ├── index.php                # Home page / landing page
    ├── login.php                # User login page
    ├── my_booking.php           # User booking history and management
    ├── reserve.php              # Reservation/booking creation
    ├── schedule.php             # Train schedule viewing
    ├── seat.php                 # Seat selection interface
    ├── signup.php               # User registration page
    └── assets/                  # Static assets (CSS, JS, images)
```

## 🚀 Installation

### Prerequisites
- PHP 7.0 or higher
- MySQL/MariaDB database server
- Web server (Apache, Nginx, etc.)
- Web browser (modern, JavaScript-enabled)

### Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/koolmakmak/Un-tren.git
   cd Un-tren
   ```

2. **Set Up Web Server**
   - Copy the `Train_reserve` directory to your web server's document root
   - For Apache: typically `/var/www/html/` on Linux or `C:\xampp\htdocs\` on Windows
   - For Nginx: configure your server block to point to the `Train_reserve` directory

3. **Create Database**
   - Create a MySQL database for the application
   - Import any required database schema (check database initialization files if available)

4. **Configure Database Connection**
   - Edit `Train_reserve/connect.php` with your database credentials:
     ```php
     // Database configuration
     $host = "localhost";
     $user = "your_database_user";
     $password = "your_database_password";
     $database = "your_database_name";
     ```

5. **Access the Application**
   - Open your browser and navigate to: `http://localhost/Train_reserve/`

## ⚙️ Configuration

### Database Connection (`connect.php`)

The main database connection file contains credentials for MySQL connectivity. Update this file with your database details:

```php
$host = "localhost";
$user = "your_user";
$password = "your_password";
$database = "your_database";
```

### Admin Protection

Admin access is protected by `admin_protection.php`. Users must authenticate before accessing administrative features.

## 📖 Usage

### User Features

1. **Sign Up / Register**
   - Navigate to the signup page
   - Enter required information (username, email, password)
   - Submit the registration form

2. **Login**
   - Click "Login" on the homepage
   - Enter your credentials
   - Access your user dashboard

3. **Browse Train Schedules**
   - Visit the Schedule page (`schedule.php`)
   - View all available train routes and departure times
   - Filter by date, route, or other criteria

4. **Make a Reservation**
   - Select a train from available schedules
   - Choose your desired carriage (`carriage.php`)
   - Select available seats (`seat.php`)
   - Complete the booking process (`reserve.php`)

5. **View Your Bookings**
   - Access "My Bookings" page (`my_booking.php`)
   - View all current and past reservations
   - Modify or cancel bookings (if applicable)

### Administrator Features

1. **Access Admin Panel**
   - Navigate to admin page (`admin_page.php`)
   - Authenticate using admin credentials

2. **Manage Train Schedules**
   - Create new train schedules
   - Update existing routes and times
   - Remove discontinued services

3. **Configure Carriages**
   - Set up train carriage configurations
   - Define seat layouts and availability
   - Manage carriage capacity

4. **Monitor Reservations**
   - View all active bookings
   - Track passenger information
   - Generate reports

## 🔧 Core Components

### `index.php`
The main entry point and landing page of the application. Provides navigation to all major features.

### `login.php` & `signup.php`
User authentication system with registration and login functionality. Manages user sessions and credentials.

### `schedule.php`
Displays available train schedules. Users can browse and filter schedules before making reservations.

### `carriage.php`
Manages train carriage information and configuration. Displays details about each carriage including capacity and amenities.

### `seat.php`
Interactive seat selection interface. Allows users to view seat availability and select their preferred seats for a reservation.

### `reserve.php`
Handles the booking process. Collects passenger information and finalizes train reservations.

### `my_booking.php`
User account management for reservations. Displays booking history, status, and allows modifications.

### `admin_page.php`
Main administrative control panel. Provides dashboard for managing trains, schedules, and bookings.

### `admin_protection.php`
Security layer for administrative access. Ensures only authorized users can access admin features.

### `connect.php`
Database connectivity. Establishes connection to MySQL/MariaDB database for all data operations.

## 💾 Database

The application requires a MySQL/MariaDB database with tables for:
- Users (registration and authentication)
- Train Schedules (routes, times, dates)
- Carriages (train car configurations)
- Seats (availability and assignments)
- Bookings (passenger reservations)

**Note**: Database schema initialization scripts should be run before first use.

## 🔒 Security

- **Admin Protection**: Restricted admin access with authentication (`admin_protection.php`)
- **User Sessions**: Session-based authentication for secure user access
- **Database Credentials**: Store sensitive credentials in `connect.php` securely
- **Input Validation**: Implement proper validation for all user inputs (recommended)
- **SQL Injection Prevention**: Use prepared statements (recommended review)

### Security Recommendations

1. Keep database credentials secure and never commit to public repositories
2. Use environment variables or configuration files outside the web root
3. Implement input validation and sanitization
4. Use prepared statements to prevent SQL injection
5. Enable HTTPS/SSL for production environments
6. Regularly update PHP and database server
7. Implement rate limiting for login attempts
8. Use strong password requirements for admin accounts

## 👥 Contributors

This project was developed collaboratively by multiple contributors including:
- **koolmakmak** (Repository Owner)
- **fahatlegend1**
- **Kaannoiz**
- **0prj0**

Recent commits show collaborative work including refinements and feature additions across multiple modules.

## 📝 License

This project is licensed under **The Unlicense**, which places the software in the public domain. You are free to use, modify, and distribute this software without restriction.

For full license details, see the [LICENSE](LICENSE) file.

---

## 🤝 Contributing

Contributions are welcome! Please feel free to:
1. Fork the repository
2. Create a feature branch
3. Make your improvements
4. Submit a pull request

## 📞 Support

For issues, questions, or suggestions, please open an issue on the GitHub repository.

---

**Last Updated**: March 30, 2026

**Repository**: [koolmakmak/Un-tren](https://github.com/koolmakmak/Un-tren)
