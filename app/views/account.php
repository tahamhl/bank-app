<?php
// Veritabanı bağlantısı ve gerekli sınıfları yükleme
$db = new Database();
$db_conn = $db->getConnection();

// Controller'ları başlatma
$accountController = new AccountController($db_conn);
$transactionController = new TransactionController($db_conn);

// Kullanıcının hesaplarını getirme
$accounts = $accountController->getAccounts();

// Hesap detayları için id kontrolü
$account_details = null;
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $account_details = $accountController->getAccountDetails($_GET['id']);
    
    // Hesaba ait işlemleri getir
    $transactions = $transactionController->getAccountTransactions($account_details['id']);
}

$title = "BankApp - Hesaplarım";
ob_start();
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Hesaplarım</h1>
    <p class="text-gray-600">Tüm hesaplarınızı görüntüleyebilir, yeni hesap açabilir ve hesap işlemlerinizi yönetebilirsiniz.</p>
</div>

<!-- Hesap İşlemleri -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Hesap Oluştur Kartı -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Yeni Hesap Oluştur</h2>
        
        <?php if(isset($_SESSION['account_errors'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <ul class="list-disc pl-4">
                    <?php foreach($_SESSION['account_errors'] as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['account_errors']); ?>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>create-account" method="post">
            <div class="mb-4">
                <label for="account_type" class="block text-gray-700 text-sm font-bold mb-2">Hesap Türü</label>
                <select id="account_type" name="account_type" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Hesap türü seçin</option>
                    <option value="checking">Vadesiz Hesap</option>
                    <option value="saving">Vadeli Hesap</option>
                    <option value="credit">Kredi Hesabı</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="currency" class="block text-gray-700 text-sm font-bold mb-2">Para Birimi</label>
                <select id="currency" name="currency" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="TL">Türk Lirası (TL)</option>
                    <option value="USD">Amerikan Doları (USD)</option>
                    <option value="EUR">Euro (EUR)</option>
                    <option value="GBP">İngiliz Sterlini (GBP)</option>
                </select>
            </div>
            
            <div class="mb-6">
                <label for="initial_deposit" class="block text-gray-700 text-sm font-bold mb-2">Başlangıç Bakiyesi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                    </div>
                    <input type="number" step="0.01" min="0" id="initial_deposit" name="initial_deposit" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                </div>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-plus-circle mr-2"></i>Hesap Oluştur
            </button>
        </form>
    </div>
    
    <!-- Para Yatırma Kartı -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Para Yatır</h2>
        
        <?php if(isset($_SESSION['deposit_errors'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <ul class="list-disc pl-4">
                    <?php foreach($_SESSION['deposit_errors'] as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['deposit_errors']); ?>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>deposit" method="post">
            <div class="mb-4">
                <label for="account_id_deposit" class="block text-gray-700 text-sm font-bold mb-2">Hesap Seçin</label>
                <select id="account_id_deposit" name="account_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Hesap seçin</option>
                    <?php foreach($accounts as $account): ?>
                        <?php if($account['status'] == 'active'): ?>
                            <option value="<?= $account['id'] ?>"><?= $account['account_number'] ?> (<?= number_format($account['balance'], 2, ',', '.') ?> <?= $account['currency'] ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="amount_deposit" class="block text-gray-700 text-sm font-bold mb-2">Yatırılacak Tutar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                    </div>
                    <input type="number" step="0.01" min="0.01" id="amount_deposit" name="amount" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="description_deposit" class="block text-gray-700 text-sm font-bold mb-2">Açıklama</label>
                <textarea id="description_deposit" name="description" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Para yatırma açıklaması"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                <i class="fas fa-arrow-down mr-2"></i>Para Yatır
            </button>
        </form>
    </div>
    
    <!-- Para Çekme Kartı -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Para Çek</h2>
        
        <?php if(isset($_SESSION['withdraw_errors'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <ul class="list-disc pl-4">
                    <?php foreach($_SESSION['withdraw_errors'] as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['withdraw_errors']); ?>
        <?php endif; ?>
        
        <form action="<?= BASE_URL ?>withdraw" method="post">
            <div class="mb-4">
                <label for="account_id_withdraw" class="block text-gray-700 text-sm font-bold mb-2">Hesap Seçin</label>
                <select id="account_id_withdraw" name="account_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">Hesap seçin</option>
                    <?php foreach($accounts as $account): ?>
                        <?php if($account['status'] == 'active'): ?>
                            <option value="<?= $account['id'] ?>"><?= $account['account_number'] ?> (<?= number_format($account['balance'], 2, ',', '.') ?> <?= $account['currency'] ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="amount_withdraw" class="block text-gray-700 text-sm font-bold mb-2">Çekilecek Tutar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-money-bill-wave text-gray-400"></i>
                    </div>
                    <input type="number" step="0.01" min="0.01" id="amount_withdraw" name="amount" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="description_withdraw" class="block text-gray-700 text-sm font-bold mb-2">Açıklama</label>
                <textarea id="description_withdraw" name="description" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Para çekme açıklaması"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <i class="fas fa-arrow-up mr-2"></i>Para Çek
            </button>
        </form>
    </div>
</div>

<!-- Hesap Detayları veya Hesap Listesi -->
<?php if($account_details): ?>
    <!-- Hesap Detayları -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
        <div class="bg-gray-50 border-b p-4 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-700">Hesap Detayları</h3>
            <a href="<?= BASE_URL ?>account" class="text-blue-600 hover:text-blue-800 text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Hesaplara Dön
            </a>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Hesap Numarası</h4>
                        <p class="text-lg font-semibold"><?= $account_details['account_number'] ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Hesap Türü</h4>
                        <p class="text-lg font-semibold"><?= ucfirst($account_details['account_type']) ?> Hesabı</p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Açılış Tarihi</h4>
                        <p class="text-lg font-semibold"><?= date('d.m.Y', strtotime($account_details['created_at'])) ?></p>
                    </div>
                </div>
                
                <div>
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Güncel Bakiye</h4>
                        <p class="text-2xl font-bold text-blue-600"><?= number_format($account_details['balance'], 2, ',', '.') ?> <?= $account_details['currency'] ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-500">Durumu</h4>
                        <p class="inline-block px-3 py-1 text-sm font-semibold rounded-full <?= $account_details['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $account_details['status'] == 'active' ? 'Aktif' : 'Pasif' ?>
                        </p>
                    </div>
                    
                    <!-- Hesap Durum Değiştirme Butonu -->
                    <form action="<?= BASE_URL ?>toggle-account-status" method="post" class="mt-2">
                        <input type="hidden" name="account_id" value="<?= $account_details['id'] ?>">
                        <input type="hidden" name="action" value="<?= $account_details['status'] == 'active' ? 'deactivate' : 'activate' ?>">
                        <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg <?= $account_details['status'] == 'active' ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-green-600 hover:bg-green-700 text-white' ?>">
                            <?= $account_details['status'] == 'active' ? '<i class="fas fa-ban mr-1"></i> Hesabı Kapat' : '<i class="fas fa-check mr-1"></i> Hesabı Aktifleştir' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hesap İşlemleri -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b p-4">
            <h3 class="text-lg font-semibold text-gray-700">Hesap İşlemleri</h3>
        </div>
        
        <div class="p-4">
            <?php if(count($transactions) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlem Tipi</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hedef/Kaynak</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutar</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Açıklama</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach($transactions as $transaction): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        <?= date('d.m.Y H:i', strtotime($transaction['created_at'])) ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <?php if($transaction['transaction_type'] == 'deposit'): ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Para Yatırma</span>
                                        <?php elseif($transaction['transaction_type'] == 'withdrawal'): ?>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Para Çekme</span>
                                        <?php else: ?>
                                            <?php if($transaction['source_account_id'] == $account_details['id']): ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Giden Transfer</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Gelen Transfer</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        <?php if($transaction['transaction_type'] == 'transfer'): ?>
                                            <?php if($transaction['source_account_id'] == $account_details['id']): ?>
                                                <?= $transaction['destination_account_number'] ? $transaction['destination_account_number'] : 'Bilinmiyor' ?>
                                            <?php else: ?>
                                                <?= $transaction['source_account_number'] ? $transaction['source_account_number'] : 'Bilinmiyor' ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm font-semibold
                                        <?php if($transaction['transaction_type'] == 'deposit' || ($transaction['transaction_type'] == 'transfer' && $transaction['destination_account_id'] == $account_details['id'])): ?>
                                            text-green-600
                                        <?php else: ?>
                                            text-red-600
                                        <?php endif; ?>
                                    ">
                                        <?php if($transaction['transaction_type'] == 'deposit' || ($transaction['transaction_type'] == 'transfer' && $transaction['destination_account_id'] == $account_details['id'])): ?>
                                            +<?= number_format($transaction['amount'], 2, ',', '.') ?> <?= $account_details['currency'] ?>
                                        <?php else: ?>
                                            -<?= number_format($transaction['amount'], 2, ',', '.') ?> <?= $account_details['currency'] ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        <?= $transaction['description'] ? $transaction['description'] : 'Açıklama yok' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-exchange-alt text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">Bu hesaba ait işlem bulunamadı.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <!-- Hesap Listesi -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gray-50 border-b p-4">
            <h3 class="text-lg font-semibold text-gray-700">Tüm Hesaplarınız</h3>
        </div>
        
        <div class="p-4">
            <?php if(count($accounts) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hesap Numarası</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hesap Türü</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bakiye</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Açılış Tarihi</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach($accounts as $account): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-sm font-medium text-gray-900">
                                        <?= $account['account_number'] ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        <?= ucfirst($account['account_type']) ?> Hesabı
                                    </td>
                                    <td class="py-3 px-4 text-sm font-medium 
                                        <?= $account['balance'] > 0 ? 'text-green-600' : ($account['balance'] < 0 ? 'text-red-600' : 'text-gray-600') ?>">
                                        <?= number_format($account['balance'], 2, ',', '.') ?> <?= $account['currency'] ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $account['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $account['status'] == 'active' ? 'Aktif' : 'Pasif' ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-sm text-gray-800">
                                        <?= date('d.m.Y', strtotime($account['created_at'])) ?>
                                    </td>
                                    <td class="py-3 px-4 text-sm">
                                        <a href="<?= BASE_URL ?>account?id=<?= $account['id'] ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                            <i class="fas fa-eye"></i> Detaylar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-credit-card text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">Henüz hiç hesabınız yok.</p>
                    <p class="text-gray-500 mt-2">Yukarıdaki "Yeni Hesap Oluştur" formunu kullanarak hemen bir hesap açabilirsiniz.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>

<?php
$title = "BankApp - Hesaplarım";
include('layout.php');
?> 