# WasteRoute — Modul Interaktif HCS & SCS v2.0

Modul web interaktif untuk mempelajari sistem pengangkutan sampah perkotaan:
**Hauled Container System (HCS)** dan **Stationary Container System (SCS)**.

Fitur:
- 📚 Teori dasar HCS & SCS
- 🟢 Simulasi HCS
- 🟠 Simulasi SCS
- ⚖️ Perbandingan HCS vs SCS
- 📐 Kalibrasi nilai konstanta a & b
- 📊 Grafik (Chart.js)
- 🧩 Contoh kasus siap pakai

## Live Demo

🔗 **https://play.reloop.id/hcs-scs/**

## Struktur

```
.
├── index.html              # Halaman utama (HTML + referensi aset)
├── css/style.css          # Gaya tampilan
├── js/                    # Logika aplikasi (dipisah per modul)
│   ├── calculations.js    # Rumus inti HCS & SCS
│   ├── charts.js          # Grafik
│   ├── ui.js              # Helper UI
│   ├── hcs.js             # Modul HCS
│   ├── scs.js             # Modul SCS
│   ├── compare.js         # Perbandingan
│   ├── calib.js           # Kalibrasi a & b
│   ├── cases.js           # Contoh kasus
│   └── app.js             # Init + progressive enhancement
└── tests/                 # Uji logika (Node.js, tanpa dependensi)
```

## Menjalankan

Butuh PHP untuk menyajikan halaman:

```bash
php -S localhost:8000
# lalu buka http://localhost:8000/index.html
```

Menjalankan uji logika (butuh Node.js):

```bash
node tests/calculations.test.js
```
