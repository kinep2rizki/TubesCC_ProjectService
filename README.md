# Project Service API (Tubes CC)

Repositori ini adalah **Project Service API** yang berjalan murni sebagai *Backend (Port 8002)* pada arsitektur Microservices aplikasi PETA.
Sistem ini bertanggung jawab atas _Business Logic_ dan pengelolaan data utama:
- Komunitas (Community)
- Acara (Event)
- Absensi (Attendance)
- Partisipasi (Participant)
- Sertifikat (Certificate)

> [!CAUTION]
> **TIDAK ADA LAYER FRONTEND ATAU AUTENTIKASI DI SINI!**
> 1. Layer antarmuka pengguna (Frontend UI Blade) telah dipisahkan ke repositori `TubesCC`.
> 2. Layer autentikasi (Login, Register, Manajemen Role) telah dipisahkan ke repositori `TubesCC_BackendJWT` (berjalan di Port 8001).

---

## 🚀 Rencana Integrasi & Penyesuaian

Karena service ini telah dipisahkan, Anda **tidak bisa** langsung menjalankan aplikasi seperti monolitik lama. Berikut adalah hal-hal yang **harus diimplementasi** untuk menyambungkan ekosistem ini:

### 1. Implementasi JWT Validation Middleware
Service ini dilindungi, tetapi fungsi validasi token `User` aslinya sudah terhapus.
Untuk menerima HTTP Request dari Frontend yang mengandung Header `Authorization: Bearer <token>`, Anda harus:
- **Opsi A (Paling Mudah)**: Install `tymon/jwt-auth` di service ini, lalu samakan `JWT_SECRET` di `.env` dengan milik `TubesCC_BackendJWT`. Buat middleware sederhana yang mengecek validitas token, lalu ekstrak nilai `sub` (User ID) dari payload JWT.
- **Opsi B (Introspeksi API)**: Buat middleware yang melakukan request `Http::get('http://127.0.0.1:8001/api/auth/me')` ke Auth Service dengan meneruskan token tersebut. Jika Auth Service mengembalikan sukses (200 OK), maka request diteruskan.

### 2. Restrukturisasi Endpoint Menjadi Murni API
Semua Controller di repositori ini (seperti `CommunityController` atau `EventController`) yang sebelumnya mereturn `view('pages.community', ...)` **HARUS** diubah untuk mereturn response JSON murni:
```php
return response()->json([
    'success' => true,
    'data' => $communities
]);
```
*(Saat ini kode Controller belum diubah seluruhnya menjadi API JSON, Anda harus mengubahnya secara bertahap!)*

### 3. Penyesuaian Relasi & Data Stitching (Cross-Service)
Karena tidak ada Model `User` di service ini, maka tabel/relasi Eloquent bawaan Laravel seperti `$event->creator` atau `$community->owner` akan gagal.
- Setiap entitas (Event, Komunitas) hanya menyimpan kolom `user_id` (Integer/UUID).
- Untuk menampilkan nama pembuat *Event* ke layar pengguna, **Frontend UI** lah yang harus menembak 2 API sekaligus:
  1. API Project Service (Port 8002) untuk mendapatkan Data Event (yang berisi `user_id: 5`).
  2. API Auth Service (Port 8001) untuk mendapatkan Data Profil User ID `5`.
  3. Menggabungkan data (Data Stitching) tersebut menggunakan Javascript di sisi Frontend.

### 4. Manajemen Peran (Roles)
Spatie Role Management (`admin`, `peserta`, dsb) ada di Auth Service. Jika Project Service harus mengecek apakah yang mengakses API ini adalah Admin, Project Service bisa melihat klaim *Role* yang bisa Anda sisipkan (Custom Claims) pada token JWT saat Auth Service menerbitkannya.

---

## 💻 Instalasi Lokal

```bash
# 1. Clone repository
git clone https://github.com/kinep2rizki/TubesCC_ProjectService.git
cd TubesCC_ProjectService

# 2. Install Dependency
composer install

# 3. Konfigurasi Environment
cp .env.example .env
php artisan key:generate

# Konfigurasi Database (Gunakan database khusus project, misal: peta)
# DB_DATABASE=peta

# 4. Jalankan Migrasi Data Bisnis
php artisan migrate

# 5. Jalankan API Backend ini di Port 8002
php artisan serve --port=8002
```
