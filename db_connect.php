<?php
// Thông tin kết nối Database
$servername = "localhost";
$username = "root";
$password = ""; // Mặc định WAMP là rỗng
$dbname = "quanlysinhvien";

try {
    // Tạo kết nối PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    
    // Thiết lập chế độ lỗi để dễ debug
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Nếu chạy trực tiếp file này thì báo thành công (để test)
    // Sau này khi include vào file khác thì dòng này sẽ không hiện
    if (basename($_SERVER['PHP_SELF']) == 'db_connect.php') {
        echo "Kết nối CSDL thành công!";
    }
} catch(PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
}
?>
