<?php
/** view/layouts/main.php
 *file layout chính của ứng dụng
 * Các nội dung động sẽ lấy từ controller để hiển thị , nội dung động là nội dung sẽ thay đổi theo từng trang
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $this->page_title; ?></title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div class="header">
            Đây là header
        </div>

        <div class="main-content">
            <h3 style ="color: red">
                <?php echo $this->error;
                if (isset($_SESSION['success'])){
                    echo "<h3 style='color: #63ffac'>{$_SESSION['success']}</h3>";
                    unset($_SESSION['success']);
                }
                if (isset($_SESSION['error'])){
                    echo "<h3 style='color: #ff0000'>{$_SESSION['error']}</h3>";
                    unset($_SESSION['error']);
                }
                ?>
            </h3>

            <?php
            // Hiển thị theo cơ chế nội dung động
            echo $this->content;
            ?>
        </div>

        <div class="footer">
            Đây là footer
        </div>

        <script src="assets/js/script.js"></script>
    </body>
</html>

