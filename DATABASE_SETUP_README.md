# Devesh Logistics Database Setup Guide

This guide will help you set up the SQL database for storing booking and contact form data.

## Prerequisites

Since you already have MySQL installed, you just need:
1. **Apache Web Server** - To serve your PHP files
2. **PHP 7.4 or higher** - To run the PHP scripts
3. **MySQL** - ✅ You already have this!

## Setup Options

### Option 1: Use Existing MySQL + Install Apache & PHP Separately
If you want to use your existing MySQL:
1. **Install Apache** from: https://httpd.apache.org/download.cgi
2. **Install PHP** from: https://www.php.net/downloads.php
3. Configure Apache to work with PHP
4. Use your existing MySQL installation

### Option 2: Install XAMPP (Recommended - Easier Setup)
Even though you have MySQL, XAMPP makes setup much easier:
1. Download XAMPP from: https://www.apachefriends.org/
2. During installation, you can choose to use your existing MySQL or XAMPP's MySQL
3. Start Apache and MySQL services
4. Access phpMyAdmin at: http://localhost/phpmyadmin

### Option 3: Use Your Existing MySQL + Lightweight Server
1. Install **WAMP** (Windows + Apache + MySQL + PHP) from: http://www.wampserver.com/
2. Or use **Laragon** (lightweight): https://laragon.org/

## Quick Start (Recommended)

Since you already have MySQL, I recommend **Option 2 (XAMPP)** because:
- ✅ Easy one-click installation
- ✅ Apache + PHP configured automatically  
- ✅ Can use your existing MySQL or XAMPP's MySQL
- ✅ Includes phpMyAdmin for easy database management
- ✅ No complex configuration needed

### Step 1: Check Your Current MySQL
First, let's verify your MySQL is working:
1. Open Command Prompt as Administrator
2. Try to connect to MySQL:
   ```
   mysql -u root -p
   ```
3. If it works, note down:
   - Your MySQL username (usually 'root')
   - Your MySQL password
   - MySQL port (usually 3306)

### Step 2: Install XAMPP (Recommended)
1. Download XAMPP from: https://www.apachefriends.org/
2. During installation, when it asks about MySQL, you can:
   - Use XAMPP's MySQL (easier) 
   - OR configure it to use your existing MySQL
3. Start Apache service (you need this for PHP)
4. You can choose whether to start XAMPP's MySQL or use your own
1. Open phpMyAdmin in your browser
2. Click on "SQL" tab
3. Copy and paste the contents of `database/devesh_logistics.sql`
4. Click "Go" to execute the SQL commands

**OR** you can create manually:
1. Create a new database named `devesh_logistics`
2. Import the `database/devesh_logistics.sql` file

### Step 3: Create Database
**Option A: Using phpMyAdmin (if you install XAMPP)**
1. Open phpMyAdmin in your browser: http://localhost/phpmyadmin
2. Click on "SQL" tab
3. Copy and paste the contents of `database/devesh_logistics.sql`
4. Click "Go" to execute the SQL commands

**Option B: Using MySQL Command Line (if using your existing MySQL)**
1. Open Command Prompt as Administrator
2. Connect to MySQL:
   ```
   mysql -u root -p
   ```
3. Create the database:
   ```sql
   CREATE DATABASE devesh_logistics;
   USE devesh_logistics;
   ```
4. Import the SQL file:
   ```
   source C:\path\to\your\project\database\devesh_logistics.sql
   ```

**Option C: Using MySQL Workbench (if you have it)**
1. Open MySQL Workbench
2. Connect to your MySQL server
3. Create new schema: `devesh_logistics`
4. Import the `devesh_logistics.sql` file

### Step 4: Configure Database Connection
1. Open `config/database.php`
2. Update the database credentials:
   ```php
   private $host = 'localhost';        // Your MySQL host
   private $db_name = 'devesh_logistics'; // Database name
   private $username = 'root';         // Your MySQL username
   private $password = '';             // Your MySQL password
   private $port = 3306;               // MySQL port
   ```

### Step 4: Test the Setup
1. Place all files in your web server directory (e.g., `C:\xampp\htdocs\devesh-logistics\`)
2. Access your website: `http://localhost/devesh-logistics/`
3. Test the booking form: `http://localhost/devesh-logistics/book.html`
4. Test the contact form: `http://localhost/devesh-logistics/contact.html`
5. Access admin panel: `http://localhost/devesh-logistics/admin.html`

## File Structure

```
devesh-logistics/
├── config/
│   └── database.php              # Database configuration
├── database/
│   └── devesh_logistics.sql      # Database schema and sample data
├── admin.html                    # Admin panel for managing data
├── admin-api.php                 # API endpoints for admin operations
├── booking-handler.php           # Handles booking form submissions
├── contact-form-handler.php      # Handles contact form submissions
├── book.html                     # Updated booking form
├── contact.html                  # Contact form (unchanged)
├── index.html                    # Main page
├── style.css                     # Existing styles
└── images/                       # Image assets
```

## Database Tables

### 1. `bookings` Table
Stores all booking requests with the following fields:
- `id` - Primary key
- `pickup_address` - Pickup location
- `drop_address` - Drop location
- `pickup_date` - Scheduled pickup date
- `phone` - Customer phone number
- `vehicle_type` - Type of vehicle requested
- `fullname` - Customer name
- `email` - Customer email
- `booking_status` - Status (pending, confirmed, in_progress, completed, cancelled)
- `created_at` - Timestamp when booking was created
- `updated_at` - Timestamp when booking was last updated

### 2. `contact_messages` Table
Stores all contact form messages:
- `id` - Primary key
- `name` - Sender name
- `email` - Sender email
- `subject` - Message subject
- `message` - Message content
- `status` - Status (new, read, replied)
- `created_at` - Timestamp when message was sent
- `updated_at` - Timestamp when message was last updated

## Features

### Form Handling
- ✅ Data validation and sanitization
- ✅ Error handling and user feedback
- ✅ SQL injection prevention using prepared statements
- ✅ Email format validation
- ✅ Phone number validation
- ✅ Date validation

### Admin Panel
- ✅ View all bookings and messages
- ✅ Search functionality
- ✅ Update booking status
- ✅ Update message status
- ✅ Delete records
- ✅ Statistics dashboard
- ✅ Real-time data updates

### Security Features
- ✅ Prepared statements for SQL queries
- ✅ Input validation and sanitization
- ✅ CORS headers for API security
- ✅ Error handling

## Usage

### Customer Side:
1. **Booking**: Customers fill the booking form on `book.html`
2. **Contact**: Customers send messages via `contact.html`
3. **Confirmation**: Customers receive success/error messages

### Admin Side:
1. **Dashboard**: View statistics at `admin.html`
2. **Manage Bookings**: Update status, view details, delete records
3. **Manage Messages**: Read messages, update status, reply to customers
4. **Search**: Find specific bookings or messages quickly

## Troubleshooting

### Common Issues:

1. **Database Connection Error**:
   - Check if MySQL is running
   - Verify database credentials in `config/database.php`
   - Ensure database `devesh_logistics` exists

2. **Form Not Submitting**:
   - Check if Apache is running
   - Verify file permissions
   - Check browser console for JavaScript errors

3. **Admin Panel Not Loading Data**:
   - Check if PHP files are accessible
   - Verify database connection
   - Check browser network tab for API errors

### Testing Database Connection:
Create a test file `test-db.php`:
```php
<?php
require_once 'config/database.php';
$conn = getDBConnection();
if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Database connection failed!";
}
?>
```

## Security Recommendations

1. **Change Default Credentials**: Update MySQL root password
2. **Restrict Admin Access**: Add authentication to admin panel
3. **Use HTTPS**: Enable SSL in production
4. **Regular Backups**: Backup database regularly
5. **Update PHP**: Keep PHP and MySQL updated

## Additional Features You Can Add

1. **Email Notifications**: Send confirmation emails to customers
2. **SMS Notifications**: Send SMS updates for booking status
3. **Payment Integration**: Add payment gateway
4. **Customer Dashboard**: Let customers track their bookings
5. **Reporting**: Generate PDF reports for bookings
6. **API Integration**: Connect with logistics partners

## Support

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify all files are in the correct location
3. Check Apache and MySQL logs for errors
4. Ensure PHP extensions (PDO, MySQL) are enabled

The system is now ready to store and manage your booking and contact data efficiently!