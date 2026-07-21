# 🌍 Global Supply Chain Risk Intelligence Platform

Platform monitoring risiko rantai pasok global berbasis web dengan integrasi multi-API, sentiment analysis, dan visualisasi data interaktif menggunakan Laravel.

## 📋 Fitur Utama (11 Fitur)

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
11. **🚚 Logistics Simulator** - Simulasi impor/ekspor antar pelabuhan dengan kalkulasi jarak (Haversine), ETA kapal kargo, cuaca real-time, dan pemetaan rute interaktif

### ✨ Bonus Features:
- 🔐 **Admin Portal** - Sync management, lexicon editor, user & article management
- 🔌 **REST API v1** - API endpoints untuk integrasi eksternal
- 🔄 **Background Jobs** - Queue system untuk data sync
- 📱 **Modern & Responsive UI** - Antarmuka yang telah diperbarui secara menyeluruh (revamped UI) dengan Bootstrap 5 + Tailwind CSS yang elegan dan responsif di semua perangkat.

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

### 6. Menjalankan Queue Worker
Karena aplikasi ini menggunakan sistem Queue untuk proses sinkronisasi di background, jalankan perintah berikut di terminal terpisah:
```bash
php artisan queue:work
```

---

## 🔄 Sinkronisasi Data API

Seluruh proses sinkronisasi data dari berbagai API eksternal dilakukan **secara manual** melalui halaman Admin.

1. Login ke dalam aplikasi menggunakan akun Admin.
2. Navigasi ke menu **Admin > Sync**.
3. Anda dapat memilih untuk sinkronisasi data tertentu (Negara, Cuaca, Ekonomi, dll) atau menjalankan "Sync All".
4. Pastikan `php artisan queue:work` sedang berjalan di server agar proses sinkronisasi dapat dieksekusi di background.

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

## 🎯 Urutan Sinkronisasi Data
Saat melakukan sinkronisasi secara manual, disarankan untuk mengikuti urutan berikut agar relasi data dapat terhubung dengan baik:

1. Countries (Data Master)
2. Weather
3. Exchange Rates
4. Economy Data
5. News
6. Risk Analysis (Dilakukan paling akhir karena mengkalkulasi ulang risiko berdasarkan data lain)

---

## 🌐 Deployment (Local Expose via Ngrok)

Platform ini dapat diekspos ke publik dengan cepat dari environment lokal menggunakan **Ngrok** untuk keperluan demo atau testing.

### Quick Deployment Steps:
1. **Download & Install Ngrok**: Kunjungi [ngrok.com](https://ngrok.com) untuk mengunduh versi yang sesuai.
2. **Setup Autentikasi**: Jalankan `ngrok config add-authtoken <YOUR_AUTH_TOKEN>` (dapatkan token dari dashboard Ngrok).
3. **Jalankan Aplikasi Lokal**: Pastikan aplikasi berjalan (misalnya menggunakan `php artisan serve` di port 8000).
4. **Ekspos dengan Ngrok**: Buka terminal baru dan jalankan perintah:
   ```bash
   ngrok http 8000
   ```
5. **Akses URL Publik**: Ngrok akan menghasilkan sebuah URL publik (contoh: `https://abcd-123.ngrok-free.app`). Salin URL tersebut untuk demo.

*Catatan: Menggunakan Ngrok sangat cocok untuk presentasi dan demo instan tanpa perlu memindahkan file kode maupun database ke server hosting eksternal. Pastikan queue worker tetap berjalan secara lokal jika Anda akan mencontohkan proses sinkronisasi.*

---

## 📚 Dokumentasi Tambahan

- 📖 `DEPLOYMENT_GUIDE.md` - Panduan deployment lengkap InfinityFree
- 📖 `REST_API_DOCUMENTATION.md` - API endpoints & usage
- 📖 `WEATHER_MAP_FEATURE.md` - Dokumentasi fitur peta cuaca
- 📖 `EXCHANGE_RATE_CHARTS_IMPROVEMENT.md` - Fitur chart nilai tukar

## 📝 License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
