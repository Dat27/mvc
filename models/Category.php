<?php
/**
 * models/Category.php
 * Mọi truy vấn liên quan đến Category đều viết tại model này
 */
require_once 'models/Model.php';
class Category extends Model {
    public $id;
    public $name;
//    public
    public $avatar;
    public $created_at;


    public function insert(){
        // +Viết truy vấn: id, name, avatar, created_at
        // + Do name và avatar có kiểu dữ liệu string nên cần truyền giá trị dạng tham số để tránh lỗi
        // bảo mật SQL Injection
        $sql_insert = "INSERT INTO categories (name, avatar) VALUES (:name, :avatar)";
        // + Cbi obj truy vấn
        $obj_insert = $this->connection->prepare($sql_insert);
        // + Tạo mảng truyền giá trị thật cho tham số trong câu truy vấn
        $inserts = [
            ':name' => $this->name,
            ':avatar' => $this->avatar
        ];
        // + Thực thi obj truy vấn
        $is_insert = $obj_insert->execute($inserts);
        return $is_insert;
    }
    public function getAll(){
        // + Viết truy vấn
        $sql_select_all = "SELECT * FROM categories ORDER BY created_at DESC";
        // + Chuẩn bị obj truy vấn
        $obj_select_all = $this->connection->prepare($sql_select_all);
        // + Bỏ qua bước truyền giá trị
        // + Thực thi truy vấn theo cơ chế PDO
        $obj_select_all->execute();
        // + Trả về mảng kết hợp
        $categories = $obj_select_all ->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }
    public function delete($id){
        // Chúc năng xóa bản ghi theo $id
        $sql_delete = "DELETE FROM categories where id = $id";
        $obj_delete = $this->connection->prepare($sql_delete);
        $is_delete = $obj_delete->execute();
//        var_dump($is_delete);
        if ($is_delete){
            $_SESSION['success'] = "Deleted";
            header("Location: index.php?controller=category&action=index");
            exit();
        }
    }
    public function getCategoryById($id){
        $sql_select_one = "SELECT * FROM categories where id = $id";
        // Không cần tạo tham số vì chắc chắn $id là số
        // + Chuẩn bị đối tượng truy vấn
        $obj_select_one = $this->connection->prepare($sql_select_one);
        // + Bỏ qua bước tạo mảng
        // Thực thi
        $obj_select_one->execute();
        // Lấy mảng kết hợp một chiều
        $category  = $obj_select_one->fetch(PDO::FETCH_ASSOC);
        return $category;
    }

    public function update($id){
        // Viết truy vấn
        $sql_update = "UPDATE categories set name = :name, avatar = :avatar where id = $id";
        // cbi obj truy vấn
        $obj_update = $this->connection->prepare($sql_update);
        // Tạo mảng gán giá trị
        $updates = [
            ':name'=>$this->name,
            ':avatar'=>$this->avatar
        ];
        $is_update = $obj_update->execute($updates);
        return $is_update;
    }
}