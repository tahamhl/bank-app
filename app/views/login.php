<?php
$title = "BankApp - Giriş";
ob_start();
?>

<div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-lg p-6 mt-10">
    <div class="text-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Giriş Yap</h2>
        <p class="text-gray-600 mt-2">Hesabınıza giriş yaparak işlemlerinize devam edin</p>
    </div>

    <?php if(isset($_GET['registered']) && $_GET['registered'] == 'true'): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>Kayıt işleminiz başarıyla tamamlandı. Şimdi giriş yapabilirsiniz.</p>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['login_errors'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <ul class="list-disc pl-4">
                <?php foreach($_SESSION['login_errors'] as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['login_errors']); ?>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>login" method="post">
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">E-posta Adresi</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-gray-400"></i>
                </div>
                <input type="email" id="email" name="email" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="E-posta adresinizi girin" value="<?= $_SESSION['login_form_data']['email'] ?? '' ?>" required>
            </div>
        </div>
        
        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Parola</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" id="password" name="password" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Parolanızı girin" required>
            </div>
        </div>
        
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember" class="ml-2 block text-sm text-gray-700">Beni hatırla</label>
            </div>
            <a href="#" class="text-sm text-blue-600 hover:text-blue-800">Parolamı unuttum</a>
        </div>
        
        <div class="mb-6">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-sign-in-alt mr-2"></i>Giriş Yap
            </button>
        </div>
    </form>
    
    <div class="text-center">
        <p class="text-gray-600">Hesabınız yok mu? <a href="<?= BASE_URL ?>register" class="text-blue-600 hover:text-blue-800 font-semibold">Hemen kayıt olun</a></p>
    </div>
</div>

<?php
$content = ob_get_clean();
include('layout.php');
?> 