<?php
// ตรวจสอบว่าเข้าสู่ระบบแล้วหรือยัง
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php'); // ถ้าเข้าสู่ระบบแล้วให้ไปที่หน้าหลัก
    exit();
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php');

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    // ตรวจสอบว่ากรอกข้อมูลครบหรือไม่
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        $error = "กรุณากรอกข้อมูลให้ครบ";
    } elseif ($password !== $confirm_password) {
        $error = "รหัสผ่านไม่ตรงกัน";
    } else {
        // ตรวจสอบว่า username หรือ email มีในระบบหรือยัง
        $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error = "ชื่อผู้ใช้หรืออีเมลนี้ถูกใช้ไปแล้ว";
        } else {
            // แทรกข้อมูลผู้ใช้ใหม่
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
            $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
            if ($conn->query($sql) === TRUE) {
                header('Location: login.php'); // ไปที่หน้า login หลังสมัครสมาชิกเสร็จ
                exit();
            } else {
                $error = "เกิดข้อผิดพลาดในการสมัครสมาชิก";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - เว็บซื้อขาย</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('header.php'); ?>
    <section class="register-container">
        <h2>สมัครสมาชิก</h2>

        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">อีเมล:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">ยืนยันรหัสผ่าน:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">สมัครสมาชิก</button>
        </form>
        <p>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
    </section>
    <?php include('footer.php'); ?>
</body>

</html>