<?php
// เริ่ม session ถ้ายังไม่ได้เริ่ม
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
    <!-- ชั้นที่ 1 -->
    <div class="header-top">
        <div class="logo">
            <a href="index.php">
                <h1>Logo Shop</h1>
            </a>
        </div>
        <div class="login">
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<span class="welcome-msg">ยินดีต้อนรับ, ' . $_SESSION['username'] . '</span>';
                echo '<a href="logout.php" class="logout-btn">ออกจากระบบ</a>';
            } else {
                echo '<a href="login.php" class="login-btn">Login</a>';
            }
            ?>
        </div>
    </div>

    <!-- ชั้นที่ 2 -->
    <div class="header-bottom">
        <div class="logo-center">
            <a href="index.php">
                <h1>Logo Shop</h1>
            </a>
        </div>
        <div class="menu">
            <a href="#">ใหม่</a>
            <a href="#">ลดราคา</a>
            <a href="#">สินค้า</a>
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="ค้นหาสินค้า..." onkeyup="searchProduct()" />
                <div id="search-result"></div> <!-- แสดงผลลัพธ์การค้นหา -->
            </div>
            <a href="#">รายการโปรด</a>
            <a href="cart.php">ตะกร้า
                <?php
                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                    $cart_count = 0;
                    foreach ($_SESSION['cart'] as $item) {
                        $cart_count += $item['quantity'];
                    }
                    echo " ($cart_count)";
                }
                ?>
            </a>
        </div>
    </div>
</header>

<!-- เพิ่มโค้ด JavaScript สำหรับ AJAX -->
<script>
function searchProduct() {
    var query = document.getElementById('search-input').value;

    if (query.length >= 3) {  // เริ่มค้นหาหลังจากพิมพ์ 3 ตัวอักษร
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "search.php?q=" + query, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById('search-result').style.display = 'block';
                document.getElementById('search-result').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    } else {
        document.getElementById('search-result').style.display = 'none';
    }
}
</script>
