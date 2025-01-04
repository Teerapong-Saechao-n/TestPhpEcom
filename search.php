<?php
include('db.php');
if (isset($_GET['q'])) {
    $query = $_GET['q']; // คำค้นหาจากผู้ใช้

    // ใช้คำค้นหาเพื่อดึงข้อมูลสินค้าที่ตรงกับคำค้นหาจากฐานข้อมูล
    $sql = "SELECT * FROM products WHERE name LIKE '%$query%' LIMIT 5";  // จำกัดการแสดงผล 5 รายการ
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="search-item">';
            echo '<img src="images/' . $row["image"] . '" alt="' . $row["name"] . '" style="width: 50px; height: 50px;">';
            echo '<a href="product-detail.php?id=' . $row["id"] . '">' . $row["name"] . '</a>';
            echo '</div>';
        }
    } else {
        echo '<p>ไม่พบสินค้าที่ค้นหา</p>';
    }
}
$conn->close();
?>
