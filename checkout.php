<?php
include('db.php');
session_start();  // เริ่มต้น session

// คำนวณราคารวม
$total_price = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}

// ตรวจสอบการกรอกข้อมูลและยืนยันการสั่งซื้อ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $payment_method = $_POST['payment_method'];

    // บันทึกคำสั่งซื้อในฐานข้อมูล (สามารถปรับตามฐานข้อมูลของคุณได้)
    $sql = "INSERT INTO orders (user_id, name, address, phone, total_price, payment_method) 
            VALUES ('" . $_SESSION['user_id'] . "', '$name', '$address', '$phone', '$total_price', '$payment_method')";
    $conn->query($sql);

    // ล้างตะกร้าสินค้า
    unset($_SESSION['cart']);

    echo "<script>alert('การสั่งซื้อของคุณเสร็จสมบูรณ์!'); window.location='index.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('header.php'); ?>

    <section class="checkout">
        <h2>กรอกข้อมูลการชำระเงิน</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="order-summary">
                <h3>รายการสินค้าที่คุณสั่งซื้อ</h3>
                <table class="order-summary-table">
                    <thead>
                        <tr>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>จำนวน</th>
                            <th>รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <tr>
                                <td><?php echo $item['name']; ?></td>
                                <td>฿<?php echo $item['price']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>฿<?php echo $item['price'] * $item['quantity']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p><strong>ราคารวม: ฿<?php echo $total_price; ?></strong></p>
            </div>

            <form method="POST" class="checkout-form">
                <div class="form-group">
                    <label for="name">ชื่อ-นามสกุล</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="address">ที่อยู่จัดส่ง</label>
                    <textarea name="address" id="address" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="phone">เบอร์โทรศัพท์</label>
                    <input type="text" name="phone" id="phone" required>
                </div>
                <div class="form-group">
                    <label for="payment_method">วิธีการชำระเงิน</label>
                    <select name="payment_method" id="payment_method" required>
                        <option value="cash_on_delivery">เก็บเงินปลายทาง</option>
                        <option value="bank_transfer">โอนผ่านธนาคาร</option>
                    </select>
                </div>
                <button type="submit" class="submit-btn">ยืนยันการสั่งซื้อ</button>
            </form>
        <?php else: ?>
            <p>ตะกร้าของคุณว่างเปล่า โปรดเพิ่มสินค้าก่อนทำการชำระเงิน</p>
        <?php endif; ?>
    </section>

    <?php include('footer.php'); ?>
</body>

</html>