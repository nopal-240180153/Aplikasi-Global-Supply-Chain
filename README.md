# 🌍 Global Supply Chain Risk Intelligence Platform

Platform monitoring risiko rantai pasok global berbasis web dengan integrasi multi-API, sentiment analysis, visualisasi data interaktif, dan simulasi logistik menggunakan **Laravel 11**.

---

## 📋 Daftar Fitur (11 Fitur Utama)

| # | Fitur | Deskripsi |
|---|-------|-----------|
| 1 | 📊 **Dashboard Interaktif** | Overview statistik global, chart distribusi negara & risiko, top risk countries |
| 2 | 🌍 **Data Negara Lengkap** | 254 negara dengan detail ekonomi, cuaca, risiko & peta interaktif |
| 3 | ⚠️ **Analisis Risiko Supply Chain** | Multi-factor risk scoring (0-100) dengan filter dinamis |
| 4 | 💰 **Data Ekonomi** | GDP, exports, imports, trade balance dari World Bank API |
| 5 | 🌦️ **Monitoring Cuaca** | Real-time weather data (suhu, curah hujan, kelembaban, angin) |
| 6 | 💱 **Nilai Tukar Mata Uang** | 247 currencies dengan 4 interactive charts |
| 7 | 📰 **Berita Global & Sentiment Analysis** | Artikel berita dengan lexicon-based sentiment scoring |
| 8 | ⚓ **Peta Pelabuhan Global** | 126+ ports dengan Leaflet.js interactive map & AJAX filter |
| 9 | 📈 **Visualisasi & Perbandingan Data** | Multi-country comparison dengan Chart.js dan Bezier Curve Route Map |
| 10 | ⭐ **Watchlist / Favorit** | Manajemen daftar negara favorit per user |
| 11 | 🚢 **Logistics Simulator** | Simulasi rute antar pelabuhan dengan kalkulasi jarak, ETA, cuaca real-time & peta rute |

### ✨ Fitur Otomatisasi & Fitur Baru (Terbaru)

- ⚡ **Automatic Lazy Fetching (`AutoSyncService`)** — Data ditarik otomatis dari API ketika tabel kosong secara transparan tanpa perlu menjalankan queue worker manual.
- 🕒 **WIB Timezone (Asia/Jakarta)** — Seluruh timestamp sinkronisasi dan aplikasi sudah disesuaikan dengan Waktu Indonesia Barat (WIB).
- 🗺️ **Bezier Route Curves** — Peta perbandingan negara (`/comparison`) menggunakan rute lengkung Bezier yang rapi tanpa terpotong batas bujur bumi/kutub.
- 🔐 **Admin Portal & Force Sync** — Panel admin untuk memantau status sinkronisasi, manajemen user, pelabuhan, artikel, dan manual force sync.
- 📱 **Responsive Modern UI** — Bootstrap 5 + Bootstrap Icons + Chart.js + Leaflet.js.

---

## 🧠 Algoritma & Logika Bisnis

### 1. Algoritma Haversine — Logistics Simulator

Digunakan untuk menghitung jarak antara dua pelabuhan di permukaan bumi (Great-Circle Distance).

```
a = sin²(Δlat/2) + cos(lat1) × cos(lat2) × sin²(Δlon/2)
c = 2 × atan2(√a, √(1−a))
d = R × c    (R = 6371 km)
```

- **Detour Factor ×1.25** → kompensasi manuver menghindari daratan
- **Konversi ke Nautical Miles**: km × 0.539957
- **ETA**: kecepatan kapal kargo rata-rata 20 knots (~37 km/jam)
- File: `app/Http/Controllers/LogisticsController.php`

---

### 2. Automatic Lazy Fetching Engine (`AutoSyncService`)

Mencegah aplikasi melambat atau memblokir pengguna saat navigasi:

- **Deteksi Otomatis**: Saat pengguna membuka halaman (misal `/weather` atau `/news`), `AutoSyncService` mengecek ketersediaan data di database.
- **Pemuatan Instan**: Jika data sudah ada di database lokal, halaman dimuat seketika dalam hitungan milidetik.
- **Fallback Pull**: Pemanggilan API eksternal hanya dilakukan jika tabel benar-benar kosong, sehingga menjaga efisiensi kuota API dan performa web.
- File: `app/Services/AutoSyncService.php`

---

### 3. Weighted Multi-Criteria Risk Scoring — Risk Analysis

Menghitung Supply Chain Risk Score (0-100) menggunakan empat variabel berbobot:

```
Total Score = (Weather Score × 30%)
            + (Economy Score × 30%)
            + (Exchange Score × 20%)
            + (News Score    × 20%)
```

| Komponen | Bobot | Service |
|----------|-------|---------|
| Cuaca Ekstrem | 30% | WeatherRiskService.php |
| Volatilitas Ekonomi | 30% | EconomyRiskService.php |
| Fluktuasi Nilai Tukar | 20% | ExchangeRiskService.php |
| Sentimen Berita | 20% | NewsSentimentService.php |

**Level Risiko:**
- Score < 20  → Rendah (Hijau)
- Score 20-34 → Sedang (Kuning)
- Score >= 35 → Tinggi (Merah)

File: `app/Services/Risk/RiskCalculationService.php`

---

### 4. Lexicon-Based Sentiment Analysis — News Analytics

Menganalisis sentimen berita menggunakan pendekatan bag-of-words:

1. **Tokenisasi**: Teks di-lowercase, dibersihkan, dipecah per kata (panjang > 2 karakter)
2. **Pencocokan Leksikon**: Setiap kata dicek ke database `positive_words` dan `negative_words`
3. **Kalkulasi Skor**:

```
difference = positiveCount - negativeCount

Jika difference > 0 → Positive → score = min(100, 50 + difference × 10)
Jika difference < 0 → Negative → score = max(0,  50 + difference × 10)
Jika difference = 0 → Neutral  → score = 50
```

File: `app/Services/News/NewsSentimentService.php`

---

## 🌐 Integrasi API Eksternal (6 API)

| API | Data | Endpoint / Mode |
|-----|------|-----------------|
| REST Countries API | Nama, bendera, populasi, koordinat negara | Public REST API |
| World Bank API | GDP, ekspor, impor, neraca perdagangan | Public REST API |
| ExchangeRate API | Nilai tukar mata uang real-time vs USD | API Key |
| Open-Meteo API | Suhu, curah hujan, kelembaban, kecepatan angin | Public REST API |
| GNews API | Berita global untuk sentiment analysis | API Key |
| World Port Index | Lokasi pelabuhan global dengan koordinat GPS | JSON Dataset |

---

## 🚀 Quick Start (Menjalankan di Lokal / Laragon)

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

Pastikan konfigurasi waktu di `.env`:
```env
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost
```

### 3. Setup Database
```bash
php artisan migrate
```

### 4. Jalankan Aplikasi
Buka Laragon (Start All) atau jalankan via Artisan:
```bash
php artisan serve
```

Buka browser dan akses `http://localhost:8000` atau `http://localhost/aplikasi-global-supply-chain`. Data akan terisi secara otomatis!

---

## 📁 Struktur Project Utama

```
app/
├── Http/Controllers/          # Dashboard, Country, Risk, Logistics, dll
│   ├── Admin/                 # Admin CRUD controllers
│   └── Api/                   # REST API controllers
├── Models/                    # Eloquent models
├── Jobs/                      # Sync jobs
└── Services/
    ├── AutoSyncService.php    # Automatic Lazy Fetching service
    ├── API/                   # External API services
    ├── News/                  # Sentiment analysis engine
    ├── Risk/                  # Risk calculation engine
    └── Sync/                  # Sync orchestration
database/
├── migrations/                # Database migrations
resources/views/               # Blade templates & UI
routes/
├── web.php                    # Web routes (user + admin)
└── api.php                    # REST API routes
```

---

## 📝 License

The Laravel framework is open-sourced software licensed under the MIT license.
