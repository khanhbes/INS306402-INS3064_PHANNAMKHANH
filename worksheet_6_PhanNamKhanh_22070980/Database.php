<?php
class Database {
    private static $instance = null;
    private $connection;

    // Hàm construct bị ẩn (private) để không ai có thể dùng từ khóa "new Database()" bên ngoài
    private function __construct() {
        $dsn = "mysql:host=localhost;dbname=ecommerce_db;charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Tự động báo lỗi nếu SQL sai
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Lấy dữ liệu dưới dạng mảng (array) dễ đọc
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            // "root" là tài khoản mặc định của XAMPP, mật khẩu để trống ""
            $this->connection = new PDO($dsn, 'root', '', $options);
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
?>