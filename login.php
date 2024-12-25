<?php
session_start();

// Kiểm tra nếu có dữ liệu từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $User = $_POST['User'];
    $Password = $_POST['Password'];

    // Kiểm tra nếu dữ liệu không rỗng
    if (!empty($User) && !empty($Password)) {
        // Kết nối tới cơ sở dữ liệu
        $host = 'test213.mysql.database.azure.com';
        $username = 'fx';
        $password = 'Lanphamj21@@';
        $db_name = 'fx';

        $conn = new mysqli($host, $username, $password, $db_name);

        // Kiểm tra kết nối
        if ($conn->connect_error) {
            die("Kết nối tới cơ sở dữ liệu thất bại: " . $conn->connect_error);
        }

        // Lọc dữ liệu đầu vào để tránh SQL injection
        $Input_user = mysqli_real_escape_string($conn, $User);
        $Input_pass = mysqli_real_escape_string($conn, $Password);

        // Sử dụng Prepared Statements để tránh SQL injection
        $sql = $conn->prepare("SELECT * FROM `account` WHERE User = ? AND Password = ?");
        $sql->bind_param("ss", $Input_user, $Input_pass);
        $sql->execute();
        $result = $sql->get_result();

        // Kiểm tra nếu có tài khoản khớp
        if ($result->num_rows == 1) {
            // Đăng nhập thành công, lưu thông tin vào session
            $row = $result->fetch_assoc();
            $_SESSION['User_name'] = $User;
            $_SESSION['id'] = $row['id'];
            header('Location: index.php'); // Chuyển hướng đến trang index.php
            exit;
        } else {
            // Nếu đăng nhập thất bại
            $_SESSION['error_message'] = "Tên đăng nhập hoặc mật khẩu không đúng.";
            header('Location: login.html'); // Quay lại trang login
            exit;
        }

        // Đóng kết nối
        $conn->close();
    } else {
        // Nếu dữ liệu trống
        $_SESSION['error_message'] = "Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.";
        header('Location: login.html');
        exit;
    }
}
?>
