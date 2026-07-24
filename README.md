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
| 9 | 📈 **Visualisasi & Perbandingan Data** | Multi-country comparison dengan Chart.js (GDP, Inflasi, Kurs, Risk) |
| 10 | ⭐ **Watchlist / Favorit** | Manajemen daftar negara favorit per user |
| 11 | 🚢 **Logistics Simulator** | Simulasi rute antar pelabuhan dengan kalkulasi jarak, ETA, cuaca real-time & peta rute |

### ✨ Fitur Tambahan (Admin & API)

- 🔐 **Admin Portal** — CRUD pelabuhan, manajemen user, artikel, lexicon editor, sync management
- 🔌 **REST API v1** — API endpoints publik untuk integrasi sistem eksternal
- 🔄 **Background Job Queue** — 7 async jobs untuk sinkronisasi data API
- 📱 **Responsive Modern UI** — Bootstrap 5 + Bootstrap Icons + Chart.js + Leaflet.js

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
- File: app/Http/Controllers/LogisticsController.php

---

### 2. Weighted Multi-Criteria Risk Scoring — Risk Analysis

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

File: app/Services/Risk/RiskCalculationService.php

---

### 3. Weather Risk Scoring — Sub-komponen Risk

| Parameter | Nilai | Penalti |
|-----------|-------|---------|
| Suhu | >= 40°C | +30 |
| Suhu | 35-39°C | +20 |
| Suhu | 30-34°C | +10 |
| Curah Hujan | >= 150 mm | +25 |
| Curah Hujan | 75-149 mm | +15 |
| Curah Hujan | 30-74 mm | +5 |
| Kecepatan Angin | >= 80 km/h | +25 |
| Kecepatan Angin | 40-79 km/h | +15 |
| Kecepatan Angin | 20-39 km/h | +5 |

Skor maksimum: 100. File: app/Services/Risk/WeatherRiskService.php

---

### 4. Economy Risk Scoring — Sub-komponen Risk

**Inflasi:**
- <= 3%  → +5  (stabil)
- 3-6%   → +15
- 6-10%  → +30
- > 10%  → +40 (krisis)

**Neraca Perdagangan:**
- Ekspor >= Impor → +5  (surplus)
- Ekspor < Impor  → +15 (defisit)

**GDP:**
- >= 1 Triliun USD    → +5  (ekonomi besar)
- 100 Miliar-1 T USD  → +10
- < 100 Miliar USD    → +20 (ekonomi kecil)

File: app/Services/Risk/EconomyRiskService.php

---

### 5. Exchange Rate Risk Scoring — Sub-komponen Risk

| Kurs (vs USD) | Skor Risiko |
|---------------|-------------|
| < 5 | 5 (sangat kuat) |
| 5 - 49 | 10 |
| 50 - 499 | 15 |
| 500 - 4999 | 20 |
| 5000 - 14999 | 30 |
| >= 15000 | 40 (sangat lemah) |

File: app/Services/Risk/ExchangeRiskService.php

---

### 6. Lexicon-Based Sentiment Analysis — News Analytics

Menganalisis sentimen berita menggunakan pendekatan bag-of-words:

1. **Tokenisasi**: Teks di-lowercase, dibersihkan, dipecah per kata (panjang > 2 karakter)
2. **Pencocokan Leksikon**: Setiap kata dicek ke database positive_words dan negative_words
3. **Kalkulasi Skor**:

```
difference = positiveCount - negativeCount

Jika difference > 0 → Positive → score = min(100, 50 + difference × 10)
Jika difference < 0 → Negative → score = max(0,  50 + difference × 10)
Jika difference = 0 → Neutral  → score = 50
```

4. **Cache Leksikon**: Di-cache selama 24 jam untuk efisiensi

File: app/Services/News/NewsSentimentService.php

---

### 7. Dynamic Weather Penalty — Logistics Simulator

Saat kalkulasi rute, sistem membaca cuaca real-time port tujuan dan memodifikasi Risk Score:

```
Angin > 80 km/h → Risk Score += 25 + label "(Cuaca Buruk)"
Angin > 40 km/h → Risk Score += 15 + label "(Cuaca Buruk)"
```

Skor akhir dibatasi maksimum 100. File: app/Http/Controllers/LogisticsController.php

---

### 8. Background Job Queue System — Data Synchronization

7 background job yang berjalan secara asinkron via Laravel Queue:

| Job | Tugas |
|-----|-------|
| CountrySyncJob | Sinkronisasi data negara dari REST Countries API |
| WeatherSyncJob | Sinkronisasi data cuaca dari Open-Meteo API |
| ExchangeRateSyncJob | Sinkronisasi nilai tukar dari ExchangeRate API |
| EconomySyncJob | Sinkronisasi data ekonomi dari World Bank API |
| NewsSyncJob | Sinkronisasi & sentiment analysis berita dari GNews API |
| PortSyncJob | Sinkronisasi data pelabuhan dari World Port Index |
| RiskSyncJob | Kalkulasi ulang Risk Score semua negara |

---

### 9. Interactive Map — AJAX-based Port Loading

Peta pelabuhan menggunakan arsitektur AJAX lazy loading:

1. Halaman dimuat → peta Leaflet.js diinisialisasi (tile: CartoDB Positron)
2. Setelah peta siap → fetch("/api/ports/data") dipanggil secara asinkron
3. Response JSON diproses → marker ditambahkan satu per satu
4. Filter (nama/negara) memicu fetch ulang tanpa reload halaman

**Validasi koordinat**: marker hanya digambar jika lat dalam [-90, 90] dan lng dalam [-180, 180].

---

## 🌐 Integrasi API Eksternal (6 API)

| API | Data | Auth |
|-----|------|------|
| REST Countries API | Nama, bendera, populasi, koordinat negara | API Key |
| World Bank API | GDP, ekspor, impor, neraca perdagangan | Public |
| ExchangeRate API | Nilai tukar mata uang real-time vs USD | API Key |
| Open-Meteo API | Suhu, curah hujan, kelembaban, kecepatan angin | Public |
| GNews API | Berita global untuk sentiment analysis | API Key |
| World Port Index | Lokasi pelabuhan global dengan koordinat GPS | Static JSON |

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

### 5. Jalankan Server
```bash
php artisan serve
```

### 6. Jalankan Queue Worker (terminal terpisah)
```bash
php artisan queue:work
```

---

## 🔄 Urutan Sinkronisasi Data

Lakukan sinkronisasi melalui Admin > Sync secara berurutan:

```
1. Countries   →  Data master negara (wajib pertama)
2. Weather     →  Data cuaca per negara
3. Exchange    →  Nilai tukar mata uang
4. Economy     →  GDP, ekspor, impor
5. News        →  Berita + sentiment analysis
6. Ports       →  Data pelabuhan global
7. Risk        →  Kalkulasi ulang skor risiko (wajib terakhir)
```

---

## 📊 Tech Stack

### Backend
- PHP 8.3 + Laravel 11
- MySQL — 22 tabel relasional
- Laravel Queue — Background job processing
- Service Layer + Repository Pattern

### Frontend
- Bootstrap 5.3
- Chart.js 4.x — Bar, Line, Doughnut, Pie charts
- Leaflet.js 1.9.4 — Interactive maps
- Bootstrap Icons 1.11
- Vite — Asset bundler

---

## 📁 Struktur Project

```
app/
├── Http/Controllers/          # 22+ controllers (Dashboard, Country, Risk, Logistics, dll)
│   ├── Admin/                 # Admin CRUD controllers
│   └── Api/                   # REST API controllers
├── Models/                    # 15+ Eloquent models
├── Jobs/                      # 7 background sync jobs
└── Services/
    ├── API/                   # 5 API service classes
    ├── Mappers/               # Data mapping (PortMapper, dll)
    ├── News/                  # Sentiment analysis engine
    ├── Risk/                  # Risk calculation engine
    └── Sync/                  # Sync orchestration
database/
├── migrations/                # 22 database migrations
└── seeders/                   # Initial data seeders
resources/views/               # 30+ Blade templates
routes/
├── web.php                    # Web routes (user + admin)
└── api.php                    # REST API v1 routes
storage/app/data/              # Static JSON (ports fallback)
```

---

## 🗄️ Tabel Database Utama

| Tabel | Isi |
|-------|-----|
| countries | Data master 254 negara |
| ports | Data pelabuhan global (126+ ports) |
| economy_data | GDP, ekspor, impor per negara per tahun |
| exchange_rates | Nilai tukar per mata uang |
| weather_logs | Log data cuaca per negara |
| news_articles | Artikel berita + skor sentimen |
| risk_scores | Skor risiko per negara per periode |
| positive_words | Kamus kata positif untuk sentiment analysis |
| negative_words | Kamus kata negatif untuk sentiment analysis |
| sync_logs | Log riwayat sinkronisasi API |

---

## 🌐 Deployment Lokal via Ngrok

```bash
# Terminal 1 - Jalankan aplikasi
php artisan serve

# Terminal 2 - Jalankan queue worker
php artisan queue:work

# Terminal 3 - Ekspos ke publik
ngrok http 8000
```

---

## 📚 Dokumentasi Tambahan

- REST_API_DOCUMENTATION.md — API endpoints & contoh penggunaan
- DEPLOYMENT_GUIDE.md — Panduan deployment ke hosting
- WEATHER_MAP_FEATURE.md — Dokumentasi fitur peta cuaca
- EXCHANGE_RATE_CHARTS_IMPROVEMENT.md — Fitur chart nilai tukar

---

## 📝 License

The Laravel framework is open-sourced software licensed under the MIT license.
