# Installation Guide

This guide explains how to install and run the MoguMogu Meal Tracker on a Raspberry Pi Zero 2W running DietPi (or any Debian-based Linux system).

## Requirements

- Raspberry Pi Zero 2W (or any Debian-based Linux)
- Internet connection (for initial setup only)
- Git installed on your local machine

---

## Step 1 : Update the System

```
sudo apt update && sudo apt upgrade -y
```

---

## Step 2 : Install Apache, PHP, and MariaDB

```
sudo apt install apache2 php libapache2-mod-php php-mysql mariadb-server git -y
```

---

## Step 3 : Start and Enable Services

```
sudo systemctl start apache2
sudo systemctl enable apache2
sudo systemctl start mariadb
sudo systemctl enable mariadb
```

Verify both are running:

```
sudo systemctl status apache2
sudo systemctl status mariadb
``` 
---

## Step 4 : Set Up the Database

Log into MariaDB:

```
sudo mysql -u root
```

Run the following SQL commands:

```sql
CREATE DATABASE Mogumogu;
USE Mogumogu;

CREATE TABLE meals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  meal_name VARCHAR(100) NOT NULL,
  meal_type VARCHAR(50) NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  health INT DEFAULT 100,
  streak INT DEFAULT 0,
  last_fed DATE,
  last_health_update DATE,
  longest_streak INT DEFAULT 0,
  total_days_logged INT DEFAULT 0
);

CREATE TABLE members (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  student_id VARCHAR(50),
  department VARCHAR(100),
  university VARCHAR(100),
  about_me TEXT,
  contributions TEXT
);

EXIT;
```
---

## Step 5 : Clone the Repository

```
cd /var/www/html
sudo git clone https://github.com/pyc10111tw/OpenSource-Mogumogu.git
```
---

## Step 6 : Import the Database Schema

```
sudo mysql -u root Mogumogu < /var/www/html/sql/schema.sql
```

---

## Step 7 : Configure the Upload Folder

```
sudo mkdir -p /var/www/html/uploads
sudo chown -R www-data:www-data /var/www/html/uploads
sudo chmod 755 /var/www/html/uploads
```
---

## Step 8 : Set File Permissions

```
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
```
---

## Step 9 : Verify Installation

Find your Pi's IP address:

```
hostname -I
```

Open a browser and go to:

```
https://github.com/pyc10111tw/OpenSource-Mogumogu.git
```

You should see the MoguMogu home page with your virtual pet.

---

## Troubleshooting:
**Database connection error on MacBook (localhost not working):**
- Open `db.php` and change:
```php
$host = "localhost";
```
to:
```php
$host = "127.0.0.1";
```
> On some macOS setups, `localhost` tries to connect via a Unix socket instead of TCP, which causes the connection to fail. Using `127.0.0.1` forces it to connect over TCP instead.

**Apache won't start:**
```
sudo mkdir -p /var/log/apache2
sudo systemctl restart apache2
```

**Database connection error:**
- Verify MariaDB is running: `sudo systemctl status mariadb`
- Check that the database name is exactly `Mogumogu` (capital M)
- Make sure the schema was imported: `sudo mysql -u root -e "SHOW TABLES;" Mogumogu`

**Photos not uploading:**
- Check the `uploads/` folder exists and is writable
- Run: `sudo chown -R www-data:www-data /var/www/html/uploads`

**Permission issues:**

```
sudo chown -R www-data:www-data /var/www/html
```