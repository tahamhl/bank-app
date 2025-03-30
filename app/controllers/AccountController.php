<?php
require_once 'app/models/Account.php';

class AccountController {
    private $db;
    private $account;

    public function __construct($db) {
        $this->db = $db;
        $this->account = new Account($db);
    }

    // Yeni hesap oluşturma
    public function createAccount() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $account_type = $_POST['account_type'] ?? '';
            $currency = $_POST['currency'] ?? 'TL';
            $initial_deposit = floatval($_POST['initial_deposit'] ?? 0);

            // Validasyon
            $errors = [];
            
            if(empty($account_type)) {
                $errors[] = "Hesap türü seçilmelidir";
            }
            
            if($initial_deposit < 0) {
                $errors[] = "Başlangıç bakiyesi negatif olamaz";
            }

            // Hata yoksa hesap oluştur
            if(empty($errors)) {
                $this->account->account_number = $this->account->generateAccountNumber();
                $this->account->user_id = $_SESSION['user_id'];
                $this->account->account_type = $account_type;
                $this->account->balance = $initial_deposit;
                $this->account->currency = $currency;
                $this->account->status = 'active';

                if($this->account->createAccount()) {
                    $_SESSION['success_message'] = "Hesap başarıyla oluşturuldu";
                    header("Location: " . BASE_URL . "account");
                    exit();
                } else {
                    $errors[] = "Hesap oluşturulurken bir hata oluştu. Lütfen tekrar deneyin.";
                }
            }

            // Hataları session'a at
            $_SESSION['account_errors'] = $errors;
            header("Location: " . BASE_URL . "account");
            exit();
        }
    }

    // Hesapları getirme
    public function getAccounts() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // Kullanıcının hesaplarını getir
        $stmt = $this->account->getUserAccounts($_SESSION['user_id']);
        $accounts = [];

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $accounts[] = $row;
        }

        return $accounts;
    }

    // Hesap detaylarını getirme
    public function getAccountDetails($id) {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // Hesap bilgilerini getir
        if($this->account->getAccountById($id)) {
            // Hesap kullanıcıya ait mi kontrol et
            if($this->account->user_id != $_SESSION['user_id']) {
                $_SESSION['error_message'] = "Bu hesaba erişim izniniz yok";
                header("Location: " . BASE_URL . "account");
                exit();
            }

            return [
                'id' => $this->account->id,
                'account_number' => $this->account->account_number,
                'account_type' => $this->account->account_type,
                'balance' => $this->account->balance,
                'currency' => $this->account->currency,
                'status' => $this->account->status,
                'created_at' => $this->account->created_at
            ];
        } else {
            $_SESSION['error_message'] = "Hesap bulunamadı";
            header("Location: " . BASE_URL . "account");
            exit();
        }
    }

    // Hesap kapama/açma
    public function toggleAccountStatus() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $account_id = $_POST['account_id'] ?? 0;
            $action = $_POST['action'] ?? '';

            // Validasyon
            $errors = [];
            
            if(empty($account_id) || empty($action)) {
                $errors[] = "Geçersiz istek";
            }

            if($action != 'activate' && $action != 'deactivate') {
                $errors[] = "Geçersiz işlem";
            }

            // Hata yoksa işleme devam et
            if(empty($errors)) {
                // Hesap bilgilerini getir
                if($this->account->getAccountById($account_id)) {
                    // Hesap kullanıcıya ait mi kontrol et
                    if($this->account->user_id != $_SESSION['user_id']) {
                        $_SESSION['error_message'] = "Bu hesaba erişim izniniz yok";
                        header("Location: " . BASE_URL . "account");
                        exit();
                    }

                    // Hesap durumunu güncelle
                    $status = $action == 'activate' ? 'active' : 'inactive';
                    if($this->account->updateStatus($status)) {
                        $_SESSION['success_message'] = "Hesap durumu başarıyla güncellendi";
                    } else {
                        $_SESSION['error_message'] = "Hesap durumu güncellenirken bir hata oluştu";
                    }
                } else {
                    $_SESSION['error_message'] = "Hesap bulunamadı";
                }
            } else {
                $_SESSION['error_message'] = $errors[0];
            }

            header("Location: " . BASE_URL . "account");
            exit();
        }
    }
}
?> 