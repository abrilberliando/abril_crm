# Laravel CRM Application

Aplikasi CRM sederhana menggunakan Laravel 11, Filament, dan PostgreSQL untuk manajemen customer dan project.

## Fitur Utama

- **Halaman Login** - Autentikasi pengguna
- **Manajemen Lead** - Kelola calon customer
- **Master Produk** - Kelola layanan/produk
- **Manajemen Project** - Proses lead dengan approval manager
- **Customer Berlangganan** - Kelola customer aktif dan layanannya

## Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM
- PostgreSQL >= 14
- Git

## Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd laravel-crm
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Konfigurasi Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=smart_app
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 5. Setup Database

```bash
# Buat database PostgreSQL
createdb smart_app
# Jalankan migrasi
php artisan migrate

# Jalankan seeder (opsional)
php artisan db:seed
```

### 6. Build Assets

```bash
# Compile assets untuk development
npm run dev

# Atau untuk production
npm run build
```

### 7. Buat Admin User

```bash
php artisan make:filament-user
```

## Menjalankan Aplikasi

### Development Server

```bash
# Jalankan Laravel server
php artisan serve

# Di terminal terpisah, jalankan Vite dev server
npm run dev
```

Aplikasi akan berjalan di `http://localhost:8000`

### Production

```bash
# Optimize aplikasi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build assets
npm run build
```

## Akses Filament Admin Panel

Setelah aplikasi berjalan, akses admin panel di:

```
http://localhost:8000/admin
```

Login menggunakan kredensial yang dibuat saat setup admin user.

## Struktur Menu Admin

1. **Dashboard** - Ringkasan data
2. **Leads** - Kelola calon customer
3. **Products** - Master produk/layanan
4. **Projects** - Manajemen project dengan approval
5. **Customers** - Customer berlangganan
6. **Users** - Manajemen pengguna (untuk admin)

## Commands Berguna

```bash
# Reset database
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate Filament resource
php artisan make:filament-resource ModelName

# Queue worker (jika menggunakan jobs)
php artisan queue:work
```

## Troubleshooting

### Database Connection Error
- Pastikan PostgreSQL service berjalan
- Periksa kredensial database di file `.env`
- Pastikan database sudah dibuat

### Permission Error
```bash
# Fix storage permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Asset Not Loading
```bash
# Clear dan rebuild assets
npm run build
php artisan view:clear
```

## Teknologi yang Digunakan

- **Laravel 11** - PHP Framework
- **Filament** - Admin Panel
- **PostgreSQL** - Database
- **Tailwind CSS** - Styling
- **Alpine.js** - Frontend interactivity

## Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Aplikasi ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail.

---

**Catatan:** Pastikan semua persyaratan sistem terpenuhi sebelum instalasi. Untuk production, gunakan web server seperti Nginx atau Apache dengan konfigurasi yang sesuai.
