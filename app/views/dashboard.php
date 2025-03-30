<?php
// Veritabanı bağlantısı ve gerekli sınıfları yükleme
$db = new Database();
$db_conn = $db->getConnection();

// Controller'ları başlatma
$accountController = new AccountController($db_conn);
$transactionController = new TransactionController($db_conn);

// Kullanıcının hesaplarını ve işlemlerini getirme
$accounts = $accountController->getAccounts();
$transactions = $transactionController->getTransactions();

// Toplam bakiye hesaplama
$total_balance = [];
if ($accounts) {
    foreach($accounts as $account) {
        if($account['status'] == 'active') {
            $currency = $account['currency'];
            if(!isset($total_balance[$currency])) {
                $total_balance[$currency] = 0;
            }
            $total_balance[$currency] += $account['balance'];
        }
    }
}

$title = "BankApp - Gösterge Paneli";
ob_start();
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Gösterge Paneli</h1>
    <p class="text-gray-600">Hoş geldiniz, <?= $_SESSION['user_name'] ?>! İşte hesaplarınızın genel durumu.</p>
</div>

<!-- Özet Kartları -->
<div class="grid grid-cols-1 md:grid-cols-<?= count($total_balance) + 2 ?> gap-6 mb-8">
    <!-- Para Birimine Göre Bakiyeler -->
    <?php foreach($total_balance as $currency => $amount): ?>
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Toplam <?= $currency ?> Bakiye</h3>
            <div class="bg-blue-100 p-2 rounded-full">
                <i class="fas fa-wallet text-blue-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= number_format($amount, 2, ',', '.') ?> <?= $currency ?></p>
        <p class="text-sm text-gray-500 mt-2"><?= $currency ?> hesaplarınızın toplam bakiyesi</p>
    </div>
    <?php endforeach; ?>

    <!-- Toplam Hesap -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Hesaplarınız</h3>
            <div class="bg-green-100 p-2 rounded-full">
                <i class="fas fa-credit-card text-green-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= count($accounts) ?></p>
        <p class="text-sm text-gray-500 mt-2">Toplam hesap sayınız</p>
    </div>

    <!-- Son İşlemler -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Son İşlemler</h3>
            <div class="bg-purple-100 p-2 rounded-full">
                <i class="fas fa-history text-purple-500"></i>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= count($transactions) ?></p>
        <p class="text-sm text-gray-500 mt-2">Toplam işlem sayınız</p>
    </div>
</div>

<!-- Hesaplar ve İşlemler -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Hesaplar Listesi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b p-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-700">Hesaplarınız</h3>
            <a href="<?= BASE_URL ?>account" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-chevron-right"></i> Tümünü Gör
            </a>
        </div>
        
        <div class="p-4">
            <?php if(count($accounts) > 0): ?>
                <div class="space-y-4">
                    <?php foreach(array_slice($accounts, 0, 3) as $account): ?>
                        <div class="border rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 <?= $account['status'] == 'active' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' ?>">
                            <div>
                                <p class="font-semibold truncate"><?= $account['account_number'] ?></p>
                                <p class="text-sm text-gray-600"><?= ucfirst($account['account_type']) ?> Hesabı</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800"><?= number_format($account['balance'], 2, ',', '.') ?> <?= $account['currency'] ?></p>
                                <p class="text-xs <?= $account['status'] == 'active' ? 'text-green-600' : 'text-red-600' ?>"><?= $account['status'] == 'active' ? 'Aktif' : 'Pasif' ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if(count($accounts) > 3): ?>
                    <div class="mt-4 text-center">
                        <a href="<?= BASE_URL ?>account" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus-circle mr-1"></i> <?= count($accounts) - 3 ?> hesap daha görüntüle
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-credit-card text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">Henüz hiç hesabınız yok.</p>
                    <a href="<?= BASE_URL ?>account" class="mt-3 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-plus mr-1"></i> Hesap Oluştur
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Son İşlemler -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b p-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-700">Son İşlemler</h3>
            <a href="<?= BASE_URL ?>transfer" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-chevron-right"></i> Tümünü Gör
            </a>
        </div>
        
        <div class="p-4">
            <?php if(count($transactions) > 0): ?>
                <div class="space-y-4">
                    <?php foreach(array_slice($transactions, 0, 5) as $transaction): ?>
                        <div class="border rounded-lg p-4 flex justify-between items-center">
                            <div class="flex items-center">
                                <?php if($transaction['transaction_type'] == 'deposit'): ?>
                                    <div class="bg-green-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-arrow-down text-green-500"></i>
                                    </div>
                                <?php elseif($transaction['transaction_type'] == 'withdrawal'): ?>
                                    <div class="bg-red-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-arrow-up text-red-500"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-blue-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-exchange-alt text-blue-500"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <p class="font-semibold">
                                        <?php 
                                            if($transaction['transaction_type'] == 'deposit') {
                                                echo 'Para Yatırma';
                                            } elseif($transaction['transaction_type'] == 'withdrawal') {
                                                echo 'Para Çekme';
                                            } else {
                                                echo 'Para Transferi';
                                            }
                                        ?>
                                    </p>
                                    <p class="text-xs text-gray-500"><?= date('d.m.Y H:i', strtotime($transaction['created_at'])) ?></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold <?= $transaction['transaction_type'] == 'deposit' ? 'text-green-600' : ($transaction['transaction_type'] == 'withdrawal' ? 'text-red-600' : 'text-blue-600') ?>">
                                    <?= $transaction['transaction_type'] == 'deposit' ? '+' : ($transaction['transaction_type'] == 'withdrawal' ? '-' : '') ?><?= number_format($transaction['amount'], 2, ',', '.') ?> TL
                                </p>
                                <p class="text-xs text-gray-500 truncate" title="<?= $transaction['source_account_number'] ?>">
                                    <?= substr($transaction['source_account_number'], 0, 8) ?>...
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if(count($transactions) > 5): ?>
                    <div class="mt-4 text-center">
                        <a href="<?= BASE_URL ?>transfer" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus-circle mr-1"></i> <?= count($transactions) - 5 ?> işlem daha görüntüle
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-exchange-alt text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">Henüz hiç işleminiz yok.</p>
                    <a href="<?= BASE_URL ?>transfer" class="mt-3 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-exchange-alt mr-1"></i> Transfer Yap
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Hızlı İşlemler -->
<div class="mt-8 bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Hızlı İşlemler</h3>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?= BASE_URL ?>account" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition-colors">
            <div class="text-blue-500 text-2xl mb-2">
                <i class="fas fa-plus-circle"></i>
            </div>
            <p class="font-semibold text-gray-800">Yeni Hesap</p>
        </a>
        
        <a href="<?= BASE_URL ?>transfer" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition-colors">
            <div class="text-green-500 text-2xl mb-2">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <p class="font-semibold text-gray-800">Para Transferi</p>
        </a>
        
        <a href="<?= BASE_URL ?>account" class="bg-purple-50 hover:bg-purple-100 p-4 rounded-lg text-center transition-colors">
            <div class="text-purple-500 text-2xl mb-2">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <p class="font-semibold text-gray-800">Para Yatır</p>
        </a>
        
        <a href="<?= BASE_URL ?>account" class="bg-red-50 hover:bg-red-100 p-4 rounded-lg text-center transition-colors">
            <div class="text-red-500 text-2xl mb-2">
                <i class="fas fa-money-bill-alt"></i>
            </div>
            <p class="font-semibold text-gray-800">Para Çek</p>
        </a>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
include('layout.php');
?> 