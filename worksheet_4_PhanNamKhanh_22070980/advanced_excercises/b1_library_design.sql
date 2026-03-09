-- Tạo bảng books (Sách)
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    isbn VARCHAR(20) UNIQUE NOT NULL,  -- Dùng VARCHAR để bảo toàn số 0 ở đầu
    published_year INT
);

-- Tạo bảng members (Thành viên)
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL         -- Dùng VARCHAR lưu định dạng quốc tế/số 0 đầu tiên
);

-- Tạo bảng borrow_records (Lịch sử mượn trả)
CREATE TABLE borrow_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    member_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (member_id) REFERENCES members(id)
);