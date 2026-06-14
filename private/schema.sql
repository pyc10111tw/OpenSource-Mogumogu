--CREATE DATABASE Mogumogu;
--USE Mogumogu;

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