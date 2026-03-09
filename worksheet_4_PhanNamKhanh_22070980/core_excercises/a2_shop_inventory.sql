-- Tạo bảng products (Sản phẩm) cho Cửa hàng
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    sku VARCHAR(50) UNIQUE NOT NULL,                -- SKU định danh sản phẩm phải là duy nhất
    price DECIMAL(10, 2) CHECK (price > 0),         -- Giá tiền phải lớn hơn 0
    stock_quantity INT DEFAULT 0,                   -- Số lượng tồn kho mặc định là 0
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);