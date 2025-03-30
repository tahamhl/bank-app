# BankApp - PHP Bankacılık Uygulaması

Modern ve güvenli bir bankacılık deneyimi sunan PHP tabanlı web uygulaması.

## Canlı Demo

Uygulamaya erişmek için: [tahamehel.tr/bank](http://tahamehelçtr/bank)

## Ekran Görüntüleri

### Ana Sayfa
![chrome_li0GofTeFr](https://github.com/user-attachments/assets/3ee71a58-325f-48d1-886f-745dccaf1038)


### Dashboard
![chrome_AxeRbqao4i](https://github.com/user-attachments/assets/a82a4366-ea6e-4625-8063-5bf76f4118ba)


## Özellikler

- Kullanıcı kaydı ve kimlik doğrulama
- Hesap oluşturma ve yönetme (TL, USD, EUR gibi çeşitli para birimleri)
- Para yatırma ve çekme işlemleri
- Hesaplar arası para transferi
- İşlem geçmişi görüntüleme
- Profil yönetimi ve güvenlik ayarları

## Teknolojiler

- PHP (PDO ile veritabanı erişimi)
- MySQL veritabanı
- HTML, Tailwind CSS
- JavaScript

## Kurulum

1. Repoyu klonlayın:
```bash
git clone https://github.com/kullanici-adiniz/bankapp.git
cd bankapp
```

2. `.env` dosyasını oluşturun:
```bash
cp .env.example .env
```

3. `.env` dosyasını düzenleyerek veritabanı bilgilerinizi girin:
```
DB_HOST=localhost
DB_NAME=bankapp
DB_USER=kullanici_adiniz
DB_PASS=şifreniz
```

4. Veritabanı tablolarını oluşturun:
```bash
mysql -u kullanici_adiniz -p < app/config/schema.sql
```

5. Web sunucunuza yükleyin ve tarayıcıdan erişin.

## Dosya Yapısı

```
bankapp/
├── app/                      # Uygulama kodları
│   ├── config/               # Yapılandırma dosyaları
│   │   ├── database.php      # Veritabanı bağlantısı
│   │   └── schema.sql        # Veritabanı şeması
│   ├── controllers/          # Controller'lar
│   │   ├── AccountController.php
│   │   ├── TransactionController.php
│   │   └── UserController.php
│   ├── models/               # Veri modelleri
│   │   ├── Account.php
│   │   ├── Transaction.php
│   │   └── User.php
│   └── views/                # Görünüm dosyaları
│       ├── account.php
│       ├── dashboard.php
│       ├── home.php
│       ├── layout.php
│       ├── login.php
│       ├── register.php
│       ├── settings.php
│       └── transfer.php
├── assets/                   # Resimler, CSS, JS
│   ├── css/
│   └── js/
├── public/                   # Genel erişime açık dosyalar
│   ├── css/
│   └── js/
├── .env                      # Çevre değişkenleri (gizli)
├── .env.example              # Örnek çevre değişkenleri
├── .gitignore                # Git tarafından görmezden gelinen dosyalar
├── .htaccess                 # Apache konfigürasyonu
├── index.php                 # Ana giriş noktası
└── README.md                 # Proje açıklaması
```

## Güvenlik

Bu uygulama aşağıdaki güvenlik önlemlerini içerir:

- Parolalar için güvenli hash (bcrypt)
- PDO ile prepared statements kullanımı
- CSRF koruması
- Girdi doğrulama ve temizleme
- XSS saldırılarına karşı koruma


## İletişim

Sorularınız ve önerileriniz için [tahamehel1@gmail.com] adresinden iletişime geçebilirsiniz. 
