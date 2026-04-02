<?php
include '../koneksi.php';

$search  = isset($_GET['q']) ? trim($_GET['q']) : '';
$mapel   = isset($_GET['mapel']) ? trim($_GET['mapel']) : '';
$where   = "WHERE 1=1";
if ($search) $where .= " AND nama_guru LIKE '%" . mysqli_real_escape_string($koneksi, $search) . "%'";
if ($mapel)  $where .= " AND mapel = '" . mysqli_real_escape_string($koneksi, $mapel) . "'";

$limit   = 12;
$halaman = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset  = ($halaman - 1) * $limit;
$total   = (int)mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) c FROM guru $where"))['c'];
$totalHalaman = ceil($total / $limit);
$data    = mysqli_query($koneksi, "SELECT * FROM guru $where ORDER BY nama_guru ASC LIMIT $limit OFFSET $offset");

// Daftar mapel untuk filter
$mapel_list = [];
$rm = mysqli_query($koneksi, "SELECT DISTINCT mapel FROM guru WHERE mapel != '' ORDER BY mapel");
while ($d = mysqli_fetch_assoc($rm)) $mapel_list[] = $d['mapel'];

// Avatar helpers
function get_initials_pub(string $n): string {
    $words = preg_split('/\s+/', trim($n));
    $i = '';
    foreach ($words as $w) if ($w) $i .= strtoupper($w[0]);
    return substr($i, 0, 2) ?: '?';
}
$palette = ['#EF4444','#F97316','#EAB308','#22C55E','#06B6D4','#3B82F6','#8B5CF6','#EC4899','#14B8A6','#F43F5E'];
function get_color_pub(string $n): string {
    global $palette;
    $h = 0;
    for ($i = 0; $i < strlen($n); $i++) $h = ord($n[$i]) + (($h << 5) - $h);
    return $palette[abs($h) % count($palette)];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Data Guru — SMK Ganesha Tama Boyolali</title>
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
.container { max-width:1200px; margin:0 auto; padding:0 24px; }
.section { padding:60px 0; }

/* FILTER BAR */
.filter-bar { background:#fff; border-radius:14px; padding:20px 24px; box-shadow:0 2px 12px rgba(18,85,164,.07); margin-bottom:36px; display:flex; gap:14px; flex-wrap:wrap; align-items:flex-end; }
.filter-group { display:flex; flex-direction:column; gap:6px; flex:1; min-width:180px; }
.filter-group label { font-size:11.5px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.4px; }
.filter-group input, .filter-group select { padding:9px 14px; border:1.5px solid #DBEAFE; border-radius:9px; font-family:'Plus Jakarta Sans',sans-serif; font-size:13.5px; color:var(--text); outline:none; transition:border .15s; background:#fff; }
.filter-group input:focus, .filter-group select:focus { border-color:var(--blue); }
.btn-filter { padding:10px 22px; background:linear-gradient(135deg,var(--blue),var(--blue2)); color:#fff; border:none; border-radius:9px; font-family:'Plus Jakarta Sans',sans-serif; font-size:13.5px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:7px; transition:all .15s; flex-shrink:0; }
.btn-filter:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(18,85,164,.3); }
.btn-reset { padding:10px 16px; background:#F1F5F9; color:var(--muted); border:none; border-radius:9px; font-family:'Plus Jakarta Sans',sans-serif; font-size:13.5px; font-weight:600; cursor:pointer; text-decoration:none; display:flex; align-items:center; gap:6px; }
.btn-reset:hover { background:#E2E8F0; }

/* STATS ROW */
.stats-row { display:flex; gap:16px; margin-bottom:32px; flex-wrap:wrap; }
.stat-pill { background:#fff; border-radius:12px; padding:14px 20px; box-shadow:0 2px 8px rgba(18,85,164,.06); border:1px solid #DBEAFE; display:flex; align-items:center; gap:10px; }
.stat-pill-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; }
.stat-pill-num { font-size:22px; font-weight:800; color:var(--navy); line-height:1; }
.stat-pill-lbl { font-size:12px; color:var(--muted); font-weight:500; }

/* GURU GRID */
.guru-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:24px; }
.guru-card { background:#fff; border-radius:18px; overflow:hidden; box-shadow:0 2px 12px rgba(18,85,164,.07); border:1px solid rgba(18,85,164,.07); transition:transform .2s,box-shadow .2s; text-align:center; }
.guru-card:hover { transform:translateY(-6px); box-shadow:0 14px 40px rgba(18,85,164,.14); }
.guru-card-top { padding:32px 20px 20px; position:relative; }
.guru-card-top::before { content:''; position:absolute; inset:0 0 40% 0; background:linear-gradient(135deg,#EFF6FF,#DBEAFE); }
.guru-avatar { width:90px; height:90px; border-radius:50%; object-fit:cover; border:4px solid #fff; box-shadow:0 4px 16px rgba(18,85,164,.15); position:relative; z-index:1; margin:0 auto 10px; display:block; }
.guru-avatar-init { width:90px; height:90px; border-radius:50%; border:4px solid #fff; box-shadow:0 4px 16px rgba(18,85,164,.15); position:relative; z-index:1; margin:0 auto 10px; display:flex; align-items:center; justify-content:center; font-size:30px; font-weight:800; color:#fff; }
.guru-name { font-family:'Playfair Display',serif; font-size:16px; font-weight:700; color:var(--navy); position:relative; z-index:1; }
.guru-mapel { font-size:12px; color:var(--blue); font-weight:600; background:#EFF6FF; padding:3px 12px; border-radius:20px; display:inline-block; margin-top:6px; position:relative; z-index:1; }
.guru-card-body { padding:16px 20px 20px; display:flex; flex-direction:column; gap:8px; text-align:left; border-top:1px solid #F0F6FF; }
.guru-info-row { display:flex; align-items:center; gap:8px; font-size:12.5px; color:var(--muted); }
.guru-info-row i { font-size:13px; color:var(--blue); flex-shrink:0; }
.guru-badge-jk { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
.badge-lk { background:#EFF6FF; color:#1255A4; }
.badge-pr { background:#FDF2F8; color:#9333EA; }

.empty-state { text-align:center; padding:80px 24px; color:var(--muted); }
.empty-state i { font-size:52px; opacity:.25; display:block; margin-bottom:16px; }
.pagination-wrap { display:flex; justify-content:center; gap:8px; margin-top:48px; flex-wrap:wrap; }
.page-btn { padding:8px 14px; border-radius:9px; background:#fff; border:1.5px solid #DBEAFE; color:var(--blue); font-size:13px; font-weight:600; text-decoration:none; transition:all .15s; }
.page-btn:hover, .page-btn.active { background:var(--blue); color:#fff; border-color:var(--blue); }
.footer { background:var(--navy); color:rgba(255,255,255,.6); text-align:center; font-size:12px; padding:20px; }
.footer strong { color:var(--gold); }

@media(max-width:640px) { .nav-menu { display:none; } .guru-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:400px) { .guru-grid { grid-template-columns:1fr; } }
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
      <a href="publik_guru.php" class="active">Guru</a>
      <a href="publik_profil.php">Profil Sekolah</a>
      <a href="login.php" class="btn-login-sm"><i class="bi bi-box-arrow-in-right"></i>Login Admin</a>
    </nav>
  </div>
</header>

<div class="page-hero">
  <h1>Data Guru</h1>
  <p>Tenaga pendidik profesional SMK Ganesha Tama Boyolali</p>
  <div class="breadcrumb-bar">
    <a href="index.php"><i class="bi bi-house-fill"></i> Beranda</a>
    <span>/</span><span class="cur">Guru</span>
  </div>
</div>

<div class="container">
  <div class="section">

    <!-- STATS -->
    <?php
    $total_guru = (int)mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) c FROM guru"))['c'];
    $total_lk   = (int)mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) c FROM guru WHERE jenis_kelamin='Laki-Laki'"))['c'];
    $total_pr   = (int)mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(*) c FROM guru WHERE jenis_kelamin='Perempuan'"))['c'];
    $total_mp   = (int)mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT COUNT(DISTINCT mapel) c FROM guru WHERE mapel != ''"))['c'];
    ?>
    <div class="stats-row">
      <div class="stat-pill">
        <div class="stat-pill-icon" style="background:#EFF6FF;"><i class="bi bi-people-fill" style="color:#1255A4;"></i></div>
        <div><div class="stat-pill-num"><?= $total_guru ?></div><div class="stat-pill-lbl">Total Guru</div></div>
      </div>
      <div class="stat-pill">
        <div class="stat-pill-icon" style="background:#DBEAFE;"><i class="bi bi-person-fill" style="color:#2563EB;"></i></div>
        <div><div class="stat-pill-num"><?= $total_lk ?></div><div class="stat-pill-lbl">Laki-Laki</div></div>
      </div>
      <div class="stat-pill">
        <div class="stat-pill-icon" style="background:#FDF2F8;"><i class="bi bi-person-fill" style="color:#9333EA;"></i></div>
        <div><div class="stat-pill-num"><?= $total_pr ?></div><div class="stat-pill-lbl">Perempuan</div></div>
      </div>
      <div class="stat-pill">
        <div class="stat-pill-icon" style="background:#F0FDF4;"><i class="bi bi-book-fill" style="color:#16A34A;"></i></div>
        <div><div class="stat-pill-num"><?= $total_mp ?></div><div class="stat-pill-lbl">Mata Pelajaran</div></div>
      </div>
    </div>

    <!-- FILTER -->
    <form method="get" class="filter-bar">
      <div class="filter-group">
        <label><i class="bi bi-search"></i> Cari Nama</label>
        <input type="text" name="q" placeholder="Nama guru..." value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="filter-group">
        <label><i class="bi bi-book"></i> Mata Pelajaran</label>
        <select name="mapel">
          <option value="">Semua Mapel</option>
          <?php foreach ($mapel_list as $mp): ?>
            <option value="<?= htmlspecialchars($mp) ?>" <?= $mapel===$mp?'selected':'' ?>><?= htmlspecialchars($mp) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn-filter"><i class="bi bi-funnel-fill"></i>Filter</button>
      <a href="publik_guru.php" class="btn-reset"><i class="bi bi-x-circle"></i>Reset</a>
    </form>

    <!-- RESULT INFO -->
    <div style="font-size:13px;color:var(--muted);margin-bottom:20px;">
      Menampilkan <strong style="color:var(--navy);"><?= $total ?></strong> guru
      <?php if ($search || $mapel): ?>
        dengan filter: <?= $search ? '<strong>'.htmlspecialchars($search).'</strong>' : '' ?> <?= $mapel ? '· Mapel: <strong>'.htmlspecialchars($mapel).'</strong>' : '' ?>
      <?php endif; ?>
    </div>

    <!-- GRID -->
    <?php if ($total === 0): ?>
      <div class="empty-state">
        <i class="bi bi-person-x"></i>
        <p>Tidak ada guru yang ditemukan.</p>
      </div>
    <?php else: ?>
      <div class="guru-grid">
        <?php while ($g = mysqli_fetch_assoc($data)):
          $initials = get_initials_pub($g['nama_guru']);
          $color    = get_color_pub($g['nama_guru']);
          $is_lk    = ($g['jenis_kelamin'] === 'Laki-Laki');
        ?>
        <div class="guru-card">
          <div class="guru-card-top">
            <?php if (!empty($g['foto'])): ?>
              <img src="admin/upload/<?= htmlspecialchars($g['foto']) ?>" class="guru-avatar" alt="<?= htmlspecialchars($g['nama_guru']) ?>">
            <?php else: ?>
              <div class="guru-avatar-init" style="background:<?= $color ?>;"><?= $initials ?></div>
            <?php endif; ?>
            <div class="guru-name"><?= htmlspecialchars($g['nama_guru']) ?></div>
            <?php if ($g['mapel']): ?>
              <div class="guru-mapel"><i class="bi bi-book-fill me-1"></i><?= htmlspecialchars($g['mapel']) ?></div>
            <?php endif; ?>
          </div>
          <div class="guru-card-body">
            <?php if ($g['nip']): ?>
            <div class="guru-info-row">
              <i class="bi bi-person-badge-fill"></i>
              <span>NIP: <?= htmlspecialchars($g['nip']) ?></span>
            </div>
            <?php endif; ?>
            <div class="guru-info-row">
              <i class="bi bi-gender-<?= $is_lk?'male':'female' ?>"></i>
              <span class="guru-badge-jk <?= $is_lk?'badge-lk':'badge-pr' ?>">
                <?= $is_lk ? '♂ Laki-Laki' : '♀ Perempuan' ?>
              </span>
            </div>
            <?php if ($g['email']): ?>
            <div class="guru-info-row">
              <i class="bi bi-envelope-fill"></i>
              <span style="word-break:break-all;"><?= htmlspecialchars($g['email']) ?></span>
            </div>
            <?php endif; ?>
            <?php if ($g['no_hp']): ?>
            <div class="guru-info-row">
              <i class="bi bi-telephone-fill"></i>
              <span><?= htmlspecialchars($g['no_hp']) ?></span>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

      <?php if ($totalHalaman > 1): ?>
      <div class="pagination-wrap">
        <?php if ($halaman > 1): ?><a href="?page=<?= $halaman-1 ?>&q=<?= urlencode($search) ?>&mapel=<?= urlencode($mapel) ?>" class="page-btn"><i class="bi bi-chevron-left"></i></a><?php endif; ?>
        <?php for ($i=1; $i<=$totalHalaman; $i++): ?>
          <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&mapel=<?= urlencode($mapel) ?>" class="page-btn <?= $i==$halaman?'active':'' ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($halaman < $totalHalaman): ?><a href="?page=<?= $halaman+1 ?>&q=<?= urlencode($search) ?>&mapel=<?= urlencode($mapel) ?>" class="page-btn"><i class="bi bi-chevron-right"></i></a><?php endif; ?>
      </div>
      <?php endif; ?>
    <?php endif; ?>

  </div>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> <strong>SMK Ganesha Tama Boyolali</strong> — Berkarakter, Inovatif, Berwawasan Lingkungan
</div>
</body>
</html>