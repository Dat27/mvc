<?php
session_start();
//Khởi tạo session.



// - Set múi giờ cho hệ thống
//echo time();
// cần set lại múi giờ Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');
//echo date('d-m-Y H:i:s');


// - Phân tích url để lấy các giá trị của controller và action

// Demo với url thêm mới danh mục
// index.php?controller=category&action=create
//   Lấy giá trị của controller:, nếu không truyền tham số controller lên url-> set controller mặc định
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';//category
// + Lấy giá trị của action tương tự như controller
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

//var_dump($controller);
//var_dump($action);
// Chuyển đổi biến controller thành tên file controller tương ứng để chuẩn bị cho việc nhúng file controller
// category -> CategoryController
$controller = ucfirst($controller);// Category
$controller.="Controller";// CategoryController
//var_dump($controller);

// Tạo 1 biến chứa đường dẫn tới file controller sẽ nhúng
$path_controller = "controllers/$controller.php";
//var_dump($path_controller);
// Kiểm tra nếu đường dẫn tới controller không tồn tại sẽ thông báo not found -> 404
if (!file_exists($path_controller)){
    die("Trang không tồn tại");
};

//- Nhúng file controller để sử dụng được class bên trong file này
require_once "$path_controller";

// - Khởi tạo đối tượng từ class controller
$obj = new $controller();

// - Dùng đối tượng vừa khởi tạo gọi/ truy cập phương thức của class đó
if (!method_exists($obj, $action)){
    die("Không tồn tại phương thức $action của controller $controller");
}

$obj->$action();