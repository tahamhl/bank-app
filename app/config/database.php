<?php
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct() {
        // Eğer .env dosyası varsa değerleri oradan al
        if (file_exists(dirname(__FILE__) . '/../../.env')) {
            $env = parse_ini_file(dirname(__FILE__) . '/../../.env');
            $this->host = $env['DB_HOST'] ?? 'localhost';
            $this->db_name = $env['DB_NAME'] ?? '';
            $this->username = $env['DB_USER'] ?? '';
            $this->password = $env['DB_PASS'] ?? '';
        } else {
            // Varsayılan değerler (geliştirme ortamı için)
            $this->host = 'localhost';
            $this->db_name = 'bankapp';
            $this->username = 'root';
            $this->password = '';
        }
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Veritabanı bağlantı hatası: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?> 