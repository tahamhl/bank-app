<?php
require_once 'app/models/Transaction.php';
require_once 'app/models/Account.php';

class TransactionController {
    private $db;
    private $transaction;
    private $account;

    public function __construct($db) {
        $this->db = $db;
        $this->transaction = new Transaction($db);
        $this->account = new Account($db);
    }

    // Para transferi
    public function transfer() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $source_account_id = $_POST['source_account_id'] ?? 0;
            $destination_account_number = $_POST['destination_account_number'] ?? '';
            $amount = floatval($_POST['amount'] ?? 0);
            $description = $_POST['description'] ?? 'Para transferi';

            // Validasyon
            $errors = [];
            
            if(empty($source_account_id)) {
                $errors[] = "Kaynak hesap seçilmelidir";
            }
            
            if(empty($destination_account_number)) {
                $errors[] = "Hedef hesap numarası girilmelidir";
            }
            
            if($amount <= 0) {
                $errors[] = "Transfer tutarı sıfırdan büyük olmalıdır";
            }

            // Hata yoksa işleme devam et
            if(empty($errors)) {
                // Kaynak hesap bilgilerini getir
                $sourceAccount = new Account($this->db);
                if(!$sourceAccount->getAccountById($source_account_id)) {
                    $errors[] = "Kaynak hesap bulunamadı";
                } else {
                    // Hesap kullanıcıya ait mi kontrol et
                    if($sourceAccount->user_id != $_SESSION['user_id']) {
                        $errors[] = "Bu hesaba erişim izniniz yok";
                    }
                    
                    // Hesap aktif mi kontrol et
                    if($sourceAccount->status != 'active') {
                        $errors[] = "Kaynak hesap aktif değil";
                    }
                    
                    // Bakiye yeterli mi kontrol et
                    if($sourceAccount->balance < $amount) {
                        $errors[] = "Yetersiz bakiye";
                    }
                }

                // Hedef hesap bilgilerini getir
                $destinationAccount = new Account($this->db);
                if(!$destinationAccount->getAccountByNumber($destination_account_number)) {
                    $errors[] = "Hedef hesap bulunamadı";
                } else {
                    // Hedef hesap aktif mi kontrol et
                    if($destinationAccount->status != 'active') {
                        $errors[] = "Hedef hesap aktif değil";
                    }

                    // Kaynak ve hedef hesap aynı mı kontrol et
                    if($sourceAccount->id == $destinationAccount->id) {
                        $errors[] = "Aynı hesaba transfer yapamazsınız";
                    }
                }

                // Hata yoksa transferi gerçekleştir
                if(empty($errors)) {
                    if($this->transaction->transfer($sourceAccount, $destinationAccount, $amount, $description)) {
                        $_SESSION['success_message'] = "Transfer başarıyla gerçekleştirildi";
                        header("Location: " . BASE_URL . "transfer");
                        exit();
                    } else {
                        $errors[] = "Transfer sırasında bir hata oluştu. Lütfen tekrar deneyin.";
                    }
                }
            }

            // Hataları session'a at
            $_SESSION['transfer_errors'] = $errors;
            header("Location: " . BASE_URL . "transfer");
            exit();
        }
    }

    // Para yatırma
    public function deposit() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $account_id = $_POST['account_id'] ?? 0;
            $amount = floatval($_POST['amount'] ?? 0);
            $description = $_POST['description'] ?? 'Para yatırma';

            // Validasyon
            $errors = [];
            
            if(empty($account_id)) {
                $errors[] = "Hesap seçilmelidir";
            }
            
            if($amount <= 0) {
                $errors[] = "Yatırılacak tutar sıfırdan büyük olmalıdır";
            }

            // Hata yoksa işleme devam et
            if(empty($errors)) {
                // Hesap bilgilerini getir
                $account = new Account($this->db);
                if(!$account->getAccountById($account_id)) {
                    $errors[] = "Hesap bulunamadı";
                } else {
                    // Hesap kullanıcıya ait mi kontrol et
                    if($account->user_id != $_SESSION['user_id']) {
                        $errors[] = "Bu hesaba erişim izniniz yok";
                    }
                    
                    // Hesap aktif mi kontrol et
                    if($account->status != 'active') {
                        $errors[] = "Hesap aktif değil";
                    }
                }

                // Hata yoksa para yatırma işlemini gerçekleştir
                if(empty($errors)) {
                    if($this->transaction->deposit($account, $amount, $description)) {
                        $_SESSION['success_message'] = "Para yatırma işlemi başarıyla gerçekleştirildi";
                        header("Location: " . BASE_URL . "account");
                        exit();
                    } else {
                        $errors[] = "Para yatırma işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.";
                    }
                }
            }

            // Hataları session'a at
            $_SESSION['deposit_errors'] = $errors;
            header("Location: " . BASE_URL . "account");
            exit();
        }
    }

    // Para çekme
    public function withdraw() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $account_id = $_POST['account_id'] ?? 0;
            $amount = floatval($_POST['amount'] ?? 0);
            $description = $_POST['description'] ?? 'Para çekme';

            // Validasyon
            $errors = [];
            
            if(empty($account_id)) {
                $errors[] = "Hesap seçilmelidir";
            }
            
            if($amount <= 0) {
                $errors[] = "Çekilecek tutar sıfırdan büyük olmalıdır";
            }

            // Hata yoksa işleme devam et
            if(empty($errors)) {
                // Hesap bilgilerini getir
                $account = new Account($this->db);
                if(!$account->getAccountById($account_id)) {
                    $errors[] = "Hesap bulunamadı";
                } else {
                    // Hesap kullanıcıya ait mi kontrol et
                    if($account->user_id != $_SESSION['user_id']) {
                        $errors[] = "Bu hesaba erişim izniniz yok";
                    }
                    
                    // Hesap aktif mi kontrol et
                    if($account->status != 'active') {
                        $errors[] = "Hesap aktif değil";
                    }
                    
                    // Bakiye yeterli mi kontrol et
                    if($account->balance < $amount) {
                        $errors[] = "Yetersiz bakiye";
                    }
                }

                // Hata yoksa para çekme işlemini gerçekleştir
                if(empty($errors)) {
                    if($this->transaction->withdraw($account, $amount, $description)) {
                        $_SESSION['success_message'] = "Para çekme işlemi başarıyla gerçekleştirildi";
                        header("Location: " . BASE_URL . "account");
                        exit();
                    } else {
                        $errors[] = "Para çekme işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.";
                    }
                }
            }

            // Hataları session'a at
            $_SESSION['withdraw_errors'] = $errors;
            header("Location: " . BASE_URL . "account");
            exit();
        }
    }

    // Kullanıcıya ait işlemleri getirme
    public function getTransactions() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // Kullanıcının işlemlerini getir
        $stmt = $this->transaction->getUserTransactions($_SESSION['user_id']);
        $transactions = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $transactions[] = $row;
        }

        return $transactions;
    }

    // Hesaba ait işlemleri getirme
    public function getAccountTransactions($account_id) {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // Hesap bilgilerini getir
        if($this->account->getAccountById($account_id)) {
            // Hesap kullanıcıya ait mi kontrol et
            if($this->account->user_id != $_SESSION['user_id']) {
                $_SESSION['error_message'] = "Bu hesaba erişim izniniz yok";
                header("Location: " . BASE_URL . "account");
                exit();
            }

            // Hesaba ait işlemleri getir
            $stmt = $this->transaction->getAccountTransactions($account_id);
            $transactions = [];

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $transactions[] = $row;
            }

            return $transactions;
        } else {
            $_SESSION['error_message'] = "Hesap bulunamadı";
            header("Location: " . BASE_URL . "account");
            exit();
        }
    }
}
?> 