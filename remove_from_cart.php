<?php
session_start();

// ตรวจสอบว่าได้รับ `id` ของสินค้าที่ต้องการลบหรือไม่
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // ลบสินค้าจากตะกร้า
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }

    // รีเซ็ตค่า index ของ array
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// ไปที่หน้า cart หลังจากลบสินค้า
header("Location: cart.php");
exit;
?>
