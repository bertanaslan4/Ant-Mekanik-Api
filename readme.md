# Ant Mekanik API

Ant Mekanik uygulamaları için Laravel ile yazılmış REST API. Kimlik doğrulama, boyler projeleri, yöntem (method) ve veri listeleri gibi uç noktalar sunar.

## Uç noktalar (özet)

Tüm API uç noktaları `/api` altındadır.

| Uç nokta | Açıklama |
|---|---|
| `POST /api/login` | Giriş |
| `POST /api/register`, `POST /api/userregister` | Kayıt |
| `POST /api/logout` | Çıkış |
| `POST /api/forgotpasword` | Şifre sıfırlama |
| `GET /api/methods`, `GET /api/method/{method}` | Yöntem listesi ve detayı |
| `GET /api/data/{type}`, `POST /api/data` | Veri listeleme ve kayıt |
| `POST /api/boiler`, `/api/boilerDetail`, `/api/boilerSave`, `/api/boilerupdate` | Boyler hesapları |
| `POST /api/boilerprojelistele`, `/api/boilerprojesil` | Boyler projelerini listeleme ve silme |
| `GET /api/istatistik` | İstatistikler |

Tam uç nokta listesi için `routes/api.php` dosyasına, şema için `openapi.yaml` dosyasına bakın.

## Kullanılan teknolojiler

- PHP / Laravel
- MySQL

## Kurulum

```bash
composer install
cp .env.example .env
php artisan key:generate
```

`.env` dosyasında MySQL bilgilerini girin, sonra:

```bash
php artisan migrate
php artisan serve
```

API `http://localhost:8000/api` adresinde çalışır.

## Güvenlik notu

Bu repoda `.env` dosyası takip ediliyor. Repodan kaldırılmalı ve içindeki şifreler yenilenmelidir.

## Lisans

Lisans dosyası bulunmamaktadır.
