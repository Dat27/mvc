<?php
require_once 'controllers/Controller.php';
require_once 'models/Category.php';
// controller/CategoryController.php
//index.php?controller=category&action=create
class CategoryController extends Controller {
    // Chức năng thêm mới danh mục

    public function create(){
        // Xử lý submit form phía trên html
        // - Debug
//        echo "<pre>";
//        print_r($_POST);
//        print_r($_FILES);
//        echo "</pre>";
        // - Xử lý submit form
        if (isset($_POST['submit'])){
            //Tạo biến trung gian
            $name = $_POST['name'];
            $avatar_arr = $_FILES['avatar'];
            // Validate form: Tên không được để trống, file upload phải là ảnh, file upload dung lượng nhỏ hơn 2MB
            if (empty($name)){
                $this->error = 'Tên không được để trống';
            }
            // Nếu có tải file lên thì mới có thể xử lý
            elseif ($avatar_arr['error'] == 0){
                // + File upload phải là ảnh
                // Lấy đuôi file
                $extension = pathinfo($avatar_arr['name'], PATHINFO_EXTENSION);
//                var_dump($extension);
                //Chuyển đuôi file về chữ thường

                $extension = strtolower($extension);

                // Tạo mảng chứa các đuôi file ảnh hợp lệ
                $allowed = ['png','jpg','jpeg','gif'];
                if (!in_array($extension,$allowed)){
                    $this->error = 'File upload phải là ảnh';
                }
                //- File upload phải <= 2MB
                $filesize_b = $avatar_arr['size'];
                $filesize_mb = $filesize_b/1024/1024;
                $filesize_mb = round($filesize_mb, 2);
                if ($filesize_mb>2){
                    $this->error = 'File upload phải < 2MB';
                }
            }

            // Xử lý logic bài toán chỉ khi không có lỗi xảy ra
            if (empty($this->error)){
                // Lưu tên file nếu có
                $avatar = '';
                // Xử lý upload file nếu có
                if ($avatar_arr['error'] == 0){
                    // Tạo đường dẫn thư mục để chứa file upload
                    $dir_upload = 'assets/uploads';
                    //Kiểm tra nếu chưa tồn tại thư mục thì mới tạo
                    if (!file_exists($dir_upload)){
                        mkdir($dir_upload);
                    }
                    // Tạo file mang tính duy nhất
                    $avatar = time(). "-" . $avatar_arr['name'];
                    // Upload file chuyển file từ đường dẫn tạm -> đường dẫn đích
                    move_uploaded_file($avatar_arr['tmp_name'], "$dir_upload/$avatar");
                }

                // + Gọi Model để nhờ Model lưu thông tin vào CSDL
                $category_model = new Category();
                // Gán các giá trị từ form cho các thuộc tính của model
                $category_model->name = $name;
                $category_model->avatar = $avatar;
                $is_insert = $category_model->insert();
//                var_dump($is_insert);
                // Chuyển hướng: Nếu insert thành công
                if ($is_insert){
                    $_SESSION['success'] = 'Thêm danh mục thành công';
                    header('Location:index.php?controller=category&action=index');
                    exit();
                } else {
                    $this->error = 'Thêm thất bại';
                }
            }
        }


//        echo 'Phương thức create';
        //Gọi view để hiển thị giao diện form thêm mới cho user
        // Thường là công việc đầu tiên khi code chức năng.
        // - View cần theo cơ chế layout động: chỉ phần nội dung động theo từng trang sẽ thay đổi,
        //còn header, footer,.. chung cho tất cả các trang

        // - Set các giá trị tương ứng cho các nội dung động để hiển thị ra layout
        // Tiêu đề trang
        $this->page_title = 'Trang thêm mới danh mục';
        // View của trang
        $this->content = $this->render('views/categories/create.php');
//        var_dump($this->content);

        // Xây dụng layout động trước
        require_once 'views/layouts/main.php';
    }

    public function index(){
//        echo "Đây là danh mục sản phẩm";

        // Gọi model truy vấn tất cả bản in danh mục đang có
        $category_model = new Category();
        $categories = $category_model->getAll();
//        echo "<pre>";
//        print_r($categories);
//        echo "</pre>";

        // Gọi layout để hiển thị nội dung view
        // Truyền mảng các biến ra view với cú pháp
        // Key là tiên biến mà view sử dụng
        //value tương ứng là giá trị của key đó
        $this->content = $this->render('views/categories/index.php',[
            'categories'=>$categories
        ]);
        require_once 'views/layouts/main.php';
    }

    public function delete(){
        // Xóa thì không nhất thiết cần view, bỏ qua bước gọi layout
        // Validate id
        if (!isset($_GET['id'])|| !is_numeric($_GET['id'])){
            $_SESSION['error'] = 'id không hợp lệ';
            header("Location: index.php?controller=category&action=index");
            exit();
        }
        $id = $_GET['id'];
        // Gọi Model để xóa
        $category_model = new Category();
        $is_delete = $category_model->delete($id);
//        var_dump($is_delete);
        // Chuyển hướng dựa vào biến $is_delete

    }
    public function update(){
//        index.php?controller=category&action=update&id=6
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])){
            $_SESSION['error'] = 'id was uncorrect';
            header("Location: index.php?controller=category");
            exit();
        }
        $id = $_GET['id'];
        // Lấy bản ghi tương ứng theo id truyền ra view
        $category_model = new Category();
        $category = $category_model->getCategoryById($id);




        // Xử lý submit form ở vị trí phía trên view
        ////         - Debug
        echo "<pre>";
        print_r($_POST);
        print_r($_FILES);
        echo "</pre>";
        // Nếu submit form
        if (isset($_POST['submit'])){
            //Tạo biến trung gian
            $name = $_POST['name'];
            $avatar = $_FILES['avatar'];
            if (empty($this->error)){
                // Xử lý upload file với chức năng update sẽ khác so với update
                $avatar = $category['avatar'];
                //xử lý logic upload file nếu có như thêm mới
                // Xóa file cũ đi tránh rác hệ thống
                //unlink($path_file);
                // Update
                // Gán các giá trị từ form cho obj category
                $category_model->name = $name;
                $category_model->avatar = $avatar;
                $is_update = $category_model->update($id);
                var_dump($is_update);
            }

        }



        // - Gọi layout để hiển thị view update
        $this->page_title = "Trang chỉnh sửa sản phẩm";
        $this ->content = $this->render('views/categories/update.php',[
            'category'=> $category
        ]);
        require_once "views/layouts/main.php";

    }
}