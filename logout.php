<?php
session_start();
session_unset(); // ลบข้อมูลทั้งหมดใน session
session_destroy(); // ทำลาย session
header('Location: index.php'); // หลังจากออกจากระบบแล้วให้กลับไปที่หน้าแรก
exit();
?>
