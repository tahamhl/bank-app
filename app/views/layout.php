<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'BankApp' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="<?= BASE_URL ?>" class="text-2xl font-bold">
                        <i class="fas fa-university mr-2"></i>BankApp
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="hidden md:flex items-center space-x-4">
                            <a href="<?= BASE_URL ?>dashboard" class="hover:text-blue-200">
                                <i class="fas fa-tachometer-alt mr-1"></i>Gösterge Paneli
                            </a>
                            <a href="<?= BASE_URL ?>account" class="hover:text-blue-200">
                                <i class="fas fa-wallet mr-1"></i>Hesaplarım
                            </a>
                            <a href="<?= BASE_URL ?>transfer" class="hover:text-blue-200">
                                <i class="fas fa-exchange-alt mr-1"></i>Para Transferi
                            </a>
                            <a href="<?= BASE_URL ?>settings" class="hover:text-blue-200">
                                <i class="fas fa-cog mr-1"></i>Ayarlar
                            </a>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="hidden md:inline"><?= $_SESSION['user_name'] ?></span>
                            <a href="<?= BASE_URL ?>logout" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md">
                                <i class="fas fa-sign-out-alt mr-1"></i>Çıkış
                            </a>
                        </div>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>login" class="hover:text-blue-200">
                            <i class="fas fa-sign-in-alt mr-1"></i>Giriş
                        </a>
                        <a href="<?= BASE_URL ?>register" class="bg-white text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md">
                            <i class="fas fa-user-plus mr-1"></i>Kayıt Ol
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu (Sadece giriş yapıldığında görünür) -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="md:hidden bg-blue-500 text-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-2">
                <a href="<?= BASE_URL ?>dashboard" class="text-center flex-1 py-2 hover:bg-blue-600">
                    <i class="fas fa-tachometer-alt block mx-auto mb-1"></i>
                    <span class="text-xs">Gösterge</span>
                </a>
                <a href="<?= BASE_URL ?>account" class="text-center flex-1 py-2 hover:bg-blue-600">
                    <i class="fas fa-wallet block mx-auto mb-1"></i>
                    <span class="text-xs">Hesaplar</span>
                </a>
                <a href="<?= BASE_URL ?>transfer" class="text-center flex-1 py-2 hover:bg-blue-600">
                    <i class="fas fa-exchange-alt block mx-auto mb-1"></i>
                    <span class="text-xs">Transfer</span>
                </a>
                <a href="<?= BASE_URL ?>settings" class="text-center flex-1 py-2 hover:bg-blue-600">
                    <i class="fas fa-cog block mx-auto mb-1"></i>
                    <span class="text-xs">Ayarlar</span>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6">
        <!-- Bildirimler -->
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative" role="alert">
                <span class="block sm:inline"><?= $_SESSION['success_message'] ?></span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times" onclick="this.parentElement.parentElement.style.display='none'"></i>
                </span>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative" role="alert">
                <span class="block sm:inline"><?= $_SESSION['error_message'] ?></span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <i class="fas fa-times" onclick="this.parentElement.parentElement.style.display='none'"></i>
                </span>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Sayfa İçeriği -->
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-auto">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p>&copy; <?= date('Y') ?> BankApp. Tüm hakları saklıdır.</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-blue-400"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-blue-400"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-blue-400"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Bildirim kapatma
        document.addEventListener('DOMContentLoaded', function() {
            // 5 saniye sonra uyarıları otomatik kapat
            setTimeout(function() {
                var alerts = document.querySelectorAll('[role="alert"]');
                alerts.forEach(function(alert) {
                    alert.style.display = 'none';
                });
            }, 5000);
        });
    </script>
</body>
</html> 