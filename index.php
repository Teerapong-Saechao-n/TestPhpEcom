<?php
include('db.php');
session_start();  // เริ่มต้น session

// ตรวจสอบว่ามีการคลิกปุ่ม "เพิ่มไปยังตะกร้า" หรือไม่
if (isset($_POST['add_to_cart'])) {
    // ตรวจสอบว่า user ได้ login หรือยัง
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('กรุณาล็อกอินก่อนที่จะเพิ่มสินค้าในตะกร้า');</script>";
    } else {
        $product_id = $_POST['product_id'];  // รหัสสินค้าที่ผู้ใช้เลือก
        $sql = "SELECT * FROM products WHERE id = '$product_id'";  // ดึงข้อมูลสินค้าจากฐานข้อมูล
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // เพิ่มสินค้าลงในตะกร้า (หากยังไม่มี)
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }
            // เช็คว่ามีสินค้านี้ในตะกร้าหรือยัง
            $found = false;
            foreach ($_SESSION['cart'] as $item) {
                if ($item['id'] == $product_id) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['cart'][] = array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'image' => $row['image'],
                    'quantity' => 1  // เริ่มต้นที่ 1 ชิ้น
                );
            }
        }
    }
}

// ป้องกันการรีเซ็ตตะกร้าเมื่อไม่ได้ล็อกอิน
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();  // หากยังไม่มีตะกร้าใน session ก็ให้สร้างขึ้นใหม่
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้า Home - เว็บซื้อขาย</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('header.php'); ?>

    <section class="products">
        <h2>สินค้ายอดนิยม</h2>
        <div class="product-list">
            <?php
            $sql = "SELECT * FROM products ORDER BY RAND() LIMIT 3";  // เลือกสินค้า 3 ตัวแรกแบบสุ่ม
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="product-item">';
                    echo '<img src="images/' . $row["image"] . '" alt="' . $row["name"] . '">';
                    echo '<h3>' . $row["name"] . '</h3>';
                    echo '<p>' . $row["description"] . '</p>';
                    echo '<p class="price">ราคา: ฿' . $row["price"] . '</p>';
                    echo '<form method="POST" action="index.php">';  // ฟอร์มสำหรับส่งข้อมูลสินค้าไปตะกร้า
                    echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
                    echo '<button type="submit" name="add_to_cart">เพิ่มไปยังตะกร้า</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "<p>ไม่มีสินค้าที่จะแสดง</p>";
            }
            ?>
        </div>
    </section>

    <?php include('footer.php'); ?>

</body>

</html>

<?php
$conn->close();
?>
