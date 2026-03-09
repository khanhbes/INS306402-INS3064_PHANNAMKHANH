-- Tạo bảng events (Sự kiện)
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    start_time DATETIME NOT NULL,     -- Lưu chính xác ngày và giờ bắt đầu
    end_time DATETIME NOT NULL,       -- Lưu chính xác ngày và giờ kết thúc
    event_details JSON                -- Lưu trữ linh hoạt (khách mời, yêu cầu...)
);