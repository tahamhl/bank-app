<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kullanıcı kaydı
    public function register() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET first_name = :first_name, 
                    last_name = :last_name, 
                    email = :email, 
                    password = :password, 
                    created_at = :created_at, 
                    updated_at = :updated_at";

        $stmt = $this->conn->prepare($query);

        // Verileri temizleme
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        // Güvenli parola şifreleme
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        // Parametreleri bağlama
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':updated_at', $this->updated_at);

        // Sorguyu çalıştırma
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // E-posta ile kullanıcı giriş kontrolü
    public function login() {
        $query = "SELECT id, first_name, last_name, email, password 
                FROM " . $this->table_name . " 
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];

            // Parola kontrolü
            if(password_verify($this->password, $row['password'])) {
                return true;
            }
        }

        return false;
    }

    // ID ile kullanıcı bilgilerini getirme
    public function getUserById($id) {
        $query = "SELECT id, first_name, last_name, email, created_at
                FROM " . $this->table_name . " 
                WHERE id = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->created_at = $row['created_at'];
            return true;
        }

        return false;
    }

    // Kullanıcı bilgilerini güncelleme
    public function updateProfile() {
        $query = "UPDATE " . $this->table_name . " 
                SET first_name = :first_name, 
                    last_name = :last_name, 
                    email = :email, 
                    updated_at = :updated_at 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Verileri temizleme
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->updated_at = date('Y-m-d H:i:s');

        // Parametreleri bağlama
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':updated_at', $this->updated_at);
        $stmt->bindParam(':id', $this->id);

        // Sorguyu çalıştırma
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Parola değiştirme
    public function changePassword() {
        $query = "UPDATE " . $this->table_name . " 
                SET password = :password, 
                    updated_at = :updated_at 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Güvenli parola şifreleme
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $this->updated_at = date('Y-m-d H:i:s');

        // Parametreleri bağlama
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':updated_at', $this->updated_at);
        $stmt->bindParam(':id', $this->id);

        // Sorguyu çalıştırma
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // E-posta adresi kontrolü
    public function emailExists() {
        $query = "SELECT id, first_name, last_name, email, password 
                FROM " . $this->table_name . " 
                WHERE email = ?
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}
?> 