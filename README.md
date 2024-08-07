# Aplikasi Payroll Laravel 7
# Test dari PT Four Best Synergy 

Aplikasi payroll ini dibangun menggunakan Laravel 7. Aplikasi ini dirancang untuk mengelola data absensi, gaji pegawai, dan perhitungan gaji termasuk denda keterlambatan dan potongan ketidakhadiran.


## Fitur Utama

- **Autentikasi Pengguna:** Login dan logout menggunakan.
- **Manajemen Pegawai:** Menambahkan dan mengelola data pegawai.
- **Absensi Pegawai:** Mencatat jam masuk dan jam pulang pegawai.
- **Penggajian:** Menghitung gaji bersih pegawai dengan potongan keterlambatan dan ketidakhadiran.
- **Admin Dashboard:** Halaman admin untuk melihat dan mengelola data absensi serta gaji pegawai.

## Prerequisites

Sebelum memulai, pastikan Anda telah menginstal:

- PHP 7.2 atau versi yang lebih tinggi
- Composer
- Node.js dan npm
- MySQL atau database lainnya yang didukung

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan aplikasi:

### 1. Clone Repository

Clone repository ini ke mesin lokal Anda:

```bash
git clone https://github.com/begusibrahim000/laravel-test.git
```

Gantilah `username` dan `repository-name` dengan username GitHub Anda dan nama repository yang sesuai.

### 2. Masuk ke Direktori Proyek

```bash
cd repository-name
```

### 3. Instal Dependensi

Instal dependensi PHP dan JavaScript:

```bash
composer install
npm install
```

### 4. Salin File Konfigurasi

Salin file konfigurasi `.env` dari `.env.example`:

```bash
cp .env.example .env
```

### 5. Konfigurasi Database

Buka file `.env` dan atur konfigurasi database Anda:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=nama_pengguna
DB_PASSWORD=katalaluan
```

Gantilah `nama_database`, `nama_pengguna`, dan `katalaluan` dengan informasi database Anda.

### 6. Generate Key Aplikasi

Generate key aplikasi Laravel:

```bash
php artisan key:generate
```

### 7. Jalankan Migrasi dan Seeder

Jalankan migrasi untuk membuat tabel di database, dan seeding data awal:

```bash
php artisan migrate --seed
```

Jika Anda ingin mengulang migrasi dan seeding, gunakan perintah berikut:

```bash
php artisan migrate:refresh --seed
```

### 8. Jalankan Server Pengembangan

Buka tiga terminal dan jalankan perintah berikut di masing-masing terminal:

**Terminal 1: Jalankan server Laravel**

```bash
php artisan serve
```

**Terminal 2: Jalankan npm untuk kompilasi dan watch**

```bash
npm run watch
```

**Terminal 3: Jalankan migrasi dan seeder untuk menyimpan database dan contoh data**

```bash
php artisan migrate --seed
```

atau jika mau mengulang migrate itu dengan 


```bash
php artisan migrate:refresh --seed
```

### 9. Akses Aplikasi

Aplikasi akan tersedia di `http://localhost:8000`atau `http://localhost:8000/login`

   akses,
   username : admin@gmail.com
   password : password

### Halaman

# Admin :

http://localhost:8000/

http://localhost:8000/admin

http://localhost:8000/pegawai

http://localhost:8000/hari-kerja

http://localhost:8000/gaji

http://localhost:8000/absensi

# pegawai :

http://localhost:8000/absensi
