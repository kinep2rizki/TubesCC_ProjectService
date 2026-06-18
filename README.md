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

---

## ⚙️ Konfigurasi Environment (.env)

Setelah melakukan `cp .env.example .env`, pastikan Anda menyesuaikan beberapa konfigurasi penting berikut agar Project Service dapat terhubung dengan Database dan Service lainnya (tanpa kredensial asli):

```dotenv
# Sesuaikan URL Aplikasi (Port 8002)
APP_URL=http://localhost:8002

# Konfigurasi Database PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=peta
DB_USERNAME=postgres
DB_PASSWORD=password_database_anda

# Konfigurasi Integrasi Microservices
# JWT_SECRET HARUS SAMA PERSIS dengan yang ada di Auth Service (Port 8001)
JWT_SECRET=rahasia_jwt_anda_disini
AUTH_SERVICE_URL=http://127.0.0.1:8001

# Konfigurasi Laravel Reverb (Untuk Broadcasting/WebSocket)
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=id_reverb_anda
REVERB_APP_KEY=key_reverb_anda
REVERB_APP_SECRET=secret_reverb_anda
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http
```

> **Catatan:** Variabel lain di file `.env` dapat dibiarkan *default* kecuali ada kebutuhan khusus seperti layanan Email (SMTP) atau Redis.

---

## 🏗️ Arsitektur Microservices

Aplikasi PETA menggunakan arsitektur Microservices dengan pembagian sebagai berikut:
1. **Frontend (Port 8000)**: Dibangun dengan kerangka (seperti Laravel Blade atau SPA React/Vue). Menangani antarmuka pengguna dan bertugas untuk melakukan *"Data Stitching"* dengan mengonsumsi API dari berbagai service.
2. **Auth Service (Port 8001)**: Menangani Autentikasi (Login, Register), Otorisasi, JWT Token generation, dan Manajemen Profil User & Role.
3. **Project Service (Port 8002 - Repositori ini)**: Bertanggung jawab pada *Business Logic* pengelolaan entitas inti (Komunitas, Event, Partisipan, Absensi, Sertifikat). Tidak menyimpan data credential pengguna, melainkan hanya merujuk ke `user_id`.

**Alur Komunikasi:**
- Frontend mengirimkan HTTP Request (misal AJAX) ke API dengan menyertakan header `Authorization: Bearer <jwt_token>`.
- Project Service memvalidasi token JWT (baik secara mandiri menggunakan `JWT_SECRET` yang sama, atau introspeksi ke Auth Service).
- Jika response membutuhkan data pengguna (seperti nama pembuat event), Frontend bertugas memanggil API dari Auth Service dan Project Service, lalu menggabungkannya sebelum ditampilkan.

---

## 📡 Daftar Endpoint API

Berikut adalah daftar endpoint API yang tersedia pada Project Service. *(Pastikan semua Request API dikirimkan dengan header `Accept: application/json`)*.

### 📊 Analytics
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| `GET`  | `/api/analytics/{communityId}/advanced` | Mendapatkan data analitik lanjutan komunitas. |
| `GET`  | `/api/analytics/{communityId}/dashboard` | Mendapatkan ringkasan dashboard analitik. |
| `POST` | `/api/analytics/{communityId}/export` | Mengekspor laporan data analitik. |

### 📜 Certificates
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| `POST` | `/api/certificates/generate` | Men-generate sertifikat baru untuk partisipan. |
| `GET`  | `/api/certificates/templates` | Mendapatkan daftar template sertifikat yang tersedia. |
| `GET`  | `/api/certificates/{id}/download` | Mengunduh file sertifikat (PDF/Image). |

### 👥 Communities
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| `GET`  | `/api/communities` | Mengambil daftar komunitas. |
| `POST` | `/api/communities` | Membuat komunitas baru. |
| `GET`  | `/api/communities/all` | Mengambil semua list komunitas (tanpa paginasi). |
| `GET`  | `/api/communities/my-memberships` | Melihat komunitas dimana user tersebut menjadi member. |
| `PUT`  | `/api/communities/{communityId}/users/{userId}/role` | Mengubah role user dalam suatu komunitas. |
| `GET`  | `/api/communities/{id}/feed` | Melihat daftar feed/postingan komunitas. |
| `POST` | `/api/communities/{id}/feed` | Membuat post feed komunitas baru. |
| `GET`  | `/api/communities/{id}/members` | Melihat daftar anggota/member komunitas. |
| `POST` | `/api/communities/{id}/roles` | Memperbarui role / jabatan di komunitas. |

### 📅 Events
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| `GET`  | `/api/events` | Mendapatkan daftar event. |
| `POST` | `/api/events` | Membuat event baru. |
| `GET`  | `/api/events/{event}` | Melihat detail informasi suatu event. |
| `PUT/PATCH` | `/api/events/{event}` | Mengubah / update detail event. |
| `DELETE`| `/api/events/{event}` | Menghapus event. |

### ✅ Attendance & Participants
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| `GET`  | `/api/events/{id}/attendance` | Melihat data absensi suatu event. |
| `POST` | `/api/events/{id}/attendance/check-in` | Melakukan check-in atau absensi event. |
| `GET`  | `/api/events/{id}/participants` | Melihat daftar partisipan yang ikut serta. |
| `POST` | `/api/events/{id}/participants` | Mendaftarkan diri ke sebuah event. |
| `GET`  | `/api/events/{id}/participants/export` | Ekspor data list partisipan event. |

### 👤 Users
| Method | Endpoint | Keterangan |
|--------|----------|------------|
| `POST` | `/api/users/memberships` | Mengambil data keanggotaan komunitas berdasarkan input ID array. |

**Struktur Response Default (JSON):**
Sebagian besar endpoint ini akan memberikan response dengan format standar JSON berikut:
```json
{
  "success": true,
  "message": "Operasi berhasil",
  "data": { ... }
}
```

---

## 🗂️ Postman Collection / API Docs

Untuk mempermudah pengujian API, disarankan menggunakan aplikasi **Postman** untuk mensimulasikan *request* ke Project Service. 

### Cara Penggunaan Postman:
1. Buka aplikasi **Postman**.
2. Buat **Environment** baru (misalnya "PETA Local") dan tambahkan variabel berikut:
   - `base_url`: `http://127.0.0.1:8002`
   - `jwt_token`: `(kosongkan sementara, diisi dengan token saat Anda login melalui Auth Service 8001)`
3. Pada setiap Request yang membutuhkan Autentikasi, di tab **Authorization**, pilih tipe **Bearer Token**, lalu masukkan `{{jwt_token}}`.
4. Anda dapat membuat Collection dan menyimpan rute-rute di atas. URL yang dipanggil akan menjadi `{{base_url}}/api/events` dll.

> **💡 Tips:**
> Jika Anda memerlukan spesifikasi API Docs (Swagger/OpenAPI), Anda bisa meng-generate otomatis dengan library `darkaonline/l5-swagger` dengan menjalankan perintah terkait (opsional jika dibutuhkan dokumentasi via browser yang komprehensif).
