<?php
class Account {
    private $conn;
    private $table_name = "accounts";

    public $id;
    public $account_number;
    public $user_id;
    public $account_type;
    public $balance;
    public $currency;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Yeni hesap oluşturma
    public function createAccount() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET account_number = :account_number, 
                    user_id = :user_id, 
                    account_type = :account_type, 
                    balance = :balance, 
                    currency = :currency, 
                    status = :status, 
                    created_at = :created_at, 
                    updated_at = :updated_at";

        $stmt = $this->conn->prepare($query);

        // Verileri temizleme
        $this->account_number = htmlspecialchars(strip_tags($this->account_number));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->account_type = htmlspecialchars(strip_tags($this->account_type));
        $this->balance = htmlspecialchars(strip_tags($this->balance));
        $this->currency = htmlspecialchars(strip_tags($this->currency));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        // Parametreleri bağlama
        $stmt->bindParam(':account_number', $this->account_number);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':account_type', $this->account_type);
        $stmt->bindParam(':balance', $this->balance);
        $stmt->bindParam(':currency', $this->currency);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':updated_at', $this->updated_at);

        // Sorguyu çalıştırma
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Kullanıcıya ait hesapları getirme
    public function getUserAccounts($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE user_id = ? 
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();

        return $stmt;
    }

    // Hesap numarası ile hesap bilgilerini getirme
    public function getAccountByNumber($account_number) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE account_number = ? 
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $account_number);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->account_number = $row['account_number'];
            $this->user_id = $row['user_id'];
            $this->account_type = $row['account_type'];
            $this->balance = $row['balance'];
            $this->currency = $row['currency'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }

        return false;
    }

    // ID ile hesap bilgilerini getirme
    public function getAccountById($id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE id = ? 
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->account_number = $row['account_number'];
            $this->user_id = $row['user_id'];
            $this->account_type = $row['account_type'];
            $this->balance = $row['balance'];
            $this->currency = $row['currency'];
            $this->status = $row['status'];
            $this->created_at = $row['created_at'];
            $this->updated_at = $row['updated_at'];
            return true;
        }

        return false;
    }

    // Bakiye güncelleme
    public function updateBalance($amount, $type = 'add') {
        $this->updated_at = date('Y-m-d H:i:s');

        if($type == 'add') {
            $this->balance += $amount;
        } else {
            $this->balance -= $amount;
        }

        $query = "UPDATE " . $this->table_name . " 
                SET balance = :balance, 
                    updated_at = :updated_at 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Parametreleri bağlama
        $stmt->bindParam(':balance', $this->balance);
        $stmt->bindParam(':updated_at', $this->updated_at);
        $stmt->bindParam(':id', $this->id);

        // Sorguyu çalıştırma
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Hesap durumunu güncelleme
    public function updateStatus($status) {
        $this->status = $status;
        $this->updated_at = date('Y-m-d H:i:s');

        $query = "UPDATE " . $this->table_name . " 
                SET status = :status, 
                    updated_at = :updated_at 
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Parametreleri bağlama
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':updated_at', $this->updated_at);
        $stmt->bindParam(':id', $this->id);

        // Sorguyu çalıştırma
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Hesap numarası oluşturma
    public function generateAccountNumber() {
        // 16 haneli hesap numarası oluşturma
        return mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(1000, 9999);
    }
}
?> 