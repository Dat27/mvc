<?php
/**
 * views/categories/update.php
 */
?>
<h1>Here is form update product</h1>
    <form action="" method = "POST" enctype = "multipart/form-data">
        Tên sản phẩm:
        <input type="text" name = "name" value ="<?php echo $category['name']?>"> <br>
        Ảnh đại diện:
        <input type="file" name = "avatar"> <br>
        <img src="assets/uploads/<?php echo $category['avatar']?>" style="height:85px">
        <input type="submit" name = "submit" value = "Lưu">

    </form>
