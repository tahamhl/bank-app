<?php
require_once 'app/models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($db);
    }

    // Kullanıcı kaydı
    public function register() {
        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validasyon
            $errors = [];
            
            if(empty($first_name)) {
                $errors[] = "Ad alanı zorunludur";
            }
            
            if(empty($last_name)) {
                $errors[] = "Soyad alanı zorunludur";
            }
            
            if(empty($email)) {
                $errors[] = "E-posta alanı zorunludur";
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Geçerli bir e-posta adresi giriniz";
            }
            
            if(empty($password)) {
                $errors[] = "Parola alanı zorunludur";
            } elseif(strlen($password) < 6) {
                $errors[] = "Parola en az 6 karakter olmalıdır";
            }
            
            if($password !== $confirm_password) {
                $errors[] = "Parolalar eşleşmiyor";
            }

            // E-posta adresi kontrolü
            $this->user->email = $email;
            if($this->user->emailExists()) {
                $errors[] = "Bu e-posta adresi zaten kayıtlı";
            }

            // Hata yoksa kullanıcı kaydını oluştur
            if(empty($errors)) {
                $this->user->first_name = $first_name;
                $this->user->last_name = $last_name;
                $this->user->email = $email;
                $this->user->password = $password;

                if($this->user->register()) {
                    // Başarılı kayıt sonrası giriş sayfasına yönlendir
                    header("Location: " . BASE_URL . "login?registered=true");
                    exit();
                } else {
                    $errors[] = "Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyin.";
                }
            }

            // Hataları session'a at ve register sayfasına yönlendir
            $_SESSION['register_errors'] = $errors;
            $_SESSION['register_form_data'] = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email
            ];
            
            header("Location: " . BASE_URL . "register");
            exit();
        }
    }

    // Kullanıcı girişi
    public function login() {
        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validasyon
            $errors = [];
            
            if(empty($email)) {
                $errors[] = "E-posta alanı zorunludur";
            }
            
            if(empty($password)) {
                $errors[] = "Parola alanı zorunludur";
            }

            // Hata yoksa giriş yap
            if(empty($errors)) {
                $this->user->email = $email;
                $this->user->password = $password;

                if($this->user->login()) {
                    // Kullanıcı bilgilerini session'a kaydet
                    $_SESSION['user_id'] = $this->user->id;
                    $_SESSION['user_name'] = $this->user->first_name . ' ' . $this->user->last_name;
                    $_SESSION['user_email'] = $this->user->email;

                    // Dashboard'a yönlendir
                    header("Location: " . BASE_URL . "dashboard");
                    exit();
                } else {
                    $errors[] = "E-posta veya parola hatalı";
                }
            }

            // Hataları session'a at ve login sayfasına yönlendir
            $_SESSION['login_errors'] = $errors;
            $_SESSION['login_form_data'] = [
                'email' => $email
            ];
            
            header("Location: " . BASE_URL . "login");
            exit();
        }
    }

    // Kullanıcı profili güncelleme
    public function updateProfile() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $first_name = $_POST['first_name'] ?? '';
            $last_name = $_POST['last_name'] ?? '';
            $email = $_POST['email'] ?? '';

            // Validasyon
            $errors = [];
            
            if(empty($first_name)) {
                $errors[] = "Ad alanı zorunludur";
            }
            
            if(empty($last_name)) {
                $errors[] = "Soyad alanı zorunludur";
            }
            
            if(empty($email)) {
                $errors[] = "E-posta alanı zorunludur";
            } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Geçerli bir e-posta adresi giriniz";
            }

            // Mevcut kullanıcı bilgilerini al
            $this->user->getUserById($_SESSION['user_id']);

            // E-posta değiştiyse kontrol et
            if($email !== $this->user->email) {
                $tempUser = new User($this->db);
                $tempUser->email = $email;
                if($tempUser->emailExists()) {
                    $errors[] = "Bu e-posta adresi zaten kayıtlı";
                }
            }

            // Hata yoksa profili güncelle
            if(empty($errors)) {
                $this->user->id = $_SESSION['user_id'];
                $this->user->first_name = $first_name;
                $this->user->last_name = $last_name;
                $this->user->email = $email;

                if($this->user->updateProfile()) {
                    // Session bilgilerini güncelle
                    $_SESSION['user_name'] = $this->user->first_name . ' ' . $this->user->last_name;
                    $_SESSION['user_email'] = $this->user->email;

                    $_SESSION['success_message'] = "Profil bilgileriniz başarıyla güncellendi";
                } else {
                    $errors[] = "Profil güncellenirken bir hata oluştu. Lütfen tekrar deneyin.";
                }
            }

            // Hataları session'a at
            if(!empty($errors)) {
                $_SESSION['profile_errors'] = $errors;
            }
            
            header("Location: " . BASE_URL . "settings");
            exit();
        }
    }

    // Parola değiştirme
    public function changePassword() {
        // Kullanıcı girişi kontrolü
        if(!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . "login");
            exit();
        }

        // POST verilerini kontrol et
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            // Form verilerini al
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validasyon
            $errors = [];
            
            if(empty($current_password)) {
                $errors[] = "Mevcut parola alanı zorunludur";
            }
            
            if(empty($new_password)) {
                $errors[] = "Yeni parola alanı zorunludur";
            } elseif(strlen($new_password) < 6) {
                $errors[] = "Yeni parola en az 6 karakter olmalıdır";
            }
            
            if($new_password !== $confirm_password) {
                $errors[] = "Yeni parolalar eşleşmiyor";
            }

            // Mevcut parola kontrolü
            $this->user->getUserById($_SESSION['user_id']);
            $tempUser = new User($this->db);
            $tempUser->email = $this->user->email;
            $tempUser->password = $current_password;

            if(!$tempUser->login()) {
                $errors[] = "Mevcut parola hatalı";
            }

            // Hata yoksa parolayı güncelle
            if(empty($errors)) {
                $this->user->id = $_SESSION['user_id'];
                $this->user->password = $new_password;

                if($this->user->changePassword()) {
                    $_SESSION['success_message'] = "Parolanız başarıyla değiştirildi";
                } else {
                    $errors[] = "Parola değiştirilirken bir hata oluştu. Lütfen tekrar deneyin.";
                }
            }

            // Hataları session'a at
            if(!empty($errors)) {
                $_SESSION['password_errors'] = $errors;
            }
            
            header("Location: " . BASE_URL . "settings");
            exit();
        }
    }
}
?> 