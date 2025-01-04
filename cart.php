<?php
include('db.php');
session_start();  // เริ่มต้น session

// คำนวณยอดรวม
$total = 0;

if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    // คำนวณยอดรวมสินค้า
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
} else {
    $total = 0;
}

// ตรวจสอบการคลิกปุ่ม "ลบสินค้า"
if (isset($_POST['remove_item'])) {
    $product_id_to_remove = $_POST['product_id'];
    // ลบสินค้าที่เลือกจากตะกร้า
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id_to_remove) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    // รีเซ็ตระเบียบของตะกร้าใหม่
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('header.php'); ?>

    <section class="cart">
        <h2>ตะกร้าสินค้าของคุณ</h2>

        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
        <!-- สร้างตารางแสดงรายการสินค้าในตะกร้า -->
        <table class="cart-table">
            <thead>
                <tr>
                    <th>รูปภาพ</th>
                    <th>ชื่อสินค้า</th>
                    <th>ราคา</th>
                    <th>จำนวน</th>
                    <th>ราคารวม</th>
                    <th>ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                <tr>
                    <td><img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" width="100"></td>
                    <td><?php echo $item['name']; ?></td>
                    <td>฿<?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>฿<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <!-- ปุ่มลบสินค้าออกจากตะกร้า -->
                        <form method="POST" action="cart.php" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="remove_item" class="remove-item-btn">ลบ</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <p>ยอดรวม: ฿<?php echo number_format($total, 2); ?></p>
            <br>
            <a href="checkout.php" class="checkout-btn">ไปที่การชำระเงิน</a>
        </div>

        <?php else: ?>
        <p>ตะกร้าของคุณว่างเปล่า!</p>
        <?php endif; ?>
    </section>

    <?php include('footer.php'); ?>
</body>

</html>

<?php
$conn->close();
?>
