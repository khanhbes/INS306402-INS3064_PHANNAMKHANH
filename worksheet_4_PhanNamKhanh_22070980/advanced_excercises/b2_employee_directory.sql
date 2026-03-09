-- Tạo bảng employees (Nhân sự)
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    department ENUM('Sales', 'IT', 'HR', 'Marketing') NOT NULL, -- Giới hạn phòng ban
    salary DECIMAL(15, 2) NOT NULL,                             -- Tránh lỗi làm tròn khi tính lương
    hire_date DATE NOT NULL                                     -- Lưu ngày tháng năm
);