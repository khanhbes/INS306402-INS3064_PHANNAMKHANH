-- Library Management Database Schema
-- Tạo database
CREATE DATABASE IF NOT EXISTS library_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE library_db;

-- Tạo bảng books
CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) DEFAULT NULL,
    publication_year INT DEFAULT NULL,
    available_copies INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tạo bảng borrow_transactions (giao dịch mượn sách)
CREATE TABLE IF NOT EXISTS borrow_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    borrower_name VARCHAR(255) NOT NULL,
    borrower_email VARCHAR(255) DEFAULT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    status ENUM('borrowed', 'returned', 'overdue') NOT NULL DEFAULT 'borrowed',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu books
INSERT INTO books (isbn, title, author, publisher, publication_year, available_copies) VALUES
('978-0-13-468599-1', 'The Pragmatic Programmer', 'David Thomas, Andrew Hunt', 'Addison-Wesley', 2019, 5),
('978-0-596-51774-8', 'JavaScript: The Good Parts', 'Douglas Crockford', 'O\'Reilly Media', 2008, 3),
('978-0-13-235088-4', 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 4),
('978-1-491-95038-8', 'PHP & MySQL: Novice to Ninja', 'Tom Butler, Kevin Yank', 'SitePoint', 2017, 6),
('978-0-201-63361-0', 'Design Patterns', 'Erich Gamma, et al.', 'Addison-Wesley', 1994, 2);

-- Dữ liệu mẫu borrow_transactions
INSERT INTO borrow_transactions (book_id, borrower_name, borrower_email, borrow_date, due_date, return_date, status, notes) VALUES
-- Sách đang được mượn (borrowed)
(1, 'Nguyen Van A', 'nguyenvana@email.com', '2026-03-15', '2026-04-15', NULL, 'borrowed', 'Mượn để nghiên cứu'),
(1, 'Tran Thi B', 'tranthib@email.com', '2026-03-20', '2026-04-20', NULL, 'borrowed', NULL),
(2, 'Le Van C', 'levanc@email.com', '2026-03-10', '2026-04-10', NULL, 'borrowed', 'Sinh viên năm 3'),
(3, 'Pham Thi D', 'phamthid@email.com', '2026-03-25', '2026-04-25', NULL, 'borrowed', NULL),
(5, 'Hoang Van E', 'hoangvane@email.com', '2026-03-18', '2026-04-18', NULL, 'borrowed', 'Giảng viên'),

-- Sách đã trả (returned)
(1, 'Vo Thi F', 'vothif@email.com', '2026-02-01', '2026-03-01', '2026-02-25', 'returned', 'Trả sớm'),
(2, 'Dang Van G', 'dangvang@email.com', '2026-01-15', '2026-02-15', '2026-02-14', 'returned', NULL),
(3, 'Bui Thi H', 'buithih@email.com', '2026-02-10', '2026-03-10', '2026-03-10', 'returned', 'Trả đúng hạn'),
(4, 'Ngo Van I', 'ngovani@email.com', '2026-01-20', '2026-02-20', '2026-02-18', 'returned', NULL),

-- Sách quá hạn (overdue)
(2, 'Truong Thi K', 'truongthik@email.com', '2026-02-01', '2026-03-01', NULL, 'overdue', 'Đã nhắc nhở 2 lần'),
(4, 'Ly Van L', 'lyvanl@email.com', '2026-02-15', '2026-03-15', NULL, 'overdue', 'Liên hệ không được'),
(5, 'Mai Thi M', 'maithim@email.com', '2026-01-10', '2026-02-10', NULL, 'overdue', 'Quá hạn lâu');

