<?php
session_start();

// URL yönlendirme için temel URL'yi tanımlama
define('BASE_URL', '/bank/');

// Sayfa yönlendirme
$request = $_SERVER['REQUEST_URI'];
$request = str_replace(BASE_URL, '', $request);
$request = strtok($request, '?');

// Ana dizini yükleme
require_once 'app/config/database.php';

// Database bağlantısı oluşturma
$db = new Database();

// Controller'ları yükleme
require_once 'app/controllers/UserController.php';
require_once 'app/controllers/AccountController.php';
require_once 'app/controllers/TransactionController.php';

// Kullanıcı giriş kontrolü
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Yönlendirme
switch ($request) {
    case '':
    case 'home':
        require 'app/views/home.php';
        break;
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController = new UserController($db->getConnection());
            $userController->login();
            exit;
        }
        
        if (isLoggedIn()) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
        require 'app/views/login.php';
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController = new UserController($db->getConnection());
            $userController->register();
            exit;
        }
        
        if (isLoggedIn()) {
            header('Location: ' . BASE_URL . 'dashboard');
            exit;
        }
        require 'app/views/register.php';
        break;
    case 'update-profile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController = new UserController($db->getConnection());
            $userController->updateProfile();
            exit;
        }
        header('Location: ' . BASE_URL . 'settings');
        exit;
        break;
    case 'change-password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userController = new UserController($db->getConnection());
            $userController->changePassword();
            exit;
        }
        header('Location: ' . BASE_URL . 'settings');
        exit;
        break;
    case 'create-account':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountController = new AccountController($db->getConnection());
            $accountController->createAccount();
            exit;
        }
        header('Location: ' . BASE_URL . 'account');
        exit;
        break;
    case 'deposit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionController = new TransactionController($db->getConnection());
            $transactionController->deposit();
            exit;
        }
        header('Location: ' . BASE_URL . 'account');
        exit;
        break;
    case 'withdraw':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionController = new TransactionController($db->getConnection());
            $transactionController->withdraw();
            exit;
        }
        header('Location: ' . BASE_URL . 'account');
        exit;
        break;
    case 'toggle-account-status':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountController = new AccountController($db->getConnection());
            $accountController->toggleAccountStatus();
            exit;
        }
        header('Location: ' . BASE_URL . 'account');
        exit;
        break;
    case 'dashboard':
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        require 'app/views/dashboard.php';
        break;
    case 'account':
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        require 'app/views/account.php';
        break;
    case 'transfer':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transactionController = new TransactionController($db->getConnection());
            $transactionController->transfer();
            exit;
        }
        
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        require 'app/views/transfer.php';
        break;
    case 'settings':
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        require 'app/views/settings.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: ' . BASE_URL . 'login');
        exit;
        break;
    default:
        http_response_code(404);
        require 'app/views/404.php';
        break;
}
?> 