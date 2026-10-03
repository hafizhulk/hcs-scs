<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Modul interaktif HCS & SCS — sistem pengangkutan sampah perkotaan. Simulasi, perbandingan, kalibrasi, dan contoh kasus.">
<title>HCS & SCS — Modul Interaktif Transportasi Sampah</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;700&family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

</head>
<body>
<header>
  <div class="hi">
    <div class="logo">
      <div class="logo-icon">♻️</div>
      <div>
        <h1>WasteRoute</h1>
        <p>Modul Interaktif HCS &amp; SCS v2.0</p>
      </div>
    </div>
    <nav>
      <button class="tb active" onclick="sw('theory',this)">📚 Teori</button>
      <button class="tb" onclick="sw('hcs',this)">🟢 HCS</button>
      <button class="tb" onclick="sw('scs',this)">🟠 SCS</button>
      <button class="tb" onclick="sw('compare',this)">⚖️ Perbandingan</button>
      <button class="tb" onclick="sw('calib',this)">📐 Nilai a &amp; b</button>
      <button class="tb" onclick="sw('cases',this)">📝 Contoh Kasus</button>
    </nav>
  </div>
</header>

<div class="wrap">
<div class="hero">
  <div class="badge"><span class="dot"></span>Modul Pembelajaran Interaktif — Teknik Lingkungan</div>
  <h2><span class="hg">Hauled</span> vs <span class="ho">Stationary</span><br>Container System</h2>
  <p>Pelajari, simulasikan, dan bandingkan sistem pengangkutan sampah HCS dan SCS. Lengkap dengan rumus, tabel referensi, simulasi interaktif, dan contoh kasus terselesaikan.</p>
</div>

<!-- ====== TEORI ====== -->
<div id="tab-theory" class="sec active">
  <div class="note-box" id="chart-status" role="status" hidden></div>
  <div class="stag">01 — Dasar Teori</div>
  <div class="sh">Sistem Pengangkutan Sampah Perkotaan</div>

  <div class="card">
    <div class="ch"><div class="tag tb2">Pendahuluan</div><h3>Apa itu HCS dan SCS?</h3></div>
    <p>Pengangkutan sampah dari sumber ke TPA/TPST adalah komponen <strong style="color:var(--txt)">paling mahal</strong> dalam sistem pengelolaan sampah perkotaan (bisa mencapai 60–80% biaya total). Terdapat dua model utama:</p>
    <div class="tgrid" style="margin-top:10px">
      <div style="background:var(--sur2);border:1px solid rgba(0,212,160,.25);border-radius:8px;padding:16px;">
        <div style="font-size:24px;margin-bottom:6px">🚛</div>
        <div style="font-family:var(--mono);font-size:9px;letter-spacing:1px;color:var(--G);margin-bottom:5px">HAULED CONTAINER SYSTEM (HCS)</div>
        <p style="margin:0;font-size:12px">Kontainer <strong style="color:var(--txt)">diangkut seluruhnya</strong> ke TPA, dikosongkan, lalu dikembalikan atau ditukar. Satu ritasi = satu kontainer penuh. Digunakan untuk sumber sampah bervolume besar (pasar, pertokoan, hotel).</p>
      </div>
      <div style="background:var(--sur2);border:1px solid rgba(255,120,73,.25);border-radius:8px;padding:16px;">
        <div style="font-size:24px;margin-bottom:6px">🏘️</div>
        <div style="font-family:var(--mono);font-size:9px;letter-spacing:1px;color:var(--O);margin-bottom:5px">STATIONARY CONTAINER SYSTEM (SCS)</div>
        <p style="margin:0;font-size:12px">Kontainer <strong style="color:var(--txt)">tetap di tempat</strong>, hanya sampahnya yang dimuat ke truk. Satu ritasi = banyak kontainer. Digunakan untuk permukiman padat dan area campuran dengan truk pemadat (compactor).</p>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="ch"><div class="tag tb2">Konsep Kunci</div><h3>4 Unit Operasi — Komponen Waktu Ritasi</h3></div>
    <p>Baik HCS maupun SCS dianalisis menggunakan <strong style="color:var(--txt)">empat unit operasi</strong>. Setiap komponen dipengaruhi faktor yang berbeda, itulah mengapa keempatnya harus dihitung terpisah.</p>
    <div class="og">
      <div class="oi"><div class="ic">🔄</div><div class="on">1. Pickup (P)</div><div class="od">Waktu muat sampah dari kontainer ke truk di lapangan</div></div>
      <div class="oi"><div class="ic">🚛</div><div class="on">2. Haul (h)</div><div class="od">Waktu perjalanan ke TPA dan kembali — fungsi jarak (h = a + bx)</div></div>
      <div class="oi"><div class="ic">🏭</div><div class="on">3. At-Site (s)</div><div class="od">Waktu antrian + bongkar muat di TPA/TPS</div></div>
      <div class="oi"><div class="ic">⏱️</div><div class="on">4. Off-Route (w)</div><div class="od">Waktu tidak produktif: absen, istirahat, kerusakan, check-in</div></div>
    </div>
    <div class="note-box">💡 <strong>Mengapa dipisahkan?</strong> Karena masing-masing dikendalikan faktor berbeda: <em>Pickup</em> → desain kontainer dan jenis kendaraan; <em>Haul</em> → jarak TPA dan kecepatan jalan; <em>At-site</em> → manajemen TPA; <em>Off-route</em> → manajemen SDM. Dengan memisahkannya, kita bisa mengoptimalkan tiap komponen secara mandiri.</div>
  </div>

  <div class="tgrid">
    <!-- HCS THEORY -->
    <div class="card">
      <div class="ch"><div class="tag tg">HCS</div><h3>Rumus Lengkap HCS</h3></div>
      <p><strong style="color:var(--txt)">Alur operasi HCS:</strong> Truk keluar dari garasi → pergi ke kontainer → angkat kontainer isi → menuju TPA → bongkar → kembali dengan kontainer kosong → turunkan di lokasi semula → ke kontainer berikutnya.</p>
      <div class="cf">
        <div class="cfi"><div class="icon">🏠</div><div class="name">Garasi</div></div>
        <div class="cfa">→</div>
        <div class="cfi"><div class="icon">📦</div><div class="name">Kontainer Isi</div></div>
        <div class="cfa">→</div>
        <div class="cfi"><div class="icon">🏭</div><div class="name">TPA</div></div>
        <div class="cfa">→</div>
        <div class="cfi"><div class="icon">📦</div><div class="name">Kontainer Kosong</div></div>
        <div class="cfa">→</div>
        <div class="cfi"><div class="icon">🏠</div><div class="name">Garasi</div></div>
      </div>
      <p style="font-size:11px;color:var(--mut)">▼ Waktu total per ritasi</p>
      <div class="fbox">T<sub>HCS</sub> = P<sub>HCS</sub> + s + h</div>
      <p style="font-size:11px;color:var(--mut)">▼ Komponen haul (linear empiris)</p>
      <div class="fbox">h = a + b·x</div>
      <p style="font-size:11px;color:var(--mut)">▼ Substitusi → bentuk desain lengkap</p>
      <div class="fbox">T<sub>HCS</sub> = P<sub>HCS</sub> + s + a + b·x</div>
      <p style="font-size:11px;color:var(--mut)">▼ Komponen pickup HCS</p>
      <div class="fbox sm">P<sub>HCS</sub> = p<sub>c</sub> + u<sub>c</sub> + d<sub>bc</sub></div>
      <p style="font-size:11px;color:var(--mut)">▼ Kapasitas dan kebutuhan ritasi/hari</p>
      <div class="fbox sm">N<sub>kapasitas</sub> = max(0, floor([H·(1−w) − (t₁+t₂)] / T<sub>HCS</sub>))</div>
      <div class="fbox sm">N<sub>kebutuhan</sub> = ceil(V<sub>d</sub> / (c · f))</div>
      <div class="note-box">Kebutuhan seluruh sampah terpenuhi bila N_kebutuhan ≤ N_kapasitas. Jika tidak, tambah kendaraan atau jam kerja. Model ini untuk HCS konvensional dengan satu kontainer per ritasi.</div>
      <table class="vt">
        <tr><th>Simbol</th><th>Keterangan</th><th>Satuan</th></tr>
        <tr><td>T<sub>HCS</sub></td><td>Waktu per ritasi HCS</td><td>jam/rit</td></tr>
        <tr><td>P<sub>HCS</sub></td><td>Waktu pickup per ritasi</td><td>jam/rit</td></tr>
        <tr><td>s</td><td>At-site time (di TPA/TPS)</td><td>jam/rit</td></tr>
        <tr><td>a</td><td>Konstanta empiris — overhead tetap</td><td>jam/rit</td></tr>
        <tr><td>b</td><td>Konstanta empiris — per km</td><td>jam/km</td></tr>
        <tr><td>x</td><td>Jarak haul rata-rata (pulang-pergi)</td><td>km/rit</td></tr>
        <tr><td>p<sub>c</sub></td><td>Waktu angkat kontainer isi</td><td>jam/rit</td></tr>
        <tr><td>u<sub>c</sub></td><td>Waktu turunkan kontainer kosong</td><td>jam/rit</td></tr>
        <tr><td>d<sub>bc</sub></td><td>Waktu tempuh antar kontainer</td><td>jam/rit</td></tr>
        <tr><td>H</td><td>Waktu kerja per hari</td><td>jam/hari</td></tr>
        <tr><td>w</td><td>Off-route factor</td><td>desimal</td></tr>
        <tr><td>t₁</td><td>Waktu garasi → kontainer pertama</td><td>jam</td></tr>
        <tr><td>t₂</td><td>Waktu kontainer terakhir → garasi</td><td>jam</td></tr>
        <tr><td>V<sub>d</sub></td><td>Volume sampah/hari</td><td>m³/hari</td></tr>
        <tr><td>c</td><td>Ukuran rata-rata kontainer</td><td>m³</td></tr>
        <tr><td>f</td><td>Faktor penggunaan kontainer</td><td>—</td></tr>
      </table>
    </div>

    <!-- SCS THEORY -->
    <div class="card">
      <div class="ch"><div class="tag to">SCS</div><h3>Rumus Lengkap SCS</h3></div>
      <p><strong style="color:var(--txt)">Alur operasi SCS:</strong> Truk keluar dari garasi → stop di kontainer 1 → muat sampah → stop di kontainer 2, 3, …, n → setelah penuh → menuju TPA → bongkar → kembali ke rute berikutnya.</p>
      <div class="cf">
        <div class="cfi"><div class="icon">🏠</div><div class="name">Garasi</div></div>
        <div class="cfa">→</div>
        <div class="cfi" style="flex:2;border-color:rgba(255,120,73,.3)"><div class="icon">🏘️🏘️🏘️</div><div class="name" style="color:var(--O)">Banyak Kontainer</div></div>
        <div class="cfa">→</div>
        <div class="cfi"><div class="icon">🏭</div><div class="name">TPA</div></div>
      </div>
      <p style="font-size:11px;color:var(--mut)">▼ Waktu total per ritasi SCS</p>
      <div class="fbox fo">T<sub>SCS</sub> = P<sub>SCS</sub> + s + a + b·x</div>
      <p style="font-size:11px;color:var(--mut)">▼ Komponen pickup SCS (banyak stop)</p>
      <div class="fbox fo sm">P<sub>SCS</sub> = C<sub>T</sub>·u<sub>c</sub> + (n<sub>p</sub>−1)·d<sub>bc</sub></div>
      <p style="font-size:11px;color:var(--mut)">▼ Jumlah kontainer per ritasi</p>
      <div class="fbox fo sm">C<sub>T</sub> = floor(V·r / (c·f))</div>
      <p style="font-size:11px;color:var(--mut)">▼ Jumlah ritasi per hari</p>
      <div class="fbox fo sm">C<sub>hari</sub> = ceil(V<sub>d</sub> / (c·f))<br>N<sub>d</sub> = ceil(C<sub>hari</sub> / C<sub>T</sub>)</div>
      <p style="font-size:11px;color:var(--mut)">▼ Waktu kerja yang diperlukan per hari</p>
      <div class="fbox fo sm">H = [(t₁+t₂) + ΣP<sub>rit</sub> + N<sub>d</sub>·(s+a+b·x)] / (1−w)</div>
      <div class="note-box">Model waktu menggunakan haul pulang-pergi sampai area pengumpulan untuk setiap ritasi; t₂ dari area tersebut ke garasi. Bila ritasi terakhir langsung TPA → garasi, koreksi perjalanan akhir memakai data rute.
      <br>V_d memakai volume sampah sebelum pemadatan. Kontainer dikosongkan utuh; jumlah lokasi ≤ jumlah kontainer. Ritasi terakhir dapat lebih sedikit kontainer. Untuk estimasi, jumlah lokasi terakhir = ceil(C_terakhir · n_p / C_T), dengan asumsi sebaran kontainer antar lokasi merata.</div>
      <table class="vt fo">
        <tr><th>Simbol</th><th>Keterangan</th><th>Satuan</th></tr>
        <tr><td>T<sub>SCS</sub></td><td>Waktu per ritasi SCS</td><td>jam/rit</td></tr>
        <tr><td>P<sub>SCS</sub></td><td>Waktu pickup per ritasi</td><td>jam/rit</td></tr>
        <tr><td>C<sub>T</sub></td><td>Jumlah kontainer dikosongkan/rit</td><td>kontainer/rit</td></tr>
        <tr><td>u<sub>c</sub></td><td>Waktu kuras per kontainer</td><td>jam/kontainer</td></tr>
        <tr><td>n<sub>p</sub></td><td>Jumlah lokasi kontainer/rit</td><td>lokasi/rit</td></tr>
        <tr><td>d<sub>bc</sub></td><td>Waktu tempuh antar lokasi</td><td>jam/lokasi</td></tr>
        <tr><td>V</td><td>Volume mobil pengumpul</td><td>m³/rit</td></tr>
        <tr><td>r</td><td>Rasio kompaksi: volume lepas / volume padat</td><td>≥ 1</td></tr>
        <tr><td>f</td><td>Faktor keterisian kontainer</td><td>0 &lt; f ≤ 1</td></tr>
        <tr><td>H</td><td>Waktu kerja dibutuhkan/hari</td><td>jam/hari</td></tr>
        <tr><td>V<sub>d</sub></td><td>Volume sampah/hari</td><td>m³/hari</td></tr>
      </table>
    </div>
  </div>

  <div class="note-box">Referensi rumus: <a href="https://faculty.mercer.edu/mccreanor_pt/eve420/Lesson06-Collection/Lesson06-HCS.html" target="_blank" rel="noopener noreferrer" style="color:var(--B)">HCS</a> dan <a href="https://faculty.mercer.edu/mccreanor_pt/eve420/Lesson06-Collection/Lesson06-SCS.html" target="_blank" rel="noopener noreferrer" style="color:var(--B)">SCS — Mercer University</a>. Contoh memakai volume rata-rata dan belum memeriksa batas berat kendaraan.</div>
  <!-- DATA KENDARAAN -->
  <div class="card">
    <div class="ch"><div class="tag ty">Data Referensi</div><h3>Data Operasional Tipikal Kendaraan (Tchobanoglous et al.)</h3></div>
    <p>Tabel ini memberikan nilai tipikal untuk parameter kendaraan yang umum digunakan dalam analisis HCS dan SCS.</p>
    <div style="overflow-x:auto">
    <table class="speed-table">
      <thead>
        <tr>
          <th colspan="2">Sistem / Jenis Kendaraan</th>
          <th>Metode Muat</th>
          <th>Rasio Kompaksi (r)</th>
          <th>Waktu angkat+turunkan kontainer (h/rit)</th>
          <th>Waktu kuras kontainer (h/kontainer)</th>
          <th>At-site time s (h/rit)</th>
        </tr>
      </thead>
      <tbody>
        <tr><td colspan="7" style="color:var(--G);font-size:9px;letter-spacing:1px;padding:8px 10px">— HCS (Hauled Container) —</td></tr>
        <tr><td></td><td>Hoist truck</td><td>Mekanikal</td><td>—</td><td>0.067</td><td>—</td><td>0.053</td></tr>
        <tr><td></td><td>Tilt-frame</td><td>Mekanikal</td><td>—</td><td>0.40</td><td>—</td><td>0.127</td></tr>
        <tr><td></td><td>Tilt-frame (dgn kompaksi)</td><td>Mekanikal</td><td>2.0–4.0</td><td>0.40</td><td>—</td><td>0.133</td></tr>
        <tr><td colspan="7" style="color:var(--O);font-size:9px;letter-spacing:1px;padding:8px 10px">— SCS Compactor —</td></tr>
        <tr><td></td><td>Compactor truck</td><td>Mekanikal</td><td>2.0–2.5</td><td>—</td><td>0.050</td><td>0.10</td></tr>
        <tr><td></td><td>Compactor truck</td><td>Manual</td><td>2.0–2.5</td><td>—</td><td>—</td><td>0.10</td></tr>
        <tr><td colspan="7" style="color:var(--B);font-size:9px;letter-spacing:1px;padding:8px 10px">— SCS Noncompactor —</td></tr>
        <tr><td></td><td>Noncompactor</td><td>Mekanikal</td><td>—</td><td>—</td><td>—</td><td>0.10*</td></tr>
        <tr><td></td><td>Noncompactor</td><td>Manual</td><td>—</td><td>—</td><td>—</td><td>0.10*</td></tr>
      </tbody>
    </table>
    </div>
    <p style="font-size:10px;color:var(--mut);margin-top:6px">* Nilai at-site time dapat lebih besar bergantung kondisi TPA setempat.</p>
  </div>
</div>

<!-- ====== HCS SIM ====== -->
<div id="tab-hcs" class="sec">
  <div class="stag">02 — Simulasi</div>
  <div class="sh">Kalkulator HCS — Hauled Container System</div>
  <div class="slim">
    <div>
      <div class="card">
        <div class="ch"><div class="tag tg">Input Parameter</div><h3>HCS</h3>
          <div style="margin-left:auto;display:flex;align-items:center;gap:6px;font-family:var(--mono);font-size:9px;color:var(--mut)">
            Satuan:
            <button id="hcs-unit-btn" onclick="toggleHCSUnit()" style="padding:3px 10px;background:rgba(0,212,160,.15);border:1px solid var(--G);border-radius:4px;color:var(--G);font-family:var(--mono);font-size:9px;cursor:pointer;font-weight:700;letter-spacing:1px">KM</button>
          </div>
        </div>
        <div class="ibox-warn" id="hcs-unit-note" style="margin:0 0 12px;font-size:11px;padding:8px 12px">Satuan aktif: <strong id="hcs-unit-label">Kilometer (km)</strong>. Tombol satuan mengonversi x dan b bersama tanpa mengganti skenario. Nilai a tetap dalam jam.</div>
        <div class="note-box">Jika tabel memberikan p_c + u_c, masukkan total satu kali: misalnya p_c = total dan u_c = 0.</div>
        <div class="ig"><div class="il"><span>p_c — Waktu angkat kontainer isi</span><span class="vs">p<sub>c</sub></span></div><input type="range" id="hp" min="0.01" max="0.5" step="0.001" value="0.067" oninput="uHCS()"><div class="rv"><span>0.01h</span><span class="rc" id="hpv">0.067h</span><span>0.50h</span></div></div>
        <div class="ig"><div class="il"><span>u_c — Waktu turunkan kontainer kosong</span><span class="vs">u<sub>c</sub></span></div><input type="range" id="hu" min="0" max="0.3" step="0.005" value="0" oninput="uHCS()"><div class="rv"><span>0h</span><span class="rc" id="huv">0.000h</span><span>0.30h</span></div></div>
        <div class="ig"><div class="il"><span>d_bc — Waktu tempuh antar kontainer</span><span class="vs">d<sub>bc</sub></span></div><input type="range" id="hd" min="0" max="0.3" step="0.005" value="0" oninput="uHCS()"><div class="rv"><span>0h</span><span class="rc" id="hdv">0.000h</span><span>0.30h</span></div></div>
        <div class="ig"><div class="il"><span>s — At-site time di TPA</span><span class="vs">s</span></div><input type="range" id="hs" min="0.05" max="0.5" step="0.001" value="0.053" oninput="uHCS()"><div class="rv"><span>0.05h</span><span class="rc" id="hsv">0.053h</span><span>0.50h</span></div></div>
        <div class="ig"><div class="il"><span id="hx-label">x — Jarak haul pulang-pergi (km)</span><span class="vs">x</span></div><input type="range" id="hx" min="1" max="80" step="any" value="20" oninput="uHCS()"><div class="rv"><span id="hx-min">1km</span><span class="rc" id="hxv">20km</span><span id="hx-max">80km</span></div></div>
        <div class="ig"><div class="il"><span>a — Konstanta overhead tetap</span><span class="vs">a</span></div><input type="range" id="ha" min="0.01" max="0.1" step="0.001" value="0.016" oninput="uHCS()"><div class="rv"><span>0.01</span><span class="rc" id="hav">0.016h</span><span>0.10</span></div></div>
        <div class="ig"><div class="il"><span id="hb-label">b — Konstanta per km</span><span class="vs">b</span></div><input type="range" id="hb" min="0.005" max="0.07" step="any" value="0.011" oninput="uHCS()"><div class="rv"><span id="hb-unit-start">0.005</span><span class="rc" id="hbv">0.011h/km</span><span id="hb-unit-end">0.070</span></div></div>
        <div style="height:1px;background:var(--bor);margin:12px 0"></div>
        <div class="ig"><div class="il"><span>H — Waktu kerja per hari</span><span class="vs">H</span></div><input type="range" id="hH" min="6" max="10" step="0.5" value="8" oninput="uHCS()"><div class="rv"><span>6jam</span><span class="rc" id="hHv">8.0jam</span><span>10jam</span></div></div>
        <div class="ig"><div class="il"><span>w — Off-route factor</span><span class="vs">w</span></div><input type="range" id="hw" min="0.10" max="0.25" step="0.01" value="0.15" oninput="uHCS()"><div class="rv"><span>10%</span><span class="rc" id="hwv">15%</span><span>25%</span></div></div>
        <div class="ig"><div class="il"><span>t₁ — Garasi → kontainer pertama</span><span class="vs">t₁</span></div><input type="range" id="ht1" min="0.05" max="0.6" step="0.005" value="0.1" oninput="uHCS()"><div class="rv"><span>0.05h</span><span class="rc" id="ht1v">0.100h</span><span>0.60h</span></div></div>
        <div class="ig"><div class="il"><span>t₂ — Kontainer terakhir → garasi</span><span class="vs">t₂</span></div><input type="range" id="ht2" min="0.05" max="0.6" step="any" value="0.1" oninput="uHCS()"><div class="rv"><span>0.05h</span><span class="rc" id="ht2v">0.100h</span><span>0.60h</span></div></div>
        <div class="ig"><div class="il"><span>V_d — Volume sampah/hari</span><span class="vs">V<sub>d</sub></span></div><input type="range" id="hvd" min="10" max="200" step="5" value="45" oninput="uHCS()"><div class="rv"><span>10m³</span><span class="rc" id="hvdv">45m³</span><span>200m³</span></div></div>
        <div class="ig"><div class="il"><span>c — Ukuran kontainer rata-rata</span><span class="vs">c</span></div><input type="range" id="hc" min="1" max="20" step="0.5" value="6" oninput="uHCS()"><div class="rv"><span>1m³</span><span class="rc" id="hcv">6.0m³</span><span>20m³</span></div></div>
        <div class="ig"><div class="il"><span>f — Faktor penggunaan kontainer</span><span class="vs">f</span></div><input type="range" id="hf" min="0.5" max="1.0" step="0.05" value="0.9" oninput="uHCS()"><div class="rv"><span>0.50</span><span class="rc" id="hfv">0.90</span><span>1.00</span></div></div>
      </div>
    </div>
    <div>
      <div class="card">
        <div class="ch"><div class="tag tg">Hasil</div><h3>Output Perhitungan HCS</h3></div>
        <div class="rg">
          <div class="ri"><div class="rl">P_HCS</div><div class="rv2" id="rHp">—</div><div class="ru">jam/rit</div></div>
          <div class="ri"><div class="rl">h (Haul Time)</div><div class="rv2" id="rHh">—</div><div class="ru">jam/rit</div></div>
          <div class="ri"><div class="rl">T_HCS</div><div class="rv2" id="rHt">—</div><div class="ru">jam/rit</div></div>
          <div class="ri"><div class="rl">Kapasitas satu kendaraan</div><div class="rv2" id="rHnd1">—</div><div class="ru">rit/hari</div></div>
          <div class="ri"><div class="rl">Kebutuhan seluruh sampah</div><div class="rv2" id="rHnd2">—</div><div class="ru">rit/hari</div></div>
          <div class="ri"><div class="rl">Ritasi satu kendaraan</div><div class="rv2" id="rHdone">—</div><div class="ru">rit/hari</div></div>
          <div class="ri"><div class="rl">Belum terangkut</div><div class="rv2" id="rHleft">—</div><div class="ru">m³/hari</div></div>
          <div class="ri"><div class="rl">Armada minimum</div><div class="rv2" id="rHfleet">—</div><div class="ru">kendaraan dengan parameter identik</div></div>
        </div>
        <div class="note-box" id="hcs-status" role="status" aria-live="polite" style="white-space:pre-line"></div>
        <div class="cs">
          <h4>📋 Langkah Perhitungan</h4>
          <div class="sl"><span>P_HCS = p_c + u_c + d_bc</span><span class="se" id="sHp">—</span></div>
          <div class="sl"><span>h = a + b·x</span><span class="se" id="sHh">—</span></div>
          <div class="sl"><span>T_HCS = P_HCS + s + h</span><span class="se" id="sHt">—</span></div>
          <div class="sl"><span>N_kapasitas = max(0, floor([H·(1−w) − (t₁+t₂)] / T_HCS))</span><span class="se" id="sHnd1">—</span></div>
          <div class="sl"><span>N_kebutuhan = ceil(Vd / (c·f))</span><span class="se" id="sHnd2">—</span></div>
        </div>
      </div>
      <div class="card" style="margin-top:14px">
        <div class="ch"><div class="tag tg">Grafik</div><h3>T_HCS vs Jarak Haul (x)</h3></div>
        <canvas id="hcs-ch" height="190"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- ====== SCS SIM ====== -->
<div id="tab-scs" class="sec">
  <div class="stag">03 — Simulasi</div>
  <div class="sh">Kalkulator SCS — Stationary Container System</div>
  <div class="slim">
    <div>
      <div class="card">
        <div class="ch"><div class="tag to">Input Parameter</div><h3>SCS</h3></div>
        <div class="ig"><div class="il"><span>V — Volume mobil pengumpul</span><span class="vs vo">V</span></div><input class="ro" type="range" id="sv" min="2" max="20" step="0.5" value="10" oninput="uSCS()"><div class="rv"><span>2m³</span><span class="rc ro" id="svv">10.0m³</span><span>20m³</span></div></div>
        <div class="ig"><div class="il"><span>r — Rasio kompaksi</span><span class="vs vo">r</span></div><input class="ro" type="range" id="sr" min="1" max="4" step="0.1" value="2.5" oninput="uSCS()"><div class="rv"><span>1.0×</span><span class="rc ro" id="srv">2.5×</span><span>4.0×</span></div></div>
        <div class="ig"><div class="il"><span>c — Volume kontainer</span><span class="vs vo">c</span></div><input class="ro" type="range" id="sc" min="0.1" max="4" step="0.1" value="0.5" oninput="uSCS()"><div class="rv"><span>0.1m³</span><span class="rc ro" id="scv">0.50m³</span><span>4.0m³</span></div></div>
        <div class="ig"><div class="il"><span>f — Faktor keterisian kontainer</span><span class="vs vo">f</span></div><input class="ro" type="range" id="sf" min="0.5" max="1.0" step="0.05" value="0.9" oninput="uSCS()"><div class="rv"><span>0.5</span><span class="rc ro" id="sfv">0.90</span><span>1.0</span></div></div>
        <div class="ig"><div class="il"><span>u_c — Waktu kuras per kontainer</span><span class="vs vo">u<sub>c</sub></span></div><input class="ro" type="range" id="suc" min="0.01" max="0.15" step="0.005" value="0.05" oninput="uSCS()"><div class="rv"><span>0.01h</span><span class="rc ro" id="sucv">0.050h</span><span>0.15h</span></div></div>
        <div class="ig"><div class="il"><span>n_p — Jumlah lokasi per ritasi</span><span class="vs vo">n<sub>p</sub></span></div><input class="ro" type="range" id="snp" min="1" max="50" step="1" value="8" oninput="uSCS()"><div class="rv"><span>1</span><span class="rc ro" id="snpv">8 lok</span><span>50</span></div></div>
        <div class="ig"><div class="il"><span>d_bc — Waktu tempuh antar lokasi</span><span class="vs vo">d<sub>bc</sub></span></div><input class="ro" type="range" id="sdbc" min="0.005" max="0.1" step="0.005" value="0.02" oninput="uSCS()"><div class="rv"><span>0.005h</span><span class="rc ro" id="sdbcv">0.020h</span><span>0.10h</span></div></div>
        <div class="ig"><div class="il"><span>s — At-site time</span><span class="vs vo">s</span></div><input class="ro" type="range" id="ss" min="0.05" max="0.5" step="0.01" value="0.10" oninput="uSCS()"><div class="rv"><span>0.05h</span><span class="rc ro" id="ssv">0.10h</span><span>0.50h</span></div></div>
        <div class="ig"><div class="il"><span>x — Jarak haul pulang-pergi (km)</span><span class="vs vo">x</span></div><input class="ro" type="range" id="sx" min="1" max="80" step="1" value="25" oninput="uSCS()"><div class="rv"><span>1km</span><span class="rc ro" id="sxv">25km</span><span>80km</span></div></div>
        <div class="ig"><div class="il"><span>a — Konstanta empiris</span><span class="vs vo">a</span></div><input class="ro" type="range" id="sa" min="0.01" max="0.1" step="0.001" value="0.050" oninput="uSCS()"><div class="rv"><span>0.01</span><span class="rc ro" id="sav">0.050h</span><span>0.10</span></div></div>
        <div class="ig"><div class="il"><span>b — Konstanta per km</span><span class="vs vo">b</span></div><input class="ro" type="range" id="sb" min="0.005" max="0.05" step="0.001" value="0.025" oninput="uSCS()"><div class="rv"><span>0.005</span><span class="rc ro" id="sbv">0.025h/km</span><span>0.050</span></div></div>
        <div style="height:1px;background:var(--bor);margin:12px 0"></div>
        <div class="ig"><div class="il"><span>V_d — Volume sampah/hari</span><span class="vs vo">V<sub>d</sub></span></div><input class="ro" type="range" id="svd" min="10" max="300" step="5" value="120" oninput="uSCS()"><div class="rv"><span>10m³</span><span class="rc ro" id="svdv">120m³</span><span>300m³</span></div></div>
        <div class="ig"><div class="il"><span>w — Off-route factor</span><span class="vs vo">w</span></div><input class="ro" type="range" id="sw2" min="0.10" max="0.25" step="0.01" value="0.15" oninput="uSCS()"><div class="rv"><span>10%</span><span class="rc ro" id="sw2v">15%</span><span>25%</span></div></div>
        <div class="ig"><div class="il"><span>t₁ — Garasi → kontainer pertama</span><span class="vs vo">t₁</span></div><input class="ro" type="range" id="st1" min="0.05" max="0.5" step="0.01" value="0.15" oninput="uSCS()"><div class="rv"><span>0.05h</span><span class="rc ro" id="st1v">0.15h</span><span>0.50h</span></div></div>
        <div class="ig"><div class="il"><span>t₂ — Area pengumpulan → garasi</span><span class="vs vo">t₂</span></div><input class="ro" type="range" id="st2" min="0.05" max="0.5" step="0.01" value="0.15" oninput="uSCS()"><div class="rv"><span>0.05h</span><span class="rc ro" id="st2v">0.15h</span><span>0.50h</span></div></div>
        <div class="note-box">Volume sampah memakai basis sebelum pemadatan. Lokasi pada ritasi terakhir diestimasi dari sebaran kontainer merata. Model ini memasukkan kembali ke area pengumpulan setiap ritasi; rute terakhir langsung TPA → garasi membutuhkan koreksi waktu perjalanan.</div>
      </div>
    </div>
    <div>
      <div class="card">
        <div class="ch"><div class="tag to">Hasil</div><h3>Output Perhitungan SCS</h3></div>
        <div class="rg">
          <div class="ri"><div class="rl">C_T (Kapasitas maksimum)</div><div class="rv2 ro" id="rSct">—</div><div class="ru">kontainer/rit</div></div>
          <div class="ri"><div class="rl">P_SCS (ritasi penuh)</div><div class="rv2 ro" id="rSp">—</div><div class="ru">jam/rit</div></div>
          <div class="ri"><div class="rl">T_SCS (ritasi penuh)</div><div class="rv2 ro" id="rSt">—</div><div class="ru">jam/rit</div></div>
          <div class="ri"><div class="rl">Nd (Ritasi/Hari)</div><div class="rv2 ro" id="rSnd">—</div><div class="ru">rit/hari</div></div>
          <div class="ri" style="grid-column:span 2"><div class="rl">H — Waktu Kerja Dibutuhkan</div><div class="rv2 ro" id="rSH">—</div><div class="ru">jam/hari</div></div>
        </div>
        <div class="note-box" id="scs-status" role="status" aria-live="polite" style="white-space:pre-line"></div>
        <div class="cs">
          <h4>📋 Langkah Perhitungan</h4>
          <div class="sl"><span>C_T = floor(V·r / (c·f))</span><span class="se so" id="sSct">—</span></div>
          <div class="sl"><span>P_SCS = C_T·u_c + (n_p−1)·d_bc</span><span class="se so" id="sSp">—</span></div>
          <div class="sl"><span>h = a + b·x</span><span class="se so" id="sSh">—</span></div>
          <div class="sl"><span>T_SCS = P_SCS + s + h</span><span class="se so" id="sSt">—</span></div>
          <div class="sl"><span>Nd = ceil(C_hari / C_T)</span><span class="se so" id="sSnd">—</span></div>
          <div class="sl"><span>H = [(t₁+t₂) + ΣP_rit + Nd·(s+h)] / (1−w)</span><span class="se so" id="sSH">—</span></div>
        </div>
      </div>
      <div class="card" style="margin-top:14px">
        <div class="ch"><div class="tag to">Grafik</div><h3>Pickup Time vs Jumlah Kontainer</h3></div>
        <canvas id="scs-ch" height="190"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- ====== COMPARE ====== -->
<div id="tab-compare" class="sec">
  <div class="stag">04 — Analisis</div>
  <div class="sh">Perbandingan HCS vs SCS</div>
  <div class="card" style="margin-bottom:14px">
    <div class="ch"><div class="tag tb2">Parameter Bersama</div><h3>Kondisi yang Sama untuk Kedua Sistem</h3></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
      <div class="ig"><div class="il"><span>Jarak haul x</span><span class="vs">x</span></div><input type="range" id="cx" min="1" max="80" step="1" value="20" oninput="uCmp()"><div class="rv"><span>1</span><span class="rc" id="cxv">20km</span><span>80</span></div></div>
      <div class="ig"><div class="il"><span>Konstanta a</span><span class="vs">a</span></div><input type="range" id="ca" min="0.01" max="0.1" step="0.001" value="0.016" oninput="uCmp()"><div class="rv"><span>0.01</span><span class="rc" id="cav">0.016</span><span>0.10</span></div></div>
      <div class="ig"><div class="il"><span>Konstanta b</span><span class="vs">b</span></div><input type="range" id="cb" min="0.005" max="0.05" step="0.001" value="0.011" oninput="uCmp()"><div class="rv"><span>0.005</span><span class="rc" id="cbv">0.011</span><span>0.050</span></div></div>
      <div class="ig"><div class="il"><span>V_d — volume lepas harian (m³)</span></div><input type="number" id="cvd" min="0"  step="any" value="120" oninput="uCmp()"></div>
      <div class="ig"><div class="il"><span>H — jam kerja/hari</span></div><input type="number" id="cH" min="0"  step="any" value="8" oninput="uCmp()"></div>
      <div class="ig"><div class="il"><span>w — off-route factor</span></div><input type="number" id="cw" min="0" max="0.99" step="any" value="0.15" oninput="uCmp()"></div>
      <div class="ig"><div class="il"><span>t₁ — awal rute (jam)</span></div><input type="number" id="ct1" min="0"  step="any" value="0.15" oninput="uCmp()"></div>
      <div class="ig"><div class="il"><span>t₂ — akhir rute (jam)</span></div><input type="number" id="ct2" min="0"  step="any" value="0.15" oninput="uCmp()"></div>
    </div>
    <div class="note-box">Isi pickup dan muatan per ritasi dari sistem masing-masing pada basis volume sebelum pemadatan. Estimasi harian di tab ini konservatif: ritasi terakhir diberi durasi penuh. Grafik per m³ mengukur waktu operasi; biaya dan batas muatan kendaraan belum dibandingkan.</div>
  </div>
  <div class="cgrid">
    <div class="card">
      <div class="ch"><div class="tag tg">HCS</div><h3>Parameter HCS</h3></div>
      <div class="ig"><div class="il"><span>P_HCS (jam/rit)</span></div><input type="number" id="cph" min="0" step="any" value="0.067" oninput="uCmp()"></div>
      <div class="ig"><div class="il"><span>s HCS (jam/rit)</span></div><input type="number" id="csh" min="0" step="any" value="0.053" oninput="uCmp()"></div>
      <div class="ri" style="margin-top:10px"><div class="rl">Total T_HCS</div><div class="rv2" id="cr-hcs">—</div><div class="ru">jam/rit</div></div>
      <div class="ig" style="margin-top:12px"><div class="il"><span>q — Muatan efektif per ritasi (m³ lepas)</span></div><input type="number" id="cq-hcs" min="0" step="any" value="5.4" oninput="uCmp()"></div>
      <div class="rg">
        <div class="ri"><div class="rl">Waktu per volume</div><div class="rv2" id="cr-hcs-unit">—</div><div class="ru">jam/m³</div></div>
        <div class="ri"><div class="rl">Kebutuhan ritasi</div><div class="rv2" id="cr-hcs-nd">—</div><div class="ru">rit/hari</div></div>
        <div class="ri"><div class="rl">Waktu seluruh sampah</div><div class="rv2" id="cr-hcs-H">—</div><div class="ru">jam satu kendaraan/hari</div></div>
        <div class="ri"><div class="rl">Armada minimum</div><div class="rv2" id="cr-hcs-fleet">—</div><div class="ru">kendaraan identik</div></div>
      </div>
    </div>
    <div class="card">
      <div class="ch"><div class="tag to">SCS</div><h3>Parameter SCS</h3></div>
      <div class="ig"><div class="il"><span>P_SCS (jam/rit)</span></div><input type="number" id="cps" min="0" step="any" value="2.89" oninput="uCmp()"></div>
      <div class="ig"><div class="il"><span>s SCS (jam/rit)</span></div><input type="number" id="css2" min="0" step="any" value="0.10" oninput="uCmp()"></div>
      <div class="ri" style="margin-top:10px"><div class="rl">Total T_SCS</div><div class="rv2 ro" id="cr-scs">—</div><div class="ru">jam/rit</div></div>
      <div class="ig" style="margin-top:12px"><div class="il"><span>q — Muatan efektif per ritasi (m³ lepas)</span></div><input type="number" id="cq-scs" min="0" step="any" value="24.75" oninput="uCmp()"></div>
      <div class="rg">
        <div class="ri"><div class="rl">Waktu per volume</div><div class="rv2 ro" id="cr-scs-unit">—</div><div class="ru">jam/m³</div></div>
        <div class="ri"><div class="rl">Kebutuhan ritasi</div><div class="rv2 ro" id="cr-scs-nd">—</div><div class="ru">rit/hari</div></div>
        <div class="ri"><div class="rl">Waktu seluruh sampah</div><div class="rv2 ro" id="cr-scs-H">—</div><div class="ru">jam satu kendaraan/hari</div></div>
        <div class="ri"><div class="rl">Armada minimum</div><div class="rv2 ro" id="cr-scs-fleet">—</div><div class="ru">kendaraan identik</div></div>
      </div>
    </div>
  </div>
  <div class="card" style="margin-top:14px">
    <div class="ch"><div class="tag tb2">Visualisasi</div><h3>Breakdown & Tren Perbandingan</h3></div>
    <div class="note-box" id="compare-status" role="status" aria-live="polite"></div>
    <div id="cmp-bd"></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:14px">
      <div><p class="ct">Komponen Waktu (jam/rit)</p><canvas id="cmp-bar" height="210"></canvas></div>
      <div><p class="ct">Waktu per m³ vs Jarak Haul</p><canvas id="cmp-line" height="210"></canvas></div>
    </div>
    <div id="cmp-verdict" style="margin-top:14px"></div>
  </div>
</div>

<!-- ====== CALIB ====== -->
<div id="tab-calib" class="sec">
  <div class="stag">05 — Kalibrasi</div>
  <div class="sh">Menentukan Nilai a dan b dari Data Lapangan</div>
  <div class="card" style="margin-bottom:14px">
    <div class="ch"><div class="tag tb2">Tabel Referensi</div><h3>Nilai a dan b Berdasarkan Kecepatan Kendaraan</h3></div>
    <p>Gunakan sebagai estimasi awal bila belum ada data lapangan. Nilai ini berlaku untuk jalan dengan kondisi normal. Baris hijau hanya contoh kecepatan; kesesuaiannya untuk rute lokal perlu dibuktikan melalui survei.</p>
    <div style="overflow-x:auto">
    <table class="speed-table">
      <thead><tr><th>Kecepatan (mil/jam)</th><th>Kecepatan (km/jam)</th><th>a (jam/trip)</th><th>b (jam/mil)</th><th>b (jam/km)</th></tr></thead>
      <tbody>
        <tr><td>55</td><td>88</td><td>0.016</td><td>0.018</td><td>0.011</td></tr>
        <tr><td>45</td><td>72</td><td>0.022</td><td>0.022</td><td>0.014</td></tr>
        <tr class="hl"><td>35</td><td>56</td><td>0.034</td><td>0.029</td><td>0.018</td></tr>
        <tr><td>25</td><td>40</td><td>0.050</td><td>0.040</td><td>0.025</td></tr>
        <tr><td>15</td><td>24</td><td>0.066</td><td>0.067</td><td>0.041</td></tr>
      </tbody>
    </table>
    </div>
    <div class="ibox-warn" style="margin-top:10px">⚠️ Nilai a dan b dari tabel diatas diturunkan dari kondisi luar negeri. Untuk studi lokal Indonesia, gunakan data lapangan dan regresi di bawah ini untuk akurasi yang lebih baik.</div>
  </div>
  <div class="cab">
    <div>
      <div class="card">
        <div class="ch"><div class="tag tb2">Data Lapangan</div><h3>Input Data Survei</h3></div>
        <p style="font-size:11px;color:var(--mut);margin-bottom:10px">Masukkan pasangan data jarak haul (x, km) dan waktu haul terukur (h, jam) dari survei lapangan. Minimal 3 pasangan lengkap dengan variasi jarak. Baris yang belum lengkap diabaikan sebagai satu pasangan. Gunakan jarak dan waktu haul pulang-pergi, tanpa pickup atau waktu di TPA.</p>
        <table class="dt">
          <thead><tr><th>#</th><th>Jarak x (km)</th><th>Waktu h (jam)</th></tr></thead>
          <tbody id="cal-body">
            <tr><td>1</td><td><input type="number" class="cx2" step="0.1" value="12.4" oninput="doReg()"></td><td><input type="number" class="ch2" step="0.01" value="0.46" oninput="doReg()"></td></tr>
            <tr><td>2</td><td><input type="number" class="cx2" step="0.1" value="18.0" oninput="doReg()"></td><td><input type="number" class="ch2" step="0.01" value="0.61" oninput="doReg()"></td></tr>
            <tr><td>3</td><td><input type="number" class="cx2" step="0.1" value="25.5" oninput="doReg()"></td><td><input type="number" class="ch2" step="0.01" value="0.82" oninput="doReg()"></td></tr>
            <tr><td>4</td><td><input type="number" class="cx2" step="0.1" value="10.2" oninput="doReg()"></td><td><input type="number" class="ch2" step="0.01" value="0.40" oninput="doReg()"></td></tr>
            <tr><td>5</td><td><input type="number" class="cx2" step="0.1" value="33.0" oninput="doReg()"></td><td><input type="number" class="ch2" step="0.01" value="1.02" oninput="doReg()"></td></tr>
          </tbody>
        </table>
        <button class="arb" onclick="addCalRow()">+ Tambah Baris Data</button>
        <button class="btn" onclick="doReg()">🔢 Hitung Regresi OLS</button>
      </div>
    </div>
    <div>
      <div class="card">
        <div class="ch"><div class="tag tb2">Hasil Regresi</div><h3>Koefisien a dan b</h3></div>
        <div class="coeff-d">
          <div class="cbox"><div class="cl">a</div><div class="cv" id="ra">—</div><div class="cd">jam/trip (overhead)</div></div>
          <div class="cbox co"><div class="cl co">b</div><div class="cv" id="rb">—</div><div class="cd">jam/km</div></div>
        </div>
        <div class="r2d">
          <div style="font-size:8px;font-family:var(--mono);color:var(--mut);letter-spacing:2px;margin-bottom:5px">R² — GOODNESS OF FIT</div>
          <div style="font-family:var(--mono);font-size:24px;font-weight:700" id="rr2">—</div>
          <div style="font-size:9px;color:var(--mut);margin-top:3px">1.00 = fit sempurna</div>
        </div>
        <div class="interp" id="rinterp">Masukkan data dan klik tombol untuk melihat interpretasi.</div>
        <div class="cw" style="margin-top:12px">
          <p class="ct">Scatter Plot &amp; Garis Regresi</p>
          <canvas id="cal-ch" height="210"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ====== CASES ====== -->
<div id="tab-cases" class="sec">
  <div class="stag">06 — Contoh Kasus</div>
  <div class="sh">Soal &amp; Penyelesaian Lengkap</div>

  <!-- KASUS 1: HCS — dari slide latihan soal -->
  <div class="card">
    <div class="ch"><div class="tag tg">Kasus 1 — HCS</div><h3>Latihan Soal: Pengangkutan HCS (Tilt-Frame + Kompaksi)</h3></div>
    <div class="ibox-warn">📌 <strong>Soal (dari slide kuliah):</strong> Untuk mengangkut sampah dari beberapa lokasi kontainer di suatu daerah digunakan sistem HCS. Data yang diberikan:
      <br>• T₁ = 15' &nbsp;|&nbsp; T₂ = 20' &nbsp;|&nbsp; W = 0,15
      <br>• (p_c + u_c) = 0,4 jam/ritasi
      <br>• Waktu rata-rata bergerak dari kontainer ke kontainer = d_bc = 6' = 0,1 jam
      <br>• Tentukan jumlah ritasi/hari bila jam kerja = 8 jam
      <br><br>⚠️ <strong>Asumsi dari tabel referensi (kecepatan 55 mil/jam):</strong> a = 0,016 jam/rit &nbsp;|&nbsp; b = 0,018 jam/<strong>mil</strong> &nbsp;|&nbsp; s = 0,133 jam/rit (tilt-frame kompaksi) &nbsp;|&nbsp; x = 31 <strong>mil</strong>
    </div>

    <div style="background:rgba(126,184,247,.07);border:1px solid rgba(126,184,247,.2);border-radius:8px;padding:12px 16px;margin:10px 0;font-size:12px;color:#aacce8">
      💡 <strong>Catatan satuan penting:</strong> T₁ = 15 menit = 15/60 = <strong>0,25 jam</strong> &nbsp;|&nbsp; T₂ = 20 menit = 20/60 = <strong>0,333 jam</strong> &nbsp;|&nbsp; d_bc = 6 menit = <strong>0,1 jam</strong>. Nilai b = 0,018 jam/<strong>mil</strong>, sehingga x harus dalam <strong>mil</strong>.
    </div>

    <div class="case-grid">
      <div>
        <div class="cinput">
          <h4>📋 Data yang Diberikan</h4>
          <div class="ci"><span class="clb">T₁ (garasi→kontainer 1)</span><span class="cvl">15' = 0,25 jam</span></div>
          <div class="ci"><span class="clb">T₂ (kontainer terakhir→garasi)</span><span class="cvl">20' = 0,333 jam</span></div>
          <div class="ci"><span class="clb">W (off-route factor)</span><span class="cvl">0,15</span></div>
          <div class="ci"><span class="clb">(p_c + u_c)</span><span class="cvl">0,4 jam/rit</span></div>
          <div class="ci"><span class="clb">d_bc</span><span class="cvl">6' = 0,1 jam</span></div>
          <div class="ci"><span class="clb">H (jam kerja)</span><span class="cvl">8 jam/hari</span></div>
          <div class="ci"><span class="clb">a (asumsi, 55 mph)</span><span class="cvl">0,016 jam/rit</span></div>
          <div class="ci"><span class="clb">b (asumsi, 55 mph)</span><span class="cvl">0,018 jam/mil</span></div>
          <div class="ci"><span class="clb">s (tilt-frame + kompaksi)</span><span class="cvl">0,133 jam/rit</span></div>
          <div class="ci"><span class="clb">x (jarak haul)</span><span class="cvl">31 mil</span></div>
        </div>
      </div>
      <div>
        <div class="abox">
          <div class="atitle">✅ Penyelesaian Langkah Demi Langkah</div>
          <div class="step-item"><div class="step-num">a</div><div class="step-content"><strong>P_HCS = (p_c + u_c) + d_bc</strong><br>P_HCS = 0,4 + 0,1 = <strong style="color:var(--G)">0,5 jam/rit</strong></div></div>
          <div class="step-item"><div class="step-num">b</div><div class="step-content"><strong>h = a + b·x &nbsp;(x dalam mil, b dalam jam/mil)</strong><br>h = 0,016 + (0,018 × 31) = 0,016 + 0,558 = <strong style="color:var(--G)">0,574 jam/rit</strong></div></div>
          <div class="step-item"><div class="step-num">b</div><div class="step-content"><strong>T_HCS = P_HCS + s + h</strong><br>T_HCS = 0,5 + 0,133 + 0,574<br>= <strong style="color:var(--G)">1,207 ≈ 1,21 jam/rit</strong></div></div>
          <div class="step-item"><div class="step-num">c</div><div class="step-content"><strong>Nd = [H·(1−w) − (t₁+t₂)] / T_HCS</strong><br>= [8·(1−0,15) − (15/60+20/60)] / 1,207<br>= 6,216667 / 1,207<br>= <strong style="color:var(--G)">5,15 → diambil 5 rit/hari</strong></div></div>
          <div class="step-item"><div class="step-num">✓</div><div class="step-content"><strong>Verifikasi waktu aktual yang diperlukan:</strong><br>H_aktual = [(t₁+t₂) + Nd·T_HCS] / (1−w)<br>= [(15/60+20/60) + 5 × 1,207] / 0,85<br>= 6,618333 / 0,85 = <strong style="color:var(--G)">≈ 7,79 jam</strong></div></div>
        </div>
      </div>
    </div>

    <div style="background:rgba(0,212,160,.06);border:1px solid rgba(0,212,160,.2);border-radius:8px;padding:14px;margin-top:12px;font-size:12px;color:#9db8d0;line-height:1.7">
      🔍 <strong style="color:var(--G)">Cara memasukkan soal ini ke simulator HCS:</strong><br>
      Klik tombol di bawah untuk memuat parameter waktu ke tab HCS. Soal tidak memberikan V_d, c, dan f; hasil kebutuhan volume memakai input tambahan yang sedang terisi, sedangkan jawaban soal adalah kapasitas 5 rit/hari.
      <br><br>
      <button onclick="loadSoalLatihan()" style="padding:10px 20px;background:linear-gradient(135deg,var(--G),#00a880);border:none;border-radius:6px;color:#000;font-family:var(--mono);font-size:10px;font-weight:700;cursor:pointer;letter-spacing:1px;text-transform:uppercase">🚀 Muat Soal Latihan ke HCS Simulator</button>
    </div>
  </div>

  <!-- KASUS 2: SCS -->
  <div class="card">
    <div class="ch"><div class="tag to">Kasus 2 — SCS</div><h3>Pengangkutan SCS dengan Compactor Truck</h3></div>
    <div class="ibox-warn" style="border-color:rgba(255,120,73,.3);color:var(--O)">📌 <strong>Soal:</strong> Wilayah perumahan menghasilkan <strong>120 m³ sampah/hari</strong>. Digunakan compactor truck (SCS): kapasitas V = 10 m³, rasio kompaksi r = 2,5, volume kontainer c = 0,5 m³, faktor keterisian f = 0,9. Waktu kuras u_c = 0,05 jam, jumlah lokasi/rit n_p = 8, waktu antar lokasi d_bc = 0,02 jam. At-site s = 0,10 jam, jarak x = 25 km, kecepatan rata-rata <strong>40 km/jam</strong> sehingga <strong>a = 0,050 jam/rit, b = 0,025 jam/km</strong>. w = 0,15, t₁ = t₂ = 0,15 jam. <em>Hitung semua parameter operasional!</em></div>
    <div class="case-grid">
      <div>
        <div class="cinput" style="border-color:rgba(255,120,73,.2)">
          <h4 style="color:var(--O)">📋 Data yang Diberikan</h4>
          <div class="ci"><span class="clb">Volume sampah V_d</span><span class="cvl co">120 m³/hari</span></div>
          <div class="ci"><span class="clb">Volume truk V</span><span class="cvl co">10 m³</span></div>
          <div class="ci"><span class="clb">Rasio kompaksi r</span><span class="cvl co">2,5</span></div>
          <div class="ci"><span class="clb">Volume kontainer c</span><span class="cvl co">0,5 m³</span></div>
          <div class="ci"><span class="clb">Faktor keterisian f</span><span class="cvl co">0,9</span></div>
          <div class="ci"><span class="clb">u_c (kuras/kontainer)</span><span class="cvl co">0,05 jam</span></div>
          <div class="ci"><span class="clb">n_p (lokasi/rit)</span><span class="cvl co">8</span></div>
          <div class="ci"><span class="clb">d_bc (antar lokasi)</span><span class="cvl co">0,02 jam</span></div>
          <div class="ci"><span class="clb">At-site s</span><span class="cvl co">0,10 jam</span></div>
          <div class="ci"><span class="clb">Jarak haul x</span><span class="cvl co">25 km</span></div>
          <div class="ci"><span class="clb">Kecepatan rata-rata</span><span class="cvl co">40 km/jam</span></div>
          <div class="ci"><span class="clb">a, b</span><span class="cvl co">0,050 | 0,025</span></div>
          <div class="ci"><span class="clb">w, t₁, t₂</span><span class="cvl co">0,15 | 0,15 | 0,15</span></div>
        </div>
      </div>
      <div>
        <div class="abox ao">
          <div class="atitle">✅ Penyelesaian Lengkap</div>
          <div id="case-scs-out"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- KASUS 3: KALKULATOR INTERAKTIF -->
  <div class="card">
    <div class="ch"><div class="tag ty">Kasus 3 — Kalkulator Mandiri</div><h3>Selesaikan Kasus HCS Anda Sendiri</h3></div>
    <p>Ubah nilai-nilai di bawah untuk menghitung kasus Anda. Hasil dihitung otomatis secara real-time.</p>
    <div class="case-grid">
      <div>
        <div class="cinput" style="border-color:rgba(240,192,64,.2)">
          <h4 style="color:var(--Y)">Input Kasus Mandiri (HCS)</h4>
          <div class="ci"><span class="clb">p_c (jam)</span><input type="number" id="k_pc" value="0.067" step="0.001" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">u_c (jam)</span><input type="number" id="k_uc" value="0" step="0.001" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">d_bc (jam)</span><input type="number" id="k_dbc" value="0" step="0.001" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">s (jam)</span><input type="number" id="k_s" value="0.053" step="0.001" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">x — jarak pulang-pergi (km)</span><input type="number" id="k_x" value="16" step="1" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">a (jam/rit)</span><input type="number" id="k_a" value="0.050" step="0.001" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">b (jam/km)</span><input type="number" id="k_b" value="0.025" step="0.001" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">H — waktu kerja (jam)</span><input type="number" id="k_H" value="8" step="0.5" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">w (off-route)</span><input type="number" id="k_w" value="0.15" step="0.01" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0" max="0.99"></div>
          <div class="ci"><span class="clb">t₁ (jam)</span><input type="number" id="k_t1" value="0.1" step="0.01" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">t₂ (jam)</span><input type="number" id="k_t2" value="0.1" step="0.01" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">V_d (m³/hari)</span><input type="number" id="k_vd" value="45" step="5" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">c — kontainer (m³)</span><input type="number" id="k_c" value="6" step="0.5" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0"></div>
          <div class="ci"><span class="clb">f (faktor penggunaan)</span><input type="number" id="k_f" value="0.9" step="0.05" style="width:85px;text-align:right;color:var(--Y);border-color:rgba(240,192,64,.2)" oninput="kHCS()" min="0" max="1"></div>
        </div>
      </div>
      <div>
        <div class="abox" style="background:rgba(240,192,64,.05);border-color:rgba(240,192,64,.2)">
          <div class="atitle" style="color:var(--Y)">📊 Hasil Perhitungan Otomatis</div>
          <pre id="k-out" style="font-family:var(--mono);font-size:11px;color:#9db8d0;line-height:1.9;white-space:pre-wrap"></pre>
        </div>
      </div>
    </div>
  </div>
</div>

</div><!-- /wrap -->
<footer><div class="wrap">WasteRoute Modul HCS &amp; SCS v2.0 · Berdasarkan Tchobanoglous et al. · Kuliah Pemindahan &amp; Transportasi Sampah · Untuk Keperluan Pembelajaran<br><span style="color:var(--G);font-weight:700">© ReLoop 2026</span></div></footer>

<script src="js/calculations.js"></script>
<script src="js/charts.js"></script>
<script src="js/ui.js"></script>
<script src="js/hcs.js"></script>
<script src="js/scs.js"></script>
<script src="js/compare.js"></script>
<script src="js/calib.js"></script>
<script src="js/cases.js"></script>
<script src="js/app.js"></script>
</body>
</html>
