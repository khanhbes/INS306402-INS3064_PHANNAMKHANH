-- Tạo database quản lý sinh viên với collation utf8mb4_unicode_ci
CREATE DATABASE IF NOT EXISTS student_management_db
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE student_management_db;

-- Tạo bảng classes (Lớp học)
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Khóa chính tự tăng
    class_name VARCHAR(255) NOT NULL,  -- Tên lớp không được để trống
    department VARCHAR(255)            -- Khoa
);

-- Tạo bảng students (Sinh viên)
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_code VARCHAR(50) UNIQUE,   -- Mã sinh viên là duy nhất
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL, -- Email duy nhất và không được trống
    age INT,                           -- Tuổi (số nguyên)
    class_id INT,                      -- Khóa ngoại tham chiếu đến classes.id
    FOREIGN KEY (class_id) REFERENCES classes(id)
);