<?php
class Transaction {
    private $conn;
    private $table_name = "transactions";

    public $id;
    public $transaction_number;
    public $user_id;
    public $source_account_id;
    public $destination_account_id;
    public $amount;
    public $transaction_type;
    public $description;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Yeni işlem kaydı oluşturma
    public function createTransaction() {
        $query = "INSERT INTO " . $this->table_name . " 
                SET transaction_number = :transaction_number, 
                    user_id = :user_id, 
                    source_account_id = :source_account_id, 
                    destination_account_id = :destination_account_id, 
                    amount = :amount, 
                    transaction_type = :transaction_type, 
                    description = :description, 
                    status = :status, 
                    created_at = :created_at, 
                    updated_at = :updated_at";

        $stmt = $this->conn->prepare($query);

        // Verileri temizleme
        $this->transaction_number = htmlspecialchars(strip_tags($this->transaction_number));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->source_account_id = htmlspecialchars(strip_tags($this->source_account_id));
        $this->destination_account_id = $this->destination_account_id !== null ? htmlspecialchars(strip_tags($this->destination_account_id)) : null;
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->transaction_type = htmlspecialchars(strip_tags($this->transaction_type));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        // Parametreleri bağlama
        $stmt->bindParam(':transaction_number', $this->transaction_number);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':source_account_id', $this->source_account_id);
        $stmt->bindParam(':destination_account_id', $this->destination_account_id);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':transaction_type', $this->transaction_type);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':updated_at', $this->updated_at);

        // Sorguyu çalıştırma
        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Para transferi işlemi
    public function transfer($sourceAccount, $destinationAccount, $amount, $description) {
        try {
            // İşlem başlat
            $this->conn->beginTransaction();

            // Kaynak hesaptan para çekme
            if(!$sourceAccount->updateBalance($amount, 'subtract')) {
                throw new Exception("Kaynak hesaptan para çekilemedi.");
            }

            // Hedef hesaba para yatırma
            if(!$destinationAccount->updateBalance($amount, 'add')) {
                throw new Exception("Hedef hesaba para yatırılamadı.");
            }

            // İşlem kaydı oluşturma
            $this->user_id = $sourceAccount->user_id;
            $this->source_account_id = $sourceAccount->id;
            $this->destination_account_id = $destinationAccount->id;
            $this->amount = $amount;
            $this->transaction_type = 'transfer';
            $this->description = $description;
            $this->status = 'completed';
            $this->transaction_number = $this->generateTransactionNumber();

            if(!$this->createTransaction()) {
                throw new Exception("İşlem kaydı oluşturulamadı.");
            }

            // İşlemi tamamla
            $this->conn->commit();
            return true;
            
        } catch(Exception $e) {
            // Hata durumunda işlemi geri al
            $this->conn->rollBack();
            return false;
        }
    }

    // Para yatırma işlemi
    public function deposit($account, $amount, $description) {
        try {
            // İşlem başlat
            $this->conn->beginTransaction();

            // Hesaba para yatırma
            if(!$account->updateBalance($amount, 'add')) {
                throw new Exception("Hesaba para yatırılamadı.");
            }

            // İşlem kaydı oluşturma
            $this->user_id = $account->user_id;
            $this->source_account_id = $account->id;
            $this->destination_account_id = null;
            $this->amount = $amount;
            $this->transaction_type = 'deposit';
            $this->description = $description;
            $this->status = 'completed';
            $this->transaction_number = $this->generateTransactionNumber();

            if(!$this->createTransaction()) {
                throw new Exception("İşlem kaydı oluşturulamadı.");
            }

            // İşlemi tamamla
            $this->conn->commit();
            return true;
            
        } catch(Exception $e) {
            // Hata durumunda işlemi geri al
            $this->conn->rollBack();
            return false;
        }
    }

    // Para çekme işlemi
    public function withdraw($account, $amount, $description) {
        try {
            // İşlem başlat
            $this->conn->beginTransaction();

            // Bakiye kontrolü
            if($account->balance < $amount) {
                throw new Exception("Yetersiz bakiye.");
            }

            // Hesaptan para çekme
            if(!$account->updateBalance($amount, 'subtract')) {
                throw new Exception("Hesaptan para çekilemedi.");
            }

            // İşlem kaydı oluşturma
            $this->user_id = $account->user_id;
            $this->source_account_id = $account->id;
            $this->destination_account_id = null;
            $this->amount = $amount;
            $this->transaction_type = 'withdrawal';
            $this->description = $description;
            $this->status = 'completed';
            $this->transaction_number = $this->generateTransactionNumber();

            if(!$this->createTransaction()) {
                throw new Exception("İşlem kaydı oluşturulamadı.");
            }

            // İşlemi tamamla
            $this->conn->commit();
            return true;
            
        } catch(Exception $e) {
            // Hata durumunda işlemi geri al
            $this->conn->rollBack();
            return false;
        }
    }

    // Kullanıcıya ait işlemleri getirme
    public function getUserTransactions($user_id) {
        $query = "SELECT t.*, 
                    s.account_number as source_account_number, 
                    d.account_number as destination_account_number 
                FROM " . $this->table_name . " t
                LEFT JOIN accounts s ON t.source_account_id = s.id 
                LEFT JOIN accounts d ON t.destination_account_id = d.id 
                WHERE t.user_id = ? 
                ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();

        return $stmt;
    }

    // Hesaba ait işlemleri getirme
    public function getAccountTransactions($account_id) {
        $query = "SELECT t.*, 
                    s.account_number as source_account_number, 
                    d.account_number as destination_account_number 
                FROM " . $this->table_name . " t
                LEFT JOIN accounts s ON t.source_account_id = s.id 
                LEFT JOIN accounts d ON t.destination_account_id = d.id 
                WHERE t.source_account_id = ? OR t.destination_account_id = ? 
                ORDER BY t.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $account_id);
        $stmt->bindParam(2, $account_id);
        $stmt->execute();

        return $stmt;
    }

    // İşlem numarası oluşturma
    public function generateTransactionNumber() {
        // İşlem numarası: TX-zaman-rastgele sayı
        return 'TX-' . time() . '-' . mt_rand(1000, 9999);
    }
}
?> 