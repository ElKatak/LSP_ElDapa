<?php
include '../koneksi.php';

$limit   = 12;
$halaman = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset  = ($halaman - 1) * $limit;
$total   = (int)mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM galeri"))['c'];
$totalHalaman = ceil($total / $limit);
$data    = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY tanggal_upload DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Galeri — SMK Ganesha Tama Boyolali</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
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
.btn-login-sm { padding:8px 18px !important; background:linear-gradient(135deg,var(--blue),var(--blue2)) !important; color:#fff !important; border-radius:9px !important; box-shadow:0 3px 10px rgba(18,85,164,.28); }
.btn-login-sm:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(18,85,164,.38) !important; }
.page-hero { background:linear-gradient(135deg,var(--navy) 0%,#1A5FA8 100%); padding:60px 24px 50px; text-align:center; position:relative; overflow:hidden; }
.page-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(255,255,255,.06) 0%,transparent 70%); }
.page-hero h1 { font-family:'Playfair Display',serif; font-size:clamp(28px,5vw,48px); color:#fff; font-weight:900; position:relative; }
.page-hero p { color:rgba(255,255,255,.72); font-size:14px; margin-top:10px; position:relative; }
.breadcrumb-bar { display:flex; align-items:center; justify-content:center; gap:8px; margin-top:16px; font-size:12.5px; position:relative; }
.breadcrumb-bar a { color:rgba(255,255,255,.7); text-decoration:none; }
.breadcrumb-bar a:hover { color:#fff; }
.breadcrumb-bar span { color:rgba(255,255,255,.4); }
.breadcrumb-bar .cur { color:var(--gold); font-weight:600; }
.container { max-width:1200px; margin:0 auto; padding:0 24px; }
.section { padding:60px 0; }

/* MASONRY-STYLE GRID */
.gallery-grid { columns:3; column-gap:20px; }
.gallery-item { break-inside:avoid; margin-bottom:20px; border-radius:16px; overflow:hidden; background:#fff; box-shadow:0 2px 12px rgba(18,85,164,.07); border:1px solid rgba(18,85,164,.06); cursor:pointer; transition:transform .2s, box-shadow .2s; position:relative; }
.gallery-item:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(18,85,164,.15); }
.gallery-item img { width:100%; display:block; }
.gallery-item-placeholder { width:100%; height:200px; background:linear-gradient(135deg,#DBEAFE,#BFDBFE); display:flex; align-items:center; justify-content:center; }
.gallery-overlay { position:absolute; inset:0; background:linear-gradient(to top, rgba(11,31,58,.75) 0%, transparent 50%); opacity:0; transition:opacity .2s; display:flex; align-items:flex-end; padding:20px; }
.gallery-item:hover .gallery-overlay { opacity:1; }
.gallery-overlay-text h3 { font-weight:700; font-size:14px; color:#fff; line-height:1.3; }
.gallery-overlay-text p { font-size:11.5px; color:rgba(255,255,255,.75); margin-top:3px; }

/* LIGHTBOX */
.lightbox { position:fixed; inset:0; background:rgba(0,0,0,.88); z-index:9999; display:none; align-items:center; justify-content:center; padding:24px; }
.lightbox.show { display:flex; }
.lightbox-inner { max-width:900px; width:100%; position:relative; }
.lightbox img { width:100%; border-radius:16px; max-height:75vh; object-fit:contain; display:block; }
.lightbox-info { background:#fff; border-radius:0 0 16px 16px; padding:18px 24px; }
.lightbox-info h3 { font-family:'Playfair Display',serif; font-size:18px; color:var(--navy); font-weight:700; }
.lightbox-info p { font-size:13px; color:var(--muted); margin-top:4px; }
.lightbox-close { position:absolute; top:-16px; right:-16px; width:40px; height:40px; background:#fff; border:none; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 4px 12px rgba(0,0,0,.2); font-size:18px; color:var(--navy); z-index:2; }
.lightbox-close:hover { background:var(--navy); color:#fff; }

.empty-state { text-align:center; padding:80px 24px; color:var(--muted); }
.empty-state i { font-size:52px; opacity:.25; display:block; margin-bottom:16px; }
.pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:48px; flex-wrap:wrap; }
.page-btn { padding:8px 14px; border-radius:9px; background:#fff; border:1.5px solid #DBEAFE; color:var(--blue); font-size:13px; font-weight:600; text-decoration:none; transition:all .15s; }
.page-btn:hover, .page-btn.active { background:var(--blue); color:#fff; border-color:var(--blue); }
.footer { background:var(--navy); color:rgba(255,255,255,.6); text-align:center; font-size:12px; padding:20px; }
.footer strong { color:var(--gold); }

@media(max-width:900px) { .gallery-grid { columns:2; } }
@media(max-width:560px) { .gallery-grid { columns:1; } .nav-menu { display:none; } }
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
      <a href="publik_galeri.php" class="active">Galeri</a>
      <a href="publik_guru.php">Guru</a>
      <a href="publik_profil.php">Profil Sekolah</a>
      <a href="login.php" class="btn-login-sm"><i class="bi bi-box-arrow-in-right"></i>Login Admin</a>
    </nav>
  </div>
</header>

<div class="page-hero">
  <h1>Galeri Sekolah</h1>
  <p>Kumpulan momen dan kegiatan di lingkungan sekolah</p>
  <div class="breadcrumb-bar">
    <a href="index.php"><i class="bi bi-house-fill"></i> Beranda</a>
    <span>/</span><span class="cur">Galeri</span>
  </div>
</div>

<div class="container">
  <div class="section">
    <?php
    $items = [];
    while ($g = mysqli_fetch_assoc($data)) $items[] = $g;
    ?>

    <?php if (empty($items)): ?>
      <div class="empty-state">
        <i class="bi bi-images"></i>
        <p>Belum ada foto dalam galeri.</p>
      </div>
    <?php else: ?>
      <div class="gallery-grid">
        <?php foreach ($items as $g): ?>
        <div class="gallery-item"
          onclick="openLightbox('<?= !empty($g['gambar']) ? htmlspecialchars($g['gambar'], ENT_QUOTES) : '' ?>','<?= htmlspecialchars($g['judul'], ENT_QUOTES) ?>','<?= htmlspecialchars($g['deskripsi'], ENT_QUOTES) ?>','<?= htmlspecialchars($g['tanggal_upload'], ENT_QUOTES) ?>')">
          <?php if (!empty($g['gambar'])): ?>
            <img src="admin/upload/<?= htmlspecialchars($g['gambar']) ?>" alt="<?= htmlspecialchars($g['judul']) ?>" loading="lazy">
          <?php else: ?>
            <div class="gallery-item-placeholder"><i class="bi bi-image" style="font-size:36px;color:#93C5FD;"></i></div>
          <?php endif; ?>
          <div class="gallery-overlay">
            <div class="gallery-overlay-text">
              <h3><?= htmlspecialchars($g['judul']) ?></h3>
              <p><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($g['tanggal_upload'])) ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <?php if ($totalHalaman > 1): ?>
      <div class="pagination-wrap">
        <?php if ($halaman > 1): ?><a href="?page=<?= $halaman-1 ?>" class="page-btn"><i class="bi bi-chevron-left"></i></a><?php endif; ?>
        <?php for ($i=1; $i<=$totalHalaman; $i++): ?>
          <a href="?page=<?= $i ?>" class="page-btn <?= $i==$halaman?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($halaman < $totalHalaman): ?><a href="?page=<?= $halaman+1 ?>" class="page-btn"><i class="bi bi-chevron-right"></i></a><?php endif; ?>
      </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox" onclick="if(event.target===this)closeLightbox()">
  <div class="lightbox-inner">
    <button class="lightbox-close" onclick="closeLightbox()"><i class="bi bi-x-lg"></i></button>
    <img id="lb-img" src="" alt="">
    <div class="lightbox-info">
      <h3 id="lb-title"></h3>
      <p id="lb-desc"></p>
    </div>
  </div>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> <strong>SMK Ganesha Tama Boyolali</strong> — Berkarakter, Inovatif, Berwawasan Lingkungan
</div>

<script>
function openLightbox(gambar, judul, desk, tgl) {
  const lb = document.getElementById('lightbox');
  const img = document.getElementById('lb-img');
  if (gambar) { img.src = 'admin/upload/' + gambar; img.style.display = 'block'; }
  else { img.style.display = 'none'; }
  document.getElementById('lb-title').textContent = judul || '—';
  document.getElementById('lb-desc').textContent  = (desk || '') + (tgl ? '  ·  ' + tgl : '');
  lb.classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeLightbox() {
  document.getElementById('lightbox').classList.remove('show');
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
</body>
</html>