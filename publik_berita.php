<?php
include '../koneksi.php';

$limit   = 9;
$halaman = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset  = ($halaman - 1) * $limit;
$total   = (int)mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM berita"))['c'];
$totalHalaman = ceil($total / $limit);
$data    = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal DESC LIMIT $limit OFFSET $offset");

// Detail berita jika ada ?id=
$detail = null;
if (isset($_GET['id'])) {
    $bid = (int)$_GET['id'];
    $detail = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM berita WHERE id='$bid' LIMIT 1"));
}

$profil = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_sekolah, logo FROM profil_sekolah LIMIT 1")) ?: [];
$nama_sekolah = $profil['nama_sekolah'] ?? 'SMK Ganesha Tama Boyolali';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Berita — <?= htmlspecialchars($nama_sekolah) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --navy:  #0B1F3A; --blue: #1255A4; --blue2: #1A6FCC;
  --sky:   #3B9FE8; --gold: #F0A500; --white: #FFFFFF;
  --off:   #F0F6FF; --muted: #6B8BB5; --text: #1A2E4A;
}
html, body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--off); overflow-x: hidden; }

/* TOPBAR */
.topbar { background: var(--navy); color: rgba(255,255,255,.8); font-size: 12px; font-weight: 500; padding: 8px 0; }
.topbar-inner { max-width: 1200px; margin: 0 auto; padding: 0 24px; display: flex; align-items: center; justify-content: center; gap: 32px; flex-wrap: wrap; }
.topbar-item { display: flex; align-items: center; gap: 6px; }
.topbar-item i { color: var(--gold); font-size: 12px; }

/* HEADER */
.header { background: var(--white); border-bottom: 3px solid var(--blue); padding: 0 24px; position: sticky; top: 0; z-index: 999; box-shadow: 0 2px 20px rgba(18,85,164,.12); }
.header-inner { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; height: 72px; }
.brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.brand-logo { width: 46px; height: 46px; }
.brand-name { font-weight: 800; font-size: 15px; color: var(--navy); line-height: 1.2; }
.brand-sub { font-size: 10px; color: var(--blue); font-weight: 600; letter-spacing: .6px; text-transform: uppercase; }

/* NAV */
.nav-menu { display: flex; align-items: center; gap: 4px; }
.nav-menu a { padding: 7px 14px; border-radius: 8px; font-size: 13.5px; font-weight: 600; color: var(--text); text-decoration: none; transition: all .15s; }
.nav-menu a:hover { background: #EFF6FF; color: var(--blue); }
.nav-menu a.active { background: var(--blue); color: #fff; }
.btn-login-sm { padding: 8px 18px !important; background: linear-gradient(135deg, var(--blue), var(--blue2)) !important; color: #fff !important; border-radius: 9px !important; box-shadow: 0 3px 10px rgba(18,85,164,.28); }
.btn-login-sm:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(18,85,164,.38) !important; }

/* HERO BANNER */
.page-hero { background: linear-gradient(135deg, var(--navy) 0%, var(--blue) 100%); padding: 60px 24px 50px; text-align: center; position: relative; overflow: hidden; }
.page-hero::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(255,255,255,.06) 0%, transparent 70%); }
.page-hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(28px, 5vw, 48px); color: #fff; font-weight: 900; position: relative; }
.page-hero p { color: rgba(255,255,255,.72); font-size: 14px; margin-top: 10px; position: relative; }
.breadcrumb-bar { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 16px; font-size: 12.5px; position: relative; }
.breadcrumb-bar a { color: rgba(255,255,255,.7); text-decoration: none; }
.breadcrumb-bar a:hover { color: #fff; }
.breadcrumb-bar span { color: rgba(255,255,255,.4); }
.breadcrumb-bar .cur { color: var(--gold); font-weight: 600; }

/* CONTAINER */
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.section { padding: 60px 0; }

/* CARDS */
.news-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 28px; }
.news-card { background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 12px rgba(18,85,164,.07); border: 1px solid rgba(18,85,164,.07); transition: transform .2s, box-shadow .2s; display: flex; flex-direction: column; }
.news-card:hover { transform: translateY(-5px); box-shadow: 0 12px 36px rgba(18,85,164,.14); }
.news-card-img { width: 100%; height: 210px; object-fit: cover; background: #E8F0F8; display: flex; align-items: center; justify-content: center; }
.news-card-img img { width: 100%; height: 210px; object-fit: cover; display: block; }
.news-card-img-placeholder { width: 100%; height: 210px; background: linear-gradient(135deg, #DBEAFE, #BFDBFE); display: flex; align-items: center; justify-content: center; }
.news-card-body { padding: 22px 24px; flex: 1; display: flex; flex-direction: column; }
.news-meta { display: flex; align-items: center; gap: 12px; font-size: 11.5px; color: var(--muted); margin-bottom: 10px; }
.news-meta i { font-size: 11px; }
.news-card-title { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; color: var(--navy); line-height: 1.4; margin-bottom: 10px; }
.news-card-excerpt { font-size: 13.5px; color: var(--muted); line-height: 1.7; flex: 1; }
.news-card-footer { padding: 16px 24px; border-top: 1px solid #F0F6FF; }
.btn-read { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; background: linear-gradient(135deg, var(--blue), var(--blue2)); color: #fff; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; transition: all .15s; }
.btn-read:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(18,85,164,.3); }

/* DETAIL */
.detail-card { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(18,85,164,.09); }
.detail-hero-img { width: 100%; height: 400px; object-fit: cover; display: block; }
.detail-body { padding: 40px 48px; }
.detail-title { font-family: 'Playfair Display', serif; font-size: clamp(22px, 4vw, 36px); font-weight: 900; color: var(--navy); line-height: 1.3; margin-bottom: 16px; }
.detail-meta { display: flex; gap: 20px; flex-wrap: wrap; font-size: 13px; color: var(--muted); margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid #EFF6FF; }
.detail-meta span { display: flex; align-items: center; gap: 6px; }
.detail-content { font-size: 15.5px; color: var(--text); line-height: 1.9; }
.btn-back { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #EFF6FF; color: var(--blue); border-radius: 10px; font-size: 13.5px; font-weight: 600; text-decoration: none; transition: all .15s; }
.btn-back:hover { background: var(--blue); color: #fff; }

/* EMPTY */
.empty-state { text-align: center; padding: 80px 24px; color: var(--muted); }
.empty-state i { font-size: 52px; opacity: .25; display: block; margin-bottom: 16px; }
.empty-state p { font-size: 15px; }

/* PAGINATION */
.pagination-wrap { display: flex; justify-content: center; gap: 8px; margin-top: 48px; flex-wrap: wrap; }
.page-btn { padding: 8px 14px; border-radius: 9px; background: #fff; border: 1.5px solid #DBEAFE; color: var(--blue); font-size: 13px; font-weight: 600; text-decoration: none; transition: all .15s; }
.page-btn:hover, .page-btn.active { background: var(--blue); color: #fff; border-color: var(--blue); }

/* FOOTER */
.footer { background: var(--navy); color: rgba(255,255,255,.6); text-align: center; font-size: 12px; padding: 20px; margin-top: 0; }
.footer strong { color: var(--gold); }

@media(max-width:640px) {
  .nav-menu { display: none; }
  .news-grid { grid-template-columns: 1fr; }
  .detail-body { padding: 24px 20px; }
}
</style>
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
  <div class="topbar-inner">
    <span class="topbar-item"><i class="bi bi-geo-alt-fill"></i>Jl. Perintis Kemerdekaan, Boyolali 57316</span>
    <span class="topbar-item"><i class="bi bi-telephone-fill"></i>(0276) 321579</span>
    <span class="topbar-item"><i class="bi bi-envelope-fill"></i>info@ganeshatama-byi.sch.id</span>
  </div>
</div>

<!-- HEADER -->
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
      <a href="publik_berita.php" class="active">Berita</a>
      <a href="publik_galeri.php">Galeri</a>
      <a href="publik_guru.php">Guru</a>
      <a href="publik_profil.php">Profil Sekolah</a>
      <a href="login.php" class="btn-login-sm"><i class="bi bi-box-arrow-in-right"></i>Login Admin</a>
    </nav>
  </div>
</header>

<!-- HERO -->
<div class="page-hero">
  <h1><?= $detail ? htmlspecialchars($detail['judul']) : 'Berita Sekolah' ?></h1>
  <p><?= $detail ? 'Detail berita lengkap' : 'Informasi terkini dari lingkungan sekolah' ?></p>
  <div class="breadcrumb-bar">
    <a href="index.php"><i class="bi bi-house-fill"></i> Beranda</a>
    <span>/</span>
    <a href="publik_berita.php">Berita</a>
    <?php if ($detail): ?>
      <span>/</span><span class="cur">Detail</span>
    <?php endif; ?>
  </div>
</div>

<!-- CONTENT -->
<div class="container">
  <div class="section">

    <?php if ($detail): ?>
    <!-- ─── DETAIL ─── -->
    <div style="margin-bottom:28px;">
      <a href="publik_berita.php" class="btn-back"><i class="bi bi-arrow-left"></i>Kembali ke Daftar Berita</a>
    </div>
    <div class="detail-card">
      <?php if (!empty($detail['gambar'])): ?>
        <img src="admin/upload/<?= htmlspecialchars($detail['gambar']) ?>" class="detail-hero-img" alt="<?= htmlspecialchars($detail['judul']) ?>">
      <?php endif; ?>
      <div class="detail-body">
        <h1 class="detail-title"><?= htmlspecialchars($detail['judul']) ?></h1>
        <div class="detail-meta">
          <span><i class="bi bi-person-fill"></i><?= htmlspecialchars($detail['penulis']) ?></span>
          <span><i class="bi bi-calendar3"></i><?= date('d F Y', strtotime($detail['tanggal'])) ?></span>
        </div>
        <div class="detail-content"><?= nl2br(htmlspecialchars($detail['isi'])) ?></div>
      </div>
    </div>

    <?php else: ?>
    <!-- ─── LIST ─── -->
    <?php if ($total === 0): ?>
      <div class="empty-state">
        <i class="bi bi-newspaper"></i>
        <p>Belum ada berita yang dipublikasikan.</p>
      </div>
    <?php else: ?>
      <div class="news-grid">
        <?php while ($b = mysqli_fetch_assoc($data)): ?>
        <div class="news-card">
          <div class="news-card-img">
            <?php if (!empty($b['gambar'])): ?>
              <img src="admin/upload/<?= htmlspecialchars($b['gambar']) ?>" alt="<?= htmlspecialchars($b['judul']) ?>">
            <?php else: ?>
              <div class="news-card-img-placeholder">
                <i class="bi bi-newspaper" style="font-size:40px;color:#93C5FD;"></i>
              </div>
            <?php endif; ?>
          </div>
          <div class="news-card-body">
            <div class="news-meta">
              <span><i class="bi bi-person-fill"></i><?= htmlspecialchars($b['penulis']) ?></span>
              <span><i class="bi bi-calendar3"></i><?= date('d M Y', strtotime($b['tanggal'])) ?></span>
            </div>
            <h2 class="news-card-title"><?= htmlspecialchars($b['judul']) ?></h2>
            <p class="news-card-excerpt"><?= htmlspecialchars(strip_tags(mb_substr($b['isi'], 0, 140))) ?>…</p>
          </div>
          <div class="news-card-footer">
            <a href="?id=<?= $b['id'] ?>" class="btn-read"><i class="bi bi-arrow-right-circle"></i>Baca Selengkapnya</a>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

      <!-- PAGINATION -->
      <?php if ($totalHalaman > 1): ?>
      <div class="pagination-wrap">
        <?php if ($halaman > 1): ?>
          <a href="?page=<?= $halaman-1 ?>" class="page-btn"><i class="bi bi-chevron-left"></i></a>
        <?php endif; ?>
        <?php for ($i=1; $i<=$totalHalaman; $i++): ?>
          <a href="?page=<?= $i ?>" class="page-btn <?= $i==$halaman?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($halaman < $totalHalaman): ?>
          <a href="?page=<?= $halaman+1 ?>" class="page-btn"><i class="bi bi-chevron-right"></i></a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    <?php endif; ?>
    <?php endif; ?>

  </div>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> <strong>SMK Ganesha Tama Boyolali</strong> — Berkarakter, Inovatif, Berwawasan Lingkungan
</div>
</body>
</html>