<?php
require_once 'configs/Database.php';
// models/Model.php
// Model cha, chứa thuộc tính kết nối dùng chung cho các model con
class Model{
    //Thuộc tính kết nối
    public $connection;

    // Phương thức khởi tạo, chạy đầu tiên mỗi khi có 1 đối tượng sinh ra từ class

    public function __construct(){
        // Khởi tạo đối tượng kết nối cho thuộc tish connection
        $this->connection = $this->getConnection();
    }

    // kết nối csdl theo PDO
    public function getConnection(){
        // Với PDO cần viết trong try catch để PHP bắt được các ngoại lệ/lỗi kết nối
        try{
            $connection = new PDO(Database::DB_DSN,Database::DB_USERNAME, Database::DB_PASSWORD);

            return $connection;

        } catch (PDOException $e){
            die("Lỗi kết nối: " . $e->getMessage());
        }
    }
}