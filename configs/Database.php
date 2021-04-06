<?php
/**
 * configs/Database.php
 * class chứa các hằng số kết nối CDSL
 * MVC ưu tiên sử dụng PDO để kết nối
 */
class Database{
    const DB_DSN = 'mysql:host=localhost;dbname=php1020e_mvc;charset=utf8';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = '';
}