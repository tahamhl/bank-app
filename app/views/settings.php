<?php
// Veritabanı bağlantısı ve gerekli sınıfları yükleme
$db = new Database();
$db_conn = $db->getConnection();

// Controller'ları başlatma
$userController = new UserController($db_conn);

// Kullanıcı bilgilerini getir
$user = new User($db_conn);
$user->getUserById($_SESSION['user_id']);

$title = "BankApp - Ayarlar";
ob_start();
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Hesap Ayarları</h1>
    <p class="text-gray-600">Kullanıcı bilgilerinizi görüntüleyebilir ve güncelleyebilirsiniz.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- Profil Bilgileri -->
    <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 border-b p-4">
                <h3 class="text-lg font-semibold text-gray-700">Profil Bilgileri</h3>
            </div>
            
            <div class="p-6">
                <?php if(isset($_SESSION['profile_errors'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <ul class="list-disc pl-4">
                            <?php foreach($_SESSION['profile_errors'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['profile_errors']); ?>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>update-profile" method="post">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">Ad</label>
                            <input type="text" id="first_name" name="first_name" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= $user->first_name ?>" required>
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Soyad</label>
                            <input type="text" id="last_name" name="last_name" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= $user->last_name ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">E-posta Adresi</label>
                        <input type="email" id="email" name="email" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= $user->email ?>" required>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Değişiklikleri Kaydet
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Parola Değiştirme -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-8">
            <div class="bg-gray-50 border-b p-4">
                <h3 class="text-lg font-semibold text-gray-700">Parola Değiştir</h3>
            </div>
            
            <div class="p-6">
                <?php if(isset($_SESSION['password_errors'])): ?>
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <ul class="list-disc pl-4">
                            <?php foreach($_SESSION['password_errors'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['password_errors']); ?>
                <?php endif; ?>
                
                <form action="<?= BASE_URL ?>change-password" method="post">
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 text-sm font-bold mb-2">Mevcut Parola</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="current_password" name="current_password" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700 text-sm font-bold mb-2">Yeni Parola</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="new_password" name="new_password" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Parolanız en az 6 karakter uzunluğunda olmalıdır.</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Yeni Parola Tekrar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="confirm_password" name="confirm_password" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                        <i class="fas fa-key mr-2"></i>Parolayı Değiştir
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Yan Panel -->
    <div class="md:col-span-1">
        <!-- Hesap Özeti -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-gray-50 border-b p-4">
                <h3 class="text-lg font-semibold text-gray-700">Hesap Özeti</h3>
            </div>
            
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mr-4">
                        <?= strtoupper(substr($user->first_name, 0, 1)) . strtoupper(substr($user->last_name, 0, 1)) ?>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold"><?= $user->first_name . ' ' . $user->last_name ?></h4>
                        <p class="text-sm text-gray-600"><?= $user->email ?></p>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600">Üyelik Tarihi</span>
                        <span class="font-semibold"><?= date('d.m.Y', strtotime($user->created_at)) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Hesap Durumu</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Güvenlik İpuçları -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gray-50 border-b p-4">
                <h3 class="text-lg font-semibold text-gray-700">Güvenlik İpuçları</h3>
            </div>
            
            <div class="p-6">
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm text-gray-600">Güçlü bir parola kullanın. Büyük harf, küçük harf, rakam ve özel karakterler içermeli.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm text-gray-600">Parolanızı düzenli olarak değiştirin ve başka web sitelerinde kullanmayın.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm text-gray-600">E-posta adresinizin güncel olduğundan emin olun. Önemli bildirimler için kullanılır.</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                        <span class="text-sm text-gray-600">Hesabınızda şüpheli bir işlem fark ederseniz hemen müşteri hizmetleriyle iletişime geçin.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
include('layout.php');
?> 