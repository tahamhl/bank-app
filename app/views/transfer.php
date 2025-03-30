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

$title = "BankApp - Para Transferi";
ob_start();
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Para Transferi</h1>
    <p class="text-gray-600">Hesaplarınız arasında veya farklı hesaplara para transferi yapabilirsiniz.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Para Transferi Formu -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Yeni Transfer</h2>
            
            <?php if(isset($_SESSION['transfer_errors'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <ul class="list-disc pl-4">
                        <?php foreach($_SESSION['transfer_errors'] as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['transfer_errors']); ?>
            <?php endif; ?>
            
            <form action="<?= BASE_URL ?>transfer" method="post">
                <div class="mb-4">
                    <label for="source_account_id" class="block text-gray-700 text-sm font-bold mb-2">Gönderen Hesap</label>
                    <select id="source_account_id" name="source_account_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Gönderen hesabı seçin</option>
                        <?php foreach($accounts as $account): ?>
                            <?php if($account['status'] == 'active'): ?>
                                <option value="<?= $account['id'] ?>"><?= $account['account_number'] ?> (<?= number_format($account['balance'], 2, ',', '.') ?> <?= $account['currency'] ?>)</option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="destination_account_number" class="block text-gray-700 text-sm font-bold mb-2">Alıcı Hesap Numarası</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-credit-card text-gray-400"></i>
                        </div>
                        <input type="text" id="destination_account_number" name="destination_account_number" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Alıcı hesap numarasını girin" required>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Transfer Tutarı</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-money-bill-wave text-gray-400"></i>
                        </div>
                        <input type="number" step="0.01" min="0.01" id="amount" name="amount" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" required>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Açıklama</label>
                    <textarea id="description" name="description" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Transfer açıklaması" rows="3"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-exchange-alt mr-2"></i>Transferi Gerçekleştir
                </button>
            </form>
        </div>
    </div>
    
    <!-- Transfer Bilgileri -->
    <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Transfer Bilgileri</h2>
            
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Neler Bilmelisiniz</h3>
                <ul class="list-disc pl-5 text-sm text-gray-600 space-y-2">
                    <li>Transferler genellikle anında gerçekleşir.</li>
                    <li>Alıcı hesap numarasının doğru olduğundan emin olun.</li>
                    <li>Transfer açıklaması zorunlu değildir ancak tavsiye edilir.</li>
                    <li>Hesap bakiyenizin üzerinde transfer yapamazsınız.</li>
                </ul>
            </div>
            
            <div class="border-t pt-4 mt-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Transfer Limitleri</h3>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Minimum:</span>
                    <span class="font-semibold">0.01 TL</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Maksimum:</span>
                    <span class="font-semibold">Hesap bakiyeniz kadar</span>
                </div>
            </div>
            
            <div class="border-t pt-4 mt-4">
                <h3 class="text-sm font-medium text-gray-500 mb-2">İşlem Ücretleri</h3>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Hesaplar arası transfer:</span>
                    <span class="font-semibold text-green-600">Ücretsiz</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Diğer banka hesaplarına:</span>
                    <span class="font-semibold">Şimdilik Ücretsiz</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Son İşlemler -->
<div class="mt-8 bg-white rounded-lg shadow-md overflow-hidden">
    <div class="bg-gray-50 border-b p-4">
        <h3 class="text-lg font-semibold text-gray-700">Son İşlemleriniz</h3>
    </div>
    
    <div class="p-4">
        <?php if(count($transactions) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlem Tipi</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kaynak Hesap</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hedef Hesap</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutar</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach(array_slice($transactions, 0, 10) as $transaction): ?>
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
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Para Transferi</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-800">
                                    <?= $transaction['source_account_number'] ?>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-800">
                                    <?= $transaction['destination_account_number'] ? $transaction['destination_account_number'] : '-' ?>
                                </td>
                                <td class="py-3 px-4 text-sm font-medium
                                    <?php if($transaction['transaction_type'] == 'deposit'): ?>
                                        text-green-600
                                    <?php else: ?>
                                        text-red-600
                                    <?php endif; ?>
                                ">
                                    <?= number_format($transaction['amount'], 2, ',', '.') ?> TL
                                </td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        <?= $transaction['status'] == 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                        <?= $transaction['status'] == 'completed' ? 'Tamamlandı' : 'İşlemde' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <i class="fas fa-exchange-alt text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Henüz hiç işleminiz yok.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
include('layout.php');
?> 