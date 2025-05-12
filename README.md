# Reusemart P3L

Aplikasi ini merupakan project fullstack yang dibangun menggunakan **Laravel** sebagai backend (REST API) dan **React.js** sebagai frontend. Arsitektur ini memisahkan antara logic server dan tampilan antarmuka (UI) agar mudah dikembangkan dan scalable.

---

## 🧰 Tech Stack

- **Backend**: Laravel 11.44.2
- **Frontend**: React.js (Vite)
- **Database**: PhpMyAdmin
- **API Format**: JSON (RESTful)

---

## 📦 Installation & Setup

### 📂 Backend (Laravel)

```bash
# 1. Masuk ke direktori backend
cd backend

# 2. Install dependency
composer install

# 3. Salin file .env dan generate app key
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi koneksi database di file .env
cp .env.example .env


# 5. Jalankan migrasi database
php artisan migrate:fresh --seed

# 6. Jalankan server Laravel
php artisan serve
```

> Secara default, API Laravel berjalan di http://localhost:8000

---

### 💻 Frontend (React.js)

```bash
# 1. Masuk ke direktori frontend
cd frontend

# 2. Install dependency
npm install

# 3. Buat Base_Url didalam APi/index.jsx dengan Url_Laravel:
BASE_URL = 'http://127.0.0.1:8000'

# 4. Jalankan server React
npm run dev
```

>>>>>>> 80c0c680bec430e7476f0b831287d8591e57f0dc
