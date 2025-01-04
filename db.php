<?php
$servername = "localhost";
$username = "root";  // ชื่อผู้ใช้ของฐานข้อมูล
$password = "";      // รหัสผ่านของฐานข้อมูล
$dbname = "shop";    // ชื่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

