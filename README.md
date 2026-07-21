# 🌍 Global Supply Chain Risk Intelligence Platform

Platform monitoring risiko rantai pasok global berbasis web dengan integrasi multi-API, sentiment analysis, dan visualisasi data interaktif menggunakan Laravel.

## 📋 Fitur Utama (10 Fitur)

1. **📊 Dashboard Interaktif** - Overview statistik, chart distribusi negara & risiko, top risk countries
2. **🌍 Data Negara Lengkap** - 254 negara dengan detail ekonomi, cuaca, risiko & peta interaktif
3. **⚠️ Analisis Risiko Supply Chain** - Multi-factor risk scoring dengan filter dinamis
4. **💰 Data Ekonomi** - GDP, exports, imports, trade balance dari World Bank API
5. **🌦️ Monitoring Cuaca** - Real-time weather data (suhu, curah hujan, kelembaban, angin)
6. **💱 Nilai Tukar Mata Uang** - 247 currencies dengan 4 interactive charts
7. **📰 Berita Global & Sentiment Analysis** - Artikel berita dengan lexicon-based sentiment (-10 to +10)
8. **⚓ Peta Pelabuhan Global** - 126 ports dengan Leaflet.js interactive map
9. **📈 Visualisasi & Perbandingan Data** - Multi-country comparison dengan Chart.js
10. **⭐ Watchlist / Favorit** - Manajemen negara favorit user

### ✨ Bonus Features:
- 🔐 **Admin Portal** - Sync management, lexicon editor, user & article management
- 🔌 **REST API v1** - API endpoints untuk integrasi eksternal
- 🔄 **Background Jobs** - Queue system untuk data sync
- 📱 **Responsive Design** - Mobile-friendly Bootstrap 5 + Tailwind CSS

---

## 🚀 Quick Start

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Setup Database
```bash
php artisan migrate
php artisan db:seed
```

### 4. Build Assets
```bash
npm run build
```

### 5. Start Development Server
```bash
php artisan serve
```

### 6. Start Auto Sync (Optional)
**Windows:**
```bash
start-scheduler.bat
```
**Manual:**
```bash
# Terminal 1: Queue Worker
php artisan queue:work

# Terminal 2: Scheduler
php artisan schedule:work
```

---

## 🔄 Auto Sync Setup

Sistem ini memiliki fitur **auto sync setiap 8 jam** untuk mengambil data terbaru dari API eksternal.

### Windows (Development)
Jalankan file batch yang sudah disediakan:
```bash
# Start services
start-scheduler.bat

# Stop services
stop-scheduler.bat
```

### Manual Sync
Untuk sync manual semua data:
```bash
php artisan sync:all
```
Atau gunakan UI di menu **Admin > Sync**

---

## 🌐 External API Integration

Aplikasi ini mengintegrasikan **6 API eksternal**:

| API | Purpose | Auth |
|-----|---------|------|
| **REST Countries API** | Data negara (nama, bendera, populasi, koordinat) | API Key |
| **World Bank API** | Data ekonomi (GDP, exports, imports, trade balance) | Public |
| **ExchangeRate API** | Nilai tukar mata uang real-time vs USD | API Key |
| **Open-Meteo API** | Data cuaca & iklim (suhu, hujan, kelembaban, angin) | Public |
| **GNews API** | Berita global untuk sentiment analysis | API Key |
| **World Port Index** | Lokasi pelabuhan global dengan koordinat GPS | Static JSON |

**API Keys sudah tersedia di `.env` file** - tinggal pakai!

---

## 📊 Tech Stack

### Backend
- **PHP 8.3** - Modern PHP with latest features
- **Laravel 11+** - Framework PHP
- **MySQL** - Relational database (22 tables)
- **Laravel Queue** - Background job processing
- **Repository Pattern** - Clean data layer separation

### Frontend
- **Bootstrap 5.3** - Main UI framework
- **Tailwind CSS 3.1** - Utility-first CSS
- **Chart.js 4.x** - Interactive charts & graphs
- **Leaflet.js** - Interactive maps (ports & countries)
- **Alpine.js 3.4** - Reactive JavaScript framework
- **Bootstrap Icons** - Icon library

### Architecture
- **MVC Pattern** - Clean separation of concerns
- **Service Layer** - Business logic isolation
- **Repository Pattern** - Data access abstraction
- **Job Queue System** - Async background processing
- **RESTful API** - API v1 with versioning

### Development Tools
- **Vite** - Fast build tool
- **Composer** - PHP dependency manager
- **NPM** - JavaScript package manager

---

## 📁 Struktur Project

```
├── app/
│   ├── Http/
│   │   ├── Controllers/           # 22 controllers (Dashboard, Country, Risk, dll)
│   │   ├── Middleware/            # IsAdmin middleware
│   ├── Models/                    # 15 Eloquent models
│   ├── Jobs/                      # 7 background sync jobs
│   ├── Repositories/              # 6 repository classes
│   └── Services/
│       ├── API/                   # 6 API service integrations
│       ├── Mappers/               # Data mapping classes
│       └── Risk/                  # Risk calculation engine
├── database/
│   ├── migrations/                # 22 database tables
│   └── seeders/                   # Initial data seeders
├── resources/
│   ├── views/                     # 25+ Blade templates
│   │   ├── admin/                 # Admin portal views
│   │   ├── countries/             # Country pages
│   │   ├── dashboard/             # Dashboard
│   │   └── layouts/               # Layout templates
│   └── js/                        # JavaScript modules
├── routes/
│   ├── web.php                    # Web routes (user + admin)
│   ├── api.php                    # REST API routes (v1)
│   └── console.php                # Artisan commands
├── storage/
│   └── app/data/                  # Static JSON data (ports)
└── public/                        # Public assets & entry point
```

---

## 🗄️ Database Tables
- `countries` - Data negara
- `currencies` - Mata uang
- `economy_data` - Data ekonomi
- `exchange_rates` - Nilai tukar
- `weather_logs` - Log cuaca
- `news_articles` - Artikel berita
- `risk_scores` - Skor risiko
- `ports` - Data pelabuhan
- `sync_logs` - Log sinkronisasi

---

## 🎯 Jadwal Auto Sync
Sinkronisasi otomatis berjalan setiap **8 jam** pada:
- 00:00 (tengah malam)
- 08:00 (pagi)
- 16:00 (sore)

**Urutan sync:**
1. Countries
2. Weather
3. Exchange Rates
4. Economy Data
5. News
6. Risk Analysis

---

## 🌐 Deployment (InfinityFree)

Platform ini telah disiapkan untuk deployment ke **InfinityFree** (100% gratis).

### Quick Deployment Steps:
1. **Export Database**: `mysqldump -u root global_supply_chain > database.sql`
2. **Registrasi InfinityFree**: Buat hosting account.
3. **Upload Files**: ZIP project (tanpa `vendor/`, `node_modules/`, `.env`), upload via cPanel File Manager. Struktur: `/laravel-app/` (app) dan `/htdocs/` (public).
4. **Setup Database**: Buat database MySQL di cPanel, import `database.sql`.
5. **Konfigurasi Environment**: Buat `.env`, sesuaikan credentials, set `APP_ENV=production` & `APP_DEBUG=false`.
6. **Testing**: Akses domain, test semua fitur & API.

*Catatan: InfinityFree tidak support Laravel scheduler otomatis, sehingga sync data harus dilakukan manual via Admin Panel.*

---

## 📚 Dokumentasi Tambahan

- 📖 `DEPLOYMENT_GUIDE.md` - Panduan deployment lengkap InfinityFree
- 📖 `REST_API_DOCUMENTATION.md` - API endpoints & usage
- 📖 `WEATHER_MAP_FEATURE.md` - Dokumentasi fitur peta cuaca
- 📖 `EXCHANGE_RATE_CHARTS_IMPROVEMENT.md` - Fitur chart nilai tukar

## 📝 License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
