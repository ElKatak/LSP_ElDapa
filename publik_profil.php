<?php
include '../koneksi.php';
$p = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM profil_sekolah LIMIT 1")) ?: [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Profil Sekolah — SMK Ganesha Tama Boyolali</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
<style>
*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
:root {
  --navy:#0B1F3A; --blue:#1255A4; --blue2:#1A6FCC;
  --gold:#F0A500; --white:#FFFFFF; --off:#F0F6FF; --muted:#6B8BB5; --text:#1A2E4A;
}
html, body { font-family:'Plus Jakarta Sans',sans-serif; background:var(--off); overflow-x:hidden; }
.topbar { background:var(--navy); color:rgba(255,255,255,.8); font-size:12px; font-weight:500; padding:8px 0; }
.topbar-inner { max-width:1200px; margin:0 auto; padding:0 24px; display:flex; align-items:center; justify-content:center; gap:32px; flex-wrap:wrap; }
.topbar-item { display:flex; align-items:center; gap:6px; }
.topbar-item i { color:var(--gold); font-size:12px; }
.header { background:var(--white); border-bottom:3px solid var(--blue); padding:0 24px; position:sticky; top:0; z-index:999; box-shadow:0 2px 20px rgba(18,85,164,.12); }
.header-inner { max-width:1200px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; height:72px; }
.brand { display:flex; align-items:center; gap:12px; text-decoration:none; }
.brand-logo { width:46px; height:46px; }
.brand-name { font-weight:800; font-size:15px; color:var(--navy); line-height:1.2; }
.brand-sub { font-size:10px; color:var(--blue); font-weight:600; letter-spacing:.6px; text-transform:uppercase; }
.nav-menu { display:flex; align-items:center; gap:4px; }
.nav-menu a { padding:7px 14px; border-radius:8px; font-size:13.5px; font-weight:600; color:var(--text); text-decoration:none; transition:all .15s; }
.nav-menu a:hover { background:#EFF6FF; color:var(--blue); }
.nav-menu a.active { background:var(--blue); color:#fff; }
.btn-login-sm { padding:8px 18px !important; background:linear-gradient(135deg,var(--blue),var(--blue2)) !important; color:#fff !important; border-radius:9px !important; }
.page-hero { background:linear-gradient(135deg,var(--navy) 0%,#1A5FA8 100%); padding:60px 24px 50px; text-align:center; position:relative; overflow:hidden; }
.page-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(255,255,255,.06) 0%,transparent 70%); }
.page-hero h1 { font-family:'Playfair Display',serif; font-size:clamp(28px,5vw,48px); color:#fff; font-weight:900; position:relative; }
.page-hero p { color:rgba(255,255,255,.72); font-size:14px; margin-top:10px; position:relative; }
.breadcrumb-bar { display:flex; align-items:center; justify-content:center; gap:8px; margin-top:16px; font-size:12.5px; position:relative; }
.breadcrumb-bar a { color:rgba(255,255,255,.7); text-decoration:none; }
.breadcrumb-bar span { color:rgba(255,255,255,.4); }
.breadcrumb-bar .cur { color:var(--gold); font-weight:600; }
.container { max-width:1100px; margin:0 auto; padding:0 24px; }
.section { padding:60px 0; }

/* IDENTITY CARD */
.identity-card { background:#fff; border-radius:24px; box-shadow:0 4px 28px rgba(18,85,164,.1); overflow:hidden; margin-bottom:32px; }
.identity-header { background:linear-gradient(135deg,var(--navy),var(--blue2)); padding:40px 48px; display:flex; align-items:center; gap:28px; }
.identity-logo { width:110px; height:110px; border-radius:50%; border:5px solid rgba(255,255,255,.3); object-fit:cover; background:#fff; flex-shrink:0; display:flex; align-items:center; justify-content:center; overflow:hidden; }
.identity-logo img { width:100%; height:100%; object-fit:cover; }
.identity-logo svg { width:80px; height:80px; }
.identity-title h2 { font-family:'Playfair Display',serif; font-size:clamp(20px,3vw,30px); color:#fff; font-weight:900; line-height:1.2; }
.identity-title p { color:rgba(255,255,255,.75); font-size:13.5px; margin-top:6px; display:flex; align-items:center; gap:6px; }
.npsn-badge { background:rgba(255,255,255,.18); color:#fff; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:700; letter-spacing:.5px; display:inline-block; margin-top:10px; }
.identity-body { padding:36px 48px; display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.info-tile { background:#F8FAFF; border-radius:12px; padding:16px 20px; border:1px solid #E8F0FB; }
.info-tile-label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.5px; margin-bottom:5px; display:flex; align-items:center; gap:6px; }
.info-tile-label i { font-size:13px; color:var(--blue); }
.info-tile-value { font-size:14.5px; font-weight:600; color:var(--navy); }

/* SECTION CARDS */
.section-card { background:#fff; border-radius:20px; box-shadow:0 3px 18px rgba(18,85,164,.08); overflow:hidden; margin-bottom:28px; }
.section-card-header { background:linear-gradient(90deg,#EFF6FF,#DBEAFE); padding:18px 28px; display:flex; align-items:center; gap:12px; border-bottom:2px solid #DBEAFE; }
.section-card-header i { font-size:20px; color:var(--blue); }
.section-card-header h3 { font-family:'Playfair Display',serif; font-size:18px; color:var(--navy); font-weight:700; }
.section-card-body { padding:28px 32px; }
.prose { font-size:15px; color:var(--text); line-height:1.85; }
.prose-list { list-style:none; display:flex; flex-direction:column; gap:10px; }
.prose-list li { display:flex; gap:10px; align-items:flex-start; font-size:14.5px; color:var(--text); line-height:1.6; }
.prose-list li::before { content:''; width:8px; height:8px; border-radius:50%; background:var(--gold); flex-shrink:0; margin-top:6px; }

/* CONTACT GRID */
.contact-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:16px; }
.contact-tile { background:#F8FAFF; border-radius:14px; padding:18px 20px; border:1px solid #E8F0FB; display:flex; align-items:center; gap:14px; }
.contact-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
.contact-label { font-size:11px; color:var(--muted); font-weight:600; text-transform:uppercase; letter-spacing:.4px; }
.contact-value { font-size:14px; color:var(--navy); font-weight:600; word-break:break-all; }

.empty-state { text-align:center; padding:80px 24px; color:var(--muted); }
.empty-state i { font-size:52px; opacity:.25; display:block; margin-bottom:16px; }
.footer { background:var(--navy); color:rgba(255,255,255,.6); text-align:center; font-size:12px; padding:20px; }
.footer strong { color:var(--gold); }

@media(max-width:768px) {
  .identity-header { flex-direction:column; text-align:center; padding:28px 24px; }
  .identity-body { grid-template-columns:1fr; padding:24px 20px; }
  .nav-menu { display:none; }
  .section-card-body { padding:20px 18px; }
}
</style>
</head>
<body>

<div class="topbar">
  <div class="topbar-inner">
    <span class="topbar-item"><i class="bi bi-geo-alt-fill"></i>Jl. Perintis Kemerdekaan, Boyolali 57316</span>
    <span class="topbar-item"><i class="bi bi-telephone-fill"></i>(0276) 321579</span>
    <span class="topbar-item"><i class="bi bi-envelope-fill"></i>info@ganeshatama-byi.sch.id</span>
  </div>
</div>

<header class="header">
  <div class="header-inner">
    <a href="index.php" class="brand">
      <svg class="brand-logo" viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg">
        <circle cx="27" cy="27" r="27" fill="#0B1F3A"/>
        <circle cx="27" cy="27" r="23" fill="#1255A4"/>
        <polygon points="27,8 32,20 45,20 35,28 39,40 27,32 15,40 19,28 9,20 22,20" fill="#F0A500" opacity=".95"/>
        <circle cx="27" cy="27" r="7" fill="white" opacity=".9"/>
        <text x="27" y="31" text-anchor="middle" font-size="7" font-weight="bold" fill="#0B1F3A" font-family="sans-serif">GT</text>
      </svg>
      <div>
        <div class="brand-name">SMK Ganesha Tama</div>
        <div class="brand-sub">Boyolali</div>
      </div>
    </a>
    <nav class="nav-menu">
      <a href="index.php">Beranda</a>
      <a href="publik_berita.php">Berita</a>
      <a href="publik_galeri.php">Galeri</a>
      <a href="publik_guru.php">Guru</a>
      <a href="publik_profil.php" class="active">Profil Sekolah</a>
      <a href="login.php" class="btn-login-sm"><i class="bi bi-box-arrow-in-right"></i>Login Admin</a>
    </nav>
  </div>
</header>

<div class="page-hero">
  <h1>Profil Sekolah</h1>
  <p>Identitas, visi, misi, dan informasi lengkap sekolah</p>
  <div class="breadcrumb-bar">
    <a href="index.php"><i class="bi bi-house-fill"></i> Beranda</a>
    <span>/</span><span class="cur">Profil Sekolah</span>
  </div>
</div>

<div class="container">
  <div class="section">

    <?php if (empty($p)): ?>
      <div class="empty-state">
        <i class="bi bi-building-x"></i>
        <p>Profil sekolah belum diisi.</p>
      </div>
    <?php else: ?>

    <!-- IDENTITY -->
    <div class="identity-card">
      <div class="identity-header">
        <div class="identity-logo">
          <?php if (!empty($p['logo'])): ?>
            <img src="admin/upload/<?= htmlspecialchars($p['logo']) ?>" alt="Logo">
          <?php else: ?>
            <svg viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg">
              <circle cx="27" cy="27" r="27" fill="#0B1F3A"/>
              <circle cx="27" cy="27" r="23" fill="#1255A4"/>
              <polygon points="27,8 32,20 45,20 35,28 39,40 27,32 15,40 19,28 9,20 22,20" fill="#F0A500" opacity=".95"/>
              <circle cx="27" cy="27" r="7" fill="white" opacity=".9"/>
            </svg>
          <?php endif; ?>
        </div>
        <div class="identity-title">
          <h2><?= htmlspecialchars($p['nama_sekolah'] ?? '') ?></h2>
          <p><i class="bi bi-geo-alt-fill"></i>
            <?= htmlspecialchars(implode(', ', array_filter([$p['alamat']??'', $p['desa']??'', $p['kecamatan']??'', $p['kabupaten']??'', $p['provinsi']??'']))) ?>
          </p>
          <?php if (!empty($p['npsn'])): ?>
            <div class="npsn-badge">NPSN: <?= htmlspecialchars($p['npsn']) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <div class="identity-body">
        <?php
        $tiles = [
          ['bi-person-badge-fill','#EFF6FF','#1255A4','Kepala Sekolah', $p['kepala_sekolah']??'-'],
          ['bi-telephone-fill','#F0FDF4','#16A34A','Telepon', $p['telepon']??'-'],
          ['bi-envelope-fill','#FFF7ED','#EA580C','Email', $p['email']??'-'],
          ['bi-globe','#F5F3FF','#7C3AED','Website', $p['website']??'-'],
        ];
        foreach ($tiles as [$icon, $bg, $ic, $lbl, $val]):
        ?>
        <div class="info-tile">
          <div class="info-tile-label" style="color:<?= $ic ?>;"><i class="bi <?= $icon ?>"></i><?= $lbl ?></div>
          <div class="info-tile-value"><?= htmlspecialchars($val) ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- DESKRIPSI -->
    <?php if (!empty($p['deskripsi'])): ?>
    <div class="section-card">
      <div class="section-card-header">
        <i class="bi bi-building-fill"></i>
        <h3>Tentang Sekolah</h3>
      </div>
      <div class="section-card-body">
        <div class="prose"><?= nl2br(htmlspecialchars($p['deskripsi'])) ?></div>
      </div>
    </div>
    <?php endif; ?>

    <!-- VISI -->
    <?php if (!empty($p['visi'])): ?>
    <div class="section-card">
      <div class="section-card-header">
        <i class="bi bi-eye-fill"></i>
        <h3>Visi Sekolah</h3>
      </div>
      <div class="section-card-body">
        <div class="prose" style="font-style:italic;font-size:16px;color:var(--blue);font-weight:600;border-left:4px solid var(--gold);padding-left:20px;">
          "<?= htmlspecialchars($p['visi']) ?>"
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- MISI -->
    <?php if (!empty($p['misi'])): ?>
    <div class="section-card">
      <div class="section-card-header">
        <i class="bi bi-rocket-takeoff-fill"></i>
        <h3>Misi Sekolah</h3>
      </div>
      <div class="section-card-body">
        <?php
        $misi_lines = array_filter(array_map('trim', preg_split('/[\r\n]+/', $p['misi'])));
        if (count($misi_lines) > 1): ?>
          <ul class="prose-list">
            <?php foreach ($misi_lines as $line): ?>
              <li><?= htmlspecialchars($line) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="prose"><?= nl2br(htmlspecialchars($p['misi'])) ?></div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- KONTAK -->
    <div class="section-card">
      <div class="section-card-header">
        <i class="bi bi-geo-alt-fill"></i>
        <h3>Informasi Kontak & Lokasi</h3>
      </div>
      <div class="section-card-body">
        <div class="contact-grid">
          <?php
          $contacts = [
            ['bi-telephone-fill','#EFF6FF','#1255A4','Telepon',$p['telepon']??'-'],
            ['bi-envelope-fill','#FFF7ED','#EA580C','Email',$p['email']??'-'],
            ['bi-globe','#F5F3FF','#7C3AED','Website',$p['website']??'-'],
            ['bi-house-fill','#F0FDF4','#16A34A','Desa',$p['desa']??'-'],
            ['bi-map-fill','#FFFBEB','#B45309','Kecamatan',$p['kecamatan']??'-'],
            ['bi-geo-fill','#FEF2F2','#DC2626','Kabupaten',$p['kabupaten']??'-'],
          ];
          foreach ($contacts as [$icon, $bg, $ic, $lbl, $val]):
          ?>
          <div class="contact-tile">
            <div class="contact-icon" style="background:<?= $bg ?>;"><i class="bi <?= $icon ?>" style="color:<?= $ic ?>;"></i></div>
            <div><div class="contact-label"><?= $lbl ?></div><div class="contact-value"><?= htmlspecialchars($val) ?></div></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <?php endif; ?>
  </div>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> <strong>SMK Ganesha Tama Boyolali</strong> — Berkarakter, Inovatif, Berwawasan Lingkungan
</div>
</body>
</html>