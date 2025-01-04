<?php
// ตรวจสอบการเข้าสู่ระบบ
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

    // ตรวจสอบว่ากรอกข้อมูลครบหรือไม่
    if (empty($username) || empty($password)) {
        $error = "กรุณากรอกข้อมูลให้ครบ";
    } else {
        // ตรวจสอบชื่อผู้ใช้และรหัสผ่านจากฐานข้อมูล
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // ตรวจสอบรหัสผ่าน
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username']; // เก็บชื่อผู้ใช้ใน session
                header('Location: index.php'); // ไปที่หน้าหลักหลังจากเข้าสู่ระบบ
                exit();
            } else {
                $error = "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            $error = "ชื่อผู้ใช้ไม่ถูกต้อง";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - เว็บซื้อขาย</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('header.php'); ?>
    <section class="login-container">
        <h2>เข้าสู่ระบบ</h2>

        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
        <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
        <p>มีบัญชีอยู่แล้ว <a href="index.php">คลิก!!!</a></p>
    </section>
    <?php include('footer.php'); ?>
</body>

</html>