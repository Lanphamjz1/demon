<?php 
session_start(); // Bắt đầu session

// Kiểm tra xem session có hợp lệ không
if (!isset($_SESSION['User_name']) || !isset($_SESSION['id'])) {
    header("Location: login.html"); // Chuyển hướng về trang đăng nhập nếu session không hợp lệ
    exit;
}

$id = $_SESSION['id'];  // Lấy id từ session (giá trị cố định)

echo "Chào mừng, " . $_SESSION['User_name'] . "!<br>";
echo "ID người dùng là: " . $id . "<br>";

// Kết nối cơ sở dữ liệu
$host = 'test213.mysql.database.azure.com';
$username = 'fx';
$password = 'Lanphamj21@@';
$db_name = 'fx';

$conn = new mysqli($host, $username, $password, $db_name);
if ($conn->connect_error) {
    die("Kết nối tới cơ sở dữ liệu thất bại: " . $conn->connect_error);
}

// Lấy thông tin sinh viên
$stmt = $conn->prepare("SELECT Name, Department, Specialized, Class FROM student WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

// Lấy bảng điểm từ bảng 2022-2023_2
$stmt_scores = $conn->prepare("SELECT * FROM `2022-2023_2` WHERE Student_id = ?");
$stmt_scores->bind_param("s", $id);
$stmt_scores->execute();
$scores_result = $stmt_scores->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">
    <title>Trang chủ</title>
</head>
<body>
    <div class="wraper">
        <div class="header">
            <div class="header__title">
                <div class="header__title__description" style="color: #fff;">
                    TRƯỜNG ĐẠI HỌC HÀNG HẢI VIỆT NAM
                </div>
                <div class="header__title__user">
                    <h3>Sinh Viên: <?php echo $student['Name']; ?></h3>
                </div>
            </div>
            <div class="header__system">
                <a href="index.php" class="header__system__link">Trang chủ</a>
                <a href="logout.php" class="header__system__link">Đăng xuất</a>
            </div>
        </div>

        <div class="container">
            <div class="container__information">
                <div class="container__information--col">
                    <div class="container__information--row"> 
                        <div class="container__information--title">Mã Sinh Viên:</div>
                        <div class="container__information--id"><?php echo $id; ?></div>
                    </div>
                    <div class="container__information--row"> 
                        <div class="container__information--title">Khoa: </div>
                        <div class="container__information--department"><?php echo $student['Department']; ?></div>
                    </div>
                    <div class="container__information--row"> 
                        <div class="container__information--title">Ngành:</div>
                        <div class="container__information--specialized"><?php echo $student['Specialized']; ?></div>
                    </div>
                </div>
                <div class="container__information--col">
                    <div class="container__information--row"> 
                        <div class="container__information--title">Lớp: </div>
                        <div class="container__information--class"><?php echo $student['Class']; ?></div>
                    </div>
                </div>
            </div>

            <div class="container__scores">
                <h3>BẢNG ĐIỂM CHI TIẾT</h3>
                <table width="100%" align="center" border="1" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th>Mã Môn Học</th>
                            <th>Số Tín Chỉ</th>
                            <th>Điểm Giữa Kỳ</th>
                            <th>Điểm Cuối Kỳ</th>
                            <th>Tổng Điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while ($score = $scores_result->fetch_assoc()) {
                                $total_score = ($score['Midterm_exam'] + $score['Final_exam']) / 2;
                                echo "<tr>
                                        <td>{$score['Subject_id']}</td>
                                        <td>{$score['Subject_credits']}</td>
                                        <td>{$score['Midterm_exam']}</td>
                                        <td>{$score['Final_exam']}</td>
                                        <td>$total_score</td>
                                      </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="footer">
            <div class="footer__description">
                Đường dây nóng: 02435528978
            </div>
        </div>
    </div>
</body>
</html>
