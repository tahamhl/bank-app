<?php
$title = "BankApp - Kayıt Ol";
ob_start();
?>

<div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg p-6 mt-10">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Hesap Oluştur</h2>
        <p class="text-gray-600 mt-2">Hızlı ve güvenli bankacılık için hemen kayıt olun</p>
    </div>

    <?php if(isset($_SESSION['register_errors'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <ul class="list-disc pl-4">
                <?php foreach($_SESSION['register_errors'] as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['register_errors']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>register" method="post">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">Ad</label>
                <input type="text" id="first_name" name="first_name" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Adınız" value="<?= $_SESSION['register_form_data']['first_name'] ?? '' ?>" required>
            </div>
            <div>
                <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Soyad</label>
                <input type="text" id="last_name" name="last_name" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Soyadınız" value="<?= $_SESSION['register_form_data']['last_name'] ?? '' ?>" required>
            </div>
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">E-posta Adresi</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input type="email" id="email" name="email" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="E-posta adresiniz" value="<?= $_SESSION['register_form_data']['email'] ?? '' ?>" required>
            </div>
        </div>
        
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Parola</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="password" name="password" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="En az 6 karakter" required>
            </div>
            <p class="text-xs text-gray-500 mt-1">Parolanız en az 6 karakter uzunluğunda olmalıdır.</p>
        </div>
        
        <div class="mb-6">
            <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Parola Tekrar</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="confirm_password" name="confirm_password" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Parolanızı tekrar girin" required>
            </div>
        </div>
        
        <div class="mb-6">
            <div class="flex items-center">
                <input type="checkbox" id="terms" name="terms" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" required>
                <label for="terms" class="ml-2 block text-sm text-gray-700">
                    <a href="#" class="text-blue-600 hover:text-blue-800">Kullanım Şartları</a> ve <a href="#" class="text-blue-600 hover:text-blue-800">Gizlilik Politikası</a>'nı kabul ediyorum
                </label>
            </div>
        </div>
        
        <div class="mb-6">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-user-plus mr-2"></i>Kayıt Ol
            </button>
        </div>
    </form>
    
    <div class="text-center">
        <p class="text-gray-600">Zaten hesabınız var mı? <a href="<?= BASE_URL ?>login" class="text-blue-600 hover:text-blue-800 font-semibold">Giriş yapın</a></p>
    </div>
</div>

<?php
$content = ob_get_clean();
include('layout.php');
?> 