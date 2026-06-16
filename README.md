# Tugas Besar CC - Project Service

Repositori ini adalah layanan utama (**Project Service**) dari aplikasi PETA yang telah dimigrasi menjadi arsitektur Microservices.
Layanan ini bertanggung jawab khusus untuk **Manajemen Komunitas, Event, Partisipasi, Attendance, dan Sertifikat**.

> [!WARNING]
> Layanan ini **tidak lagi memiliki fitur Autentikasi** secara internal. Fitur *Login*, *Register*, dan manajemen profil User telah dipindahkan sepenuhnya ke **Auth Service** (`TubesCC_BackendJWT`).

## 🚀 Walkthrough Instalasi & Konfigurasi

1. **Clone & Install Dependencies**
   ```bash
   git clone https://github.com/kinep2rizki/TubesCC_ProjectService.git
   cd TubesCC_ProjectService
   composer install
   ```

2. **Setup Environment**
   - Salin file `.env.example` ke `.env`.
   - Pastikan konfigurasi Database menunjuk ke `peta`.
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=peta
   DB_USERNAME=postgres
   DB_PASSWORD=kinep2004
   ```

3. **Jalankan Migrasi**
   Pastikan Anda sudah membuat database `peta` di PostgreSQL, lalu jalankan:
   ```bash
   php artisan migrate
   ```

4. **Jalankan Layanan (Port 8000)**
   Jalankan Project Service di Port 8000:
   ```bash
   php artisan serve
   ```

---

## 🤖 AI Context: Catatan Penting untuk Pengembangan Selanjutnya

Bagi AI atau developer yang membaca file ini di masa mendatang, berikut adalah konteks penuh terkait pemisahan sistem ini menjadi Microservices:

### 📌 Perubahan yang Telah Terjadi
1. **Pemisahan Auth**: Seluruh fitur login, register, token, dan autentikasi telah dipindahkan ke layanan `TubesCC_BackendJWT`.
2. **Penghapusan Model User**: Model `User.php` dan tabel migrasi `users`, `personal_access_tokens`, serta manajemen peran dari `spatie/laravel-permission` telah **dihapus sepenuhnya** dari layanan ini.

### 🚧 Hal-Hal yang Belum Diimplementasikan (TODO)
Karena hilangnya Model User, beberapa endpoint mungkin mengalami error (rusak) pada versi ini karena `$item->user` tidak lagi me-return *Relationship Object*. Tugas-tugas berikut ini harus diselesaikan di tahap pengembangan selanjutnya:

1. **Validasi Token (Middleware)**: 
   - Anda perlu membuat Custom Middleware (misal `CheckJwtToken`) yang menangkap header `Authorization: Bearer <token>` dari Frontend, dan melakukan verifikasi manual (baik via *Token Signature Verification* maupun memanggil introspeksi token ke Auth Service).
2. **Sistem Sinkronisasi/Stitching Data**:
   - Jika endpoint membutuhkan data spesifik milik pengguna (misal: Menampilkan daftar partisipan dengan namanya), maka *Project Service* hanya memiliki `user_id` saja.
   - Hal ini bisa diselesaikan dengan *Data Stitching* via Frontend (Frontend memanggil Auth Service untuk resolusi ID ke nama), atau
   - *Data Stitching* via Guzzle HTTP di dalam Controller Project Service (Project Service meminta data pengguna ke `http://127.0.0.1:8001/api/auth/users/batch`).
3. **Pengecekan Role & Permission**:
   - Saat ini *Spatie Role* ada di Auth Service. Untuk menentukan apakah pengguna (dengan ID `X`) adalah Admin di Komunitas `Y`, sistem harus mengandalkan mekanisme Cross-Service untuk memvalidasi Role.
4. **Event Broadcasting (Reverb)**:
   - Manajemen Realtime masih terpasang, namun *Private Channel* yang membutuhkan autentikasi pengguna (`Broadcast::routes()`) perlu disesuaikan agar bisa mengautentikasi klien berdasar JWT dari Auth Service.
