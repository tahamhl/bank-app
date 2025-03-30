<?php
$title = "BankApp - Anasayfa";
ob_start();
?>

<div class="flex flex-col md:flex-row items-center justify-between mb-10">
    <div class="w-full md:w-1/2 mb-6 md:mb-0 md:pr-6">
        <h1 class="text-4xl font-bold text-blue-700 mb-4">Bankacılık İşlemlerinizi<br>Kolaylaştırın</h1>
        <p class="text-gray-600 mb-6 text-lg">BankApp ile bankacılık işlemlerinizi hızlı, güvenli ve kolayca yapabilirsiniz. Para transferleri, hesap yönetimi ve daha fazlası tek bir platformda.</p>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
            <a href="<?= BASE_URL ?>register" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg text-center">
                <i class="fas fa-user-plus mr-2"></i>Hemen Başlayın
            </a>
            <a href="#features" class="bg-white hover:bg-gray-100 text-blue-600 font-bold py-3 px-6 rounded-lg border border-blue-600 text-center">
                <i class="fas fa-info-circle mr-2"></i>Daha Fazla Bilgi
            </a>
        </div>
    </div>
    <div class="w-full md:w-1/2">
        <img src="<?= BASE_URL ?>assets/gorsel.png" alt="Modern Bankacılık" class="rounded-lg shadow-lg w-full h-auto md:max-w-sm mx-auto">
    </div>
</div>

<div id="features" class="my-16">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">Neden BankApp?</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="text-blue-600 text-4xl mb-4">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Güvenli Bankacılık</h3>
            <p class="text-gray-600">En üst düzey güvenlik önlemleriyle hesaplarınız her zaman güvende. Gelişmiş şifreleme teknolojileri kullanıyoruz.</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="text-blue-600 text-4xl mb-4">
                <i class="fas fa-bolt"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Hızlı İşlemler</h3>
            <p class="text-gray-600">Saniyeler içinde para transferleri yapın. Zaman kaybı olmadan bankacılık işlemlerinizi tamamlayın.</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="text-blue-600 text-4xl mb-4">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Finansal Takip</h3>
            <p class="text-gray-600">Gelir ve giderlerinizi kolayca takip edin. Detaylı raporlar ile finansal durumunuzu kontrol altında tutun.</p>
        </div>
    </div>
</div>

<div class="my-16 bg-blue-50 p-8 rounded-lg">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">Hizmetlerimiz</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
            <div class="text-blue-600 text-3xl mb-4">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Para Transferleri</h3>
            <p class="text-gray-600 text-sm">Hızlı ve güvenli para transferleri yapın. İster kendi hesaplarınız arasında, ister başka hesaplara.</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
            <div class="text-blue-600 text-3xl mb-4">
                <i class="fas fa-credit-card"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Hesap Yönetimi</h3>
            <p class="text-gray-600 text-sm">Farklı hesap türleri ile ihtiyaçlarınıza uygun bankacılık deneyimi yaşayın.</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
            <div class="text-blue-600 text-3xl mb-4">
                <i class="fas fa-history"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">İşlem Geçmişi</h3>
            <p class="text-gray-600 text-sm">Tüm işlemlerinizi detaylı olarak görüntüleyin ve geçmiş işlemlerinizi takip edin.</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow text-center">
            <div class="text-blue-600 text-3xl mb-4">
                <i class="fas fa-lock"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Güvenlik Ayarları</h3>
            <p class="text-gray-600 text-sm">Hesap güvenliğinizi artırmak için gelişmiş güvenlik ayarlarını kullanın.</p>
        </div>
    </div>
</div>

<div class="my-16">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-10">Müşteri Yorumları</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center mb-4">
                <div class="text-yellow-400">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <span class="ml-2 text-gray-600">5.0</span>
            </div>
            <p class="text-gray-600 mb-4">"BankApp ile bankacılık işlemlerimi çok daha hızlı ve kolay yapabiliyorum. Artık şubeye gitmeme gerek kalmadı!"</p>
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">A</div>
                <div class="ml-3">
                    <h4 class="font-semibold">Ahmet Yılmaz</h4>
                    <p class="text-sm text-gray-500">İş İnsanı</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center mb-4">
                <div class="text-yellow-400">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <span class="ml-2 text-gray-600">4.5</span>
            </div>
            <p class="text-gray-600 mb-4">"Kullanımı çok kolay ve anlaşılır. Para transferleri çok hızlı gerçekleşiyor. Kesinlikle tavsiye ederim."</p>
            <div class="flex items-center">
                <div class="w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold">Z</div>
                <div class="ml-3">
                    <h4 class="font-semibold">Zeynep Kaya</h4>
                    <p class="text-sm text-gray-500">Öğretmen</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center mb-4">
                <div class="text-yellow-400">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <span class="ml-2 text-gray-600">5.0</span>
            </div>
            <p class="text-gray-600 mb-4">"Hesaplarımı takip etmek artık çok daha kolay. Arayüzü çok modern ve kullanıcı dostu."</p>
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">M</div>
                <div class="ml-3">
                    <h4 class="font-semibold">Mehmet Demir</h4>
                    <p class="text-sm text-gray-500">Mühendis</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="my-16 text-center">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Hemen Başlayın</h2>
    <p class="text-gray-600 mb-8 max-w-xl mx-auto">Modern bankacılık deneyimini keşfedin. Hemen kaydolun ve BankApp'in sunduğu tüm avantajlardan yararlanın.</p>
    <a href="<?= BASE_URL ?>register" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg inline-block">
        <i class="fas fa-user-plus mr-2"></i>Ücretsiz Kayıt Olun
    </a>
</div>

<?php
$content = ob_get_clean();
include('layout.php');
?> 