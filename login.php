<?php
session_start();

$host = 'test213.mysql.database.azure.com';
$username = 'fx';
$password = 'Lanphamj21@@';
$db_name = 'fx';

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($host, $username, $password, $db_name);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối tới cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Lấy dữ liệu người dùng nhập vào từ form
$User = $_POST["User"];
$Password = $_POST["Password"];

// Lọc dữ liệu để tránh SQL Injection
$Input_user = mysqli_real_escape_string($conn, $User);
$Input_pass = mysqli_real_escape_string($conn, $Password);

// Sử dụng Prepared Statements để tránh SQL Injection
$sql = $conn->prepare("SELECT * FROM `account` WHERE User = ? AND Password = ?");
$sql->bind_param("ss", $Input_user, $Input_pass);
$sql->execute();
$result = $sql->get_result();

// Kiểm tra số lượng kết quả trả về
if ($result->num_rows == 1) {
    // Nếu đăng nhập thành công, lưu thông tin người dùng vào session
    $_SESSION['User_name'] = $User;
    $_SESSION['id'] = 1;
    header('Location: index.php');  // Chuyển hướng đến trang chủ
    exit;
} else {
    // Nếu đăng nhập thất bại, thông báo lỗi và quay lại trang login
    $_SESSION['error_message'] = "Tên đăng nhập hoặc mật khẩu không đúng.";
    header('Location: login.html');  // Chuyển hướng về trang đăng nhập
    exit;
}

// Đóng kết nối
$conn->close();
?>
