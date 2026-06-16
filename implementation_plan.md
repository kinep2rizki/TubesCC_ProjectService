# Refactoring Project Service to Pure API & Data Stitching

Sistem ini sekarang harus berjalan sebagai *Backend API* murni (tanpa Blade views) dan memanfaatkan *Auth Service* secara remote untuk data penggunanya.

## User Review Required

> [!IMPORTANT]
> **API Migration**: Semua Controller Web lama (`app/Http/Controllers/EventController.php`, dsb.) akan dihapus beserta *routes* di `routes/web.php`. Kita akan mengandalkan `app/Http/Controllers/Api/` dan `routes/api.php` yang mengembalikan respon murni JSON. Apakah ada *view/route* Web tertentu yang masih ingin dipertahankan atau boleh dihapus total?
user : ya hapus total saja dari web, yang penting bisa diakses nanti di FE saya
>
> **Data Stitching Strategy**: Saat mengambil banyak data (contoh: list partisipan dari sebuah Event), kita akan mengambil array of `user_id` lalu menembak `/api/auth/users/batch` ke Auth Service dan menempelkannya (*stitch*) sebelum mengirim JSON ke *Frontend*. Pendekatan ini membutuhkan request HTTP antar microservice. Apakah disetujui?
user : ya disetujui, tapi implementasikanlah yang menurutmu paling efisien dan efektif.

## Proposed Changes

---

### Models Cleanup

Menghapus metode relasional ke tabel `users` yang sudah tidak ada.

#### [MODIFY] [Community.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Models/Community.php)
- Menghapus `public function owner()` yang berelasi ke `User::class`.

#### [MODIFY] [CommunityMember.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Models/CommunityMember.php)
- Menghapus `public function user()` yang berelasi ke `User::class`.

#### [MODIFY] [EventParticipant.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Models/EventParticipant.php)
- Menghapus `public function user()` yang berelasi ke `User::class`.

#### [MODIFY] [ActivityLog.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Models/ActivityLog.php)
- Menghapus `public function user()` yang berelasi ke `User::class`.

---

### Controller & Route Deletions

Menghapus controller lama berbasis Blade view dan mematikan rute web-nya.

#### [DELETE] Semua controller di `app/Http/Controllers` (selain Controller dasar dan folder `Api/`)
- `AnalyticsController.php`, `AttendanceController.php`, `CertificateController.php`, `CommunityController.php`, `EventController.php`, `ParticipantController.php`, `UserController.php`, `ProfileController.php`, `AuthController.php`.

#### [MODIFY] [web.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/routes/web.php)
- Menghapus semua definisi _routes_ yang memanggil controller lama, mungkin menyisakan `Route::get('/', function() { return ['status' => 'Project Service OK']; });`.

---

### Pure API Controllers (With Data Stitching)

Mengisi logika yang sebelumnya ada di Web Controllers, ke dalam API Controllers, namun dengan membuang pemanggilan `auth()->id()` menjadi `$request->auth_user_id` dan mengganti `with('user')` dengan `UserService::getUsersBatch()`.

#### [MODIFY] [Api/CommunityController.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Http/Controllers/Api/CommunityController.php)
- Mengembalikan JSON `Community` beserta stitching profil owner.
- Memperbaiki `auth('api')->id()` menjadi `$request->auth_user_id`.

#### [MODIFY] [Api/EventController.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Http/Controllers/Api/EventController.php)
- Migrasi logika hitung total data dari web controller ke JSON.
- `index`, `show`, `store`, `update`, `destroy` murni JSON.
- Data stitching untuk mengambil siapa saja partisipannya.

#### [MODIFY] [Api/ParticipantController.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Http/Controllers/Api/ParticipantController.php)
- Stitching `EventParticipant` dengan profil lengkap `users`.

#### [MODIFY] [Api/AttendanceController.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Http/Controllers/Api/AttendanceController.php)
- API endpoint JSON murni.

#### [MODIFY] [Api/AnalyticsController.php](file:///d:/Kuliah/CC/TubesCC_ProjectService/app/Http/Controllers/Api/AnalyticsController.php)
- Memindahkan data statistik (dashboard stats) menjadi output JSON `/analytics/dashboard`.

## Verification Plan

### Automated Tests
- Menjalankan `php artisan optimize:clear`

### Manual Verification
- Cek URL `http://127.0.0.1:8002/api/events/` via REST Client/Insomnia dan pastikan responnya adalah format JSON yang sudah berisi objek profil user dari *Auth Service*.
