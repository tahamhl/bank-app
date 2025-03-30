<?php
$title = "BankApp - Sayfa Bulunamadı";
$content = ob_get_clean();
include('layout.php');
?>

<?php ob_start(); ?>

<div class="flex flex-col items-center justify-center py-16">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-blue-600">404</h1>
        <h2 class="text-3xl font-bold text-gray-800 my-6">Aradığınız Sayfa Bulunamadı</h2>
        <p class="text-gray-600 mb-8">Üzgünüz, aradığınız sayfa mevcut değil veya kaldırılmış olabilir.</p>
        <div class="flex justify-center space-x-4">
            <a href="<?= BASE_URL ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                <i class="fas fa-home mr-2"></i>Ana Sayfaya Dön
            </a>
            <a href="javascript:history.back()" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>Geri Dön
            </a>
        </div>
    </div>
    
    <div class="mt-12">
        <img src="https://source.unsplash.com/random/600x400/?lost,404" alt="404 Illustration" class="rounded-lg shadow-lg max-w-md w-full">
    </div>
</div>

<?php $content = ob_get_clean(); ?> 