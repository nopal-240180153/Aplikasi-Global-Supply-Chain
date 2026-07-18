# Global Supply Chain Risk Intelligence Platform

Platform monitoring risiko rantai pasok global berbasis multi-API dan analitik data menggunakan Laravel.

## 📋 Fitur Utama

- 🌍 **Global Country Dashboard** - Monitoring data negara global
- 🌦️ **Weather Monitoring** - Pemantauan cuaca real-time
- 💱 **Exchange Rate Tracking** - Tracking nilai tukar mata uang
- 📊 **Economic Analysis** - Analisis data ekonomi (GDP, inflasi, ekspor/impor)
- 📰 **News Intelligence** - Agregasi berita terkait supply chain
- ⚠️ **Risk Scoring Engine** - Sistem scoring risiko otomatis
- 🗺️ **Interactive Map** - Visualisasi data dengan Leaflet.js
- 📈 **Data Visualization** - Chart dan grafik dengan Chart.js
- 🔄 **Auto Sync** - Sinkronisasi otomatis setiap 8 jam

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

### Manual Setup

Lihat dokumentasi lengkap di [SCHEDULER_SETUP.md](SCHEDULER_SETUP.md)

### Manual Sync

Untuk sync manual semua data:

```bash
php artisan sync:all
```

Atau gunakan UI di menu **Admin > Sync**

## 🌐 API yang Digunakan

Aplikasi ini mengintegrasikan beberapa API gratis:

1. **Open-Meteo API** - Data cuaca global (tanpa API key)
2. **World Bank API** - Data ekonomi (tanpa API key)
3. **REST Countries API** - Data negara (tanpa API key)
4. **ExchangeRate API** - Nilai tukar mata uang
5. **GNews API** - Berita global
6. **World Port Index** - Data pelabuhan

## 📊 Tech Stack

### Backend
- PHP 8.3
- Laravel 13
- MySQL

### Frontend
- Bootstrap 5
- Chart.js
- Leaflet.js
- AJAX & JavaScript ES6

### Tools
- Laravel Queue untuk background jobs
- Laravel Scheduler untuk auto sync
- Blade Templates

## 📁 Struktur Project

```
├── app/
│   ├── Http/Controllers/      # Controllers
│   ├── Models/                 # Eloquent Models
│   ├── Jobs/                   # Background Jobs
│   ├── Repositories/           # Data Layer
│   └── Services/               # Business Logic
├── database/
│   └── migrations/             # Database Schema
├── resources/
│   ├── views/                  # Blade Templates
│   └── js/                     # JavaScript
├── routes/
│   ├── web.php                 # Web Routes
│   ├── api.php                 # API Routes
│   └── console.php             # Scheduled Tasks
└── public/                     # Public Assets
```

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

## 🎯 Jadwal Auto Sync

Sinkronisasi otomatis berjalan setiap **8 jam** pada:
- 00:00 (tengah malam)
- 08:00 (pagi)
- 16:00 (sore)

**Urutan sync:**
1. Countries (00:00)
2. Weather (00:05)
3. Exchange Rates (00:10)
4. Economy Data (00:15)
5. News (00:20)
6. Risk Analysis (00:25)

## 📝 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 📧 Contact

Untuk pertanyaan atau bantuan, silakan hubungi developer.

---

**💡 Tip:** Pastikan queue worker dan scheduler berjalan untuk auto sync. Gunakan `start-scheduler.bat` di Windows.
