<?php

    session_start(); // Nếu session đã bắt đầu, chỉ cần gọi session_start()

// Kết nối cơ sở dữ liệu
$host = 'test213.mysql.database.azure.com';
$username = 'fx';
$password = 'Lanphamj21@@';
$db_name = 'fx';

$conn = new mysqli($host, $username, $password, $db_name);
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

if ($result->num_rows == 1) {
    // Đăng nhập thành công
    $row = $result->fetch_assoc();
    $_SESSION['User_name'] = $User;
    $_SESSION['id'] = 1;  // Lưu ID người dùng vào session
    

    header('Location: index.php');  // Chuyển hướng đến trang chủ
    exit;
} else {
    // Đăng nhập thất bại
    $_SESSION['error_message'] = "Tên đăng nhập hoặc mật khẩu không đúng.";
    header('Location: login.html');  // Chuyển hướng về trang login
    exit;
}

// Đóng kết nối
$conn->close();
?>
