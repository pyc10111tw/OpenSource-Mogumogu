# Installation Guide

This guide explains how to install and run the MoguMogu Meal Tracker on a Raspberry Pi Zero 2W running DietPi (or any Debian-based Linux system).

## Requirements

- Raspberry Pi Zero 2W (or any Debian-based Linux)
- Internet connection (for initial setup only)
- Git installed on your local machine

---

## Step 1: Update the System

```
sudo apt update
sudo apt upgrade -y
```

---

## Step 2: Install Apache, PHP, MariaDB, and Git

```
sudo apt install apache2 php php-mysql php8.4-fpm mariadb-server git -y
```

---

### Step 2-1: Enable PHP-FPM for Apache

```
sudo a2enmod proxy_fcgi setenvif
sudo a2enconf php8.4-fpm
sudo systemctl restart apache2
```

---

## Step 3: Start and Enable Services

```
sudo systemctl start apache2
sudo systemctl enable apache2
sudo systemctl start mariadb
sudo systemctl enable mariadb
```

---

### Step 3-1: Verify Installation

Verify MariaDB is installed:

```
mariadb --version
```

Verify Apache is running:

```
sudo ss -tlnp | grep :80
```

Find the Raspberry Pi IP address:

```
hostname -I
```

Open a browser and visit:

```
http://<Pi-IP>/
```

You should see the default Apache web page.

---

## Step 4: Clone the Repository

```
cd ~
git clone https://github.com/pyc10111tw/OpenSource-Mogumogu.git
cd OpenSource-Mogumogu
```
---

## Step 5: Configure Apache DocumentRoot

Open the Apache configuration file:

```
sudo nano /etc/apache2/sites-available/000-default.conf
```

Find the line:

```
DocumentRoot /var/www/html
```

Change it to:

```
DocumentRoot /home/dietpi/OpenSource-Mogumogu/public
```

Save the file and restart Apache:

```
sudo systemctl restart apache2
```

---

### Step 5-1: Configure Apache Directory Permissions

Open the Apache configuration file:

```
sudo nano /etc/apache2/apache2.conf
```

Add the following block at the end of the file:

```
<Directory /home/dietpi/OpenSource-Mogumogu/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

Save the file and restart Apache:

```
sudo systemctl restart apache2
```

---

## Step 6: Set Up the Database

Log into MariaDB:

```
sudo mariadb
```

Run the following SQL commands:

```sql
CREATE DATABASE Mogumogu;

CREATE USER 'mogumogu'@'localhost' IDENTIFIED BY '1234';

GRANT ALL PRIVILEGES ON Mogumogu.* TO 'mogumogu'@'localhost';

FLUSH PRIVILEGES;

EXIT;
```
---

## Step 7: Import the Database Schema

Make sure you are in the project directory:

```
cd ~/OpenSource-Mogumogu
```

Import the schema:

```
sudo mariadb Mogumogu < private/schema.sql
```

---

### Step 7-1: Initialize Contributor Data

Run the member setup script once:

```
php private/member_setup.php
```

This script inserts the contributor information into the members table.

---

## Step 8: Configure Upload Permissions

```
sudo chmod -R 755 /home/dietpi/OpenSource-Mogumogu/public/upload
```

---

## Step 9: Verify Installation

Open a browser and go to:

```
http://<Pi-IP>/
```

You should see the MoguMogu home page with your virtual pet.

---

## Troubleshooting:

**Database connection error:**
- Verify MariaDB is running:
  
  ```
  sudo systemctl status mariadb
  ```
  
- Check that the database name is exactly `Mogumogu` (capital M)
- Make sure the schema was imported:
  
  ```
  sudo mysql -u root -e "SHOW TABLES;" Mogumogu
  ```
  
- Make sure the database credentials in private/db.php match the MariaDB user created in Step 6.

**Photos not uploading:**
- Check the uploads folder exists `/home/dietpi/OpenSource-Mogumogu/public/upload`
- Make sure it is writable:

  ```
  sudo chmod -R 777 /home/dietpi/OpenSource-Mogumogu/public/upload
  ```

**403 Forbidden after changing DocumentRoot:**
- Make sure Step 5-1 was completed correctly.
- Verify that the following block exists in /etc/apache2/apache2.conf:
  
  ```
  <Directory /home/dietpi/OpenSource-Mogumogu/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
  </Directory>
  ```
  
- Restart Apache:
  
  ```
  sudo systemctl restart apache2
  ```
