<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>XTM Ganakat — Login</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous"/>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --ink:    #0A0F1E;
      --slate:  #1E293B;
      --muted:  #64748B;
      --border: #E2E8F0;
      --blue:   #2563EB;
      --blue2:  #1D4ED8;
      --cyan:   #06B6D4;
      --red:    #EF4444;
      --white:  #FFFFFF;
      --bg:     #F1F5F9;
    }

    html, body {
      height: 100%;
      font-family: 'Outfit', sans-serif;
      background: var(--bg);
      overflow: hidden;
    }

    /* ── Animated Background ── */
    .bg-scene {
      position: fixed;
      inset: 0;
      z-index: 0;
      background:
        radial-gradient(ellipse 80% 60% at 20% 10%,  rgba(37,99,235,.13) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 80% 80%,  rgba(6,182,212,.10) 0%, transparent 60%),
        radial-gradient(ellipse 50% 70% at 50% 50%,  rgba(255,255,255,.6) 0%, transparent 70%),
        #F1F5F9;
    }

    /* Floating blobs */
    .blob {
      position: fixed;
      border-radius: 50%;
      filter: blur(60px);
      opacity: 0.5;
      animation: drift 12s ease-in-out infinite alternate;
      pointer-events: none;
      z-index: 0;
    }
    .blob-1 { width:500px; height:500px; background:rgba(37,99,235,.18); top:-150px; left:-150px; animation-delay:0s; }
    .blob-2 { width:400px; height:400px; background:rgba(6,182,212,.15);  bottom:-100px; right:-100px; animation-delay:-5s; }
    .blob-3 { width:300px; height:300px; background:rgba(139,92,246,.12); top:40%; left:60%; animation-delay:-3s; }

    @keyframes drift {
      0%   { transform: translate(0,0) scale(1); }
      100% { transform: translate(30px, 20px) scale(1.06); }
    }

    /* Grid overlay */
    .grid-overlay {
      position: fixed;
      inset: 0;
      z-index: 0;
      background-image:
        linear-gradient(rgba(37,99,235,.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(37,99,235,.04) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
    }

    /* ── Layout ── */
    .scene {
      position: relative;
      z-index: 1;
      display: flex;
      min-height: 100vh;
      align-items: center;
      justify-content: center;
      padding: 24px;
    }

    /* ── Card ── */
    .card {
      background: rgba(255,255,255,0.82);
      backdrop-filter: blur(20px) saturate(180%);
      -webkit-backdrop-filter: blur(20px) saturate(180%);
      border: 1px solid rgba(255,255,255,0.7);
      border-radius: 24px;
      box-shadow:
        0 2px 0 rgba(255,255,255,.9) inset,
        0 32px 80px rgba(15,23,42,.12),
        0 8px 20px rgba(15,23,42,.06);
      width: 100%;
      max-width: 420px;
      padding: 44px 40px 40px;
      animation: slideUp .55s cubic-bezier(.22,.68,0,1.2) both;
    }

    @keyframes slideUp {
      from { opacity:0; transform: translateY(32px) scale(.97); }
      to   { opacity:1; transform: translateY(0) scale(1); }
    }

    /* ── Logo ── */
    .logo-wrap {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 32px;
    }

    .logo-icon {
      width: 54px;
      height: 54px;
      border-radius: 16px;
      background: linear-gradient(135deg, var(--blue), var(--cyan));
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 6px 20px rgba(37,99,235,.35);
      flex-shrink: 0;
      position: relative;
      overflow: hidden;
    }
    .logo-icon::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,.2) 0%, transparent 60%);
    }
    .logo-icon img {
      width: 38px;
      height: 38px;
      object-fit: contain;
      position: relative;
      z-index: 1;
    }

    .logo-text .name {
      font-weight: 800;
      font-size: 18px;
      color: var(--ink);
      letter-spacing: -0.3px;
      line-height: 1.1;
    }
    .logo-text .sub {
      font-size: 11.5px;
      color: var(--muted);
      font-weight: 500;
      letter-spacing: 0.4px;
    }

    /* ── Heading ── */
    .heading {
      margin-bottom: 28px;
    }
    .heading h1 {
      font-size: 26px;
      font-weight: 800;
      color: var(--ink);
      letter-spacing: -0.5px;
      line-height: 1.15;
    }
    .heading p {
      font-size: 13.5px;
      color: var(--muted);
      margin-top: 5px;
    }

    /* ── Alert error ── */
    .alert-err {
      background: #FEF2F2;
      border: 1px solid #FECACA;
      border-radius: 10px;
      padding: 11px 14px;
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13px;
      color: var(--red);
      font-weight: 600;
      margin-bottom: 20px;
      animation: shake .35s cubic-bezier(.36,.07,.19,.97);
    }
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      20%,60%  { transform: translateX(-6px); }
      40%,80%  { transform: translateX(6px); }
    }

    /* ── Form ── */
    .field {
      margin-bottom: 18px;
    }
    .field label {
      display: block;
      font-size: 12.5px;
      font-weight: 700;
      color: var(--slate);
      letter-spacing: 0.3px;
      text-transform: uppercase;
      margin-bottom: 7px;
    }
    .input-wrap {
      position: relative;
    }
    .input-wrap i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 15px;
      color: var(--muted);
      pointer-events: none;
      transition: color .2s;
    }
    .input-wrap input {
      width: 100%;
      height: 48px;
      padding: 0 44px 0 42px;
      border: 1.5px solid var(--border);
      border-radius: 12px;
      font-family: 'Outfit', sans-serif;
      font-size: 14.5px;
      color: var(--ink);
      background: rgba(255,255,255,.7);
      outline: none;
      transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .input-wrap input::placeholder { color: #94A3B8; }
    .input-wrap input:focus {
      border-color: var(--blue);
      box-shadow: 0 0 0 4px rgba(37,99,235,.10);
      background: #fff;
    }
    .input-wrap input:focus + i,
    .input-wrap:has(input:focus) i { color: var(--blue); }

    /* Toggle password */
    .toggle-pw {
      position: absolute;
      right: 13px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: var(--muted);
      cursor: pointer;
      background: none;
      border: none;
      padding: 4px;
      transition: color .2s;
      z-index: 2;
    }
    .toggle-pw:hover { color: var(--blue); }

    /* ── Remember me ── */
    .remember {
      display: flex;
      align-items: center;
      gap: 9px;
      margin-bottom: 24px;
    }
    .remember input[type=checkbox] {
      width: 17px; height: 17px;
      accent-color: var(--blue);
      cursor: pointer;
      border-radius: 4px;
    }
    .remember label {
      font-size: 13px;
      color: var(--muted);
      cursor: pointer;
      font-weight: 500;
    }

    /* ── Submit button ── */
    .btn-login {
      width: 100%;
      height: 50px;
      border: none;
      border-radius: 13px;
      background: linear-gradient(135deg, var(--blue), var(--blue2));
      color: #fff;
      font-family: 'Outfit', sans-serif;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 0.2px;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: transform .15s, box-shadow .15s;
      box-shadow: 0 4px 16px rgba(37,99,235,.35);
    }
    .btn-login::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,.15) 0%, transparent 50%);
    }
    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 8px 24px rgba(37,99,235,.4);
    }
    .btn-login:active {
      transform: translateY(0);
      box-shadow: 0 3px 10px rgba(37,99,235,.3);
    }
    .btn-login .btn-text { position: relative; z-index: 1; display: flex; align-items: center; justify-content: center; gap: 8px; }

    /* ── Divider ── */
    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 20px 0;
      font-size: 12px;
      color: #CBD5E1;
      font-weight: 500;
    }
    .divider::before, .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--border);
    }

    /* ── Role chips ── */
    .role-chips {
      display: flex;
      gap: 8px;
    }
    .chip {
      flex: 1;
      padding: 9px 0;
      border-radius: 10px;
      border: 1.5px solid var(--border);
      background: rgba(255,255,255,.5);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      font-size: 13px;
      font-weight: 600;
      color: var(--muted);
      cursor: pointer;
      transition: all .18s;
    }
    .chip i { font-size: 15px; }
    .chip.active {
      border-color: var(--blue);
      background: rgba(37,99,235,.07);
      color: var(--blue);
    }

    /* ── Footer note ── */
    .foot-note {
      text-align: center;
      font-size: 11.5px;
      color: #94A3B8;
      margin-top: 22px;
      font-weight: 500;
    }
    .foot-note a { color: var(--blue); text-decoration: none; font-weight: 600; }
    .foot-note a:hover { text-decoration: underline; }

    /* ── Side decoration (desktop) ── */
    @media (min-width: 900px) {
      .scene { justify-content: flex-end; padding-right: 10%; }
      .side-deco {
        position: fixed;
        left: 0; top: 0; bottom: 0;
        width: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px;
        z-index: 1;
      }
      .side-text h2 {
        font-size: 44px;
        font-weight: 900;
        color: var(--ink);
        letter-spacing: -1.5px;
        line-height: 1.05;
        margin-bottom: 18px;
      }
      .side-text h2 span {
        background: linear-gradient(135deg, var(--blue), var(--cyan));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      .side-text p {
        font-size: 16px;
        color: var(--muted);
        max-width: 380px;
        line-height: 1.7;
      }
      .stats-row {
        display: flex;
        gap: 24px;
        margin-top: 40px;
      }
      .stat-box {
        background: rgba(255,255,255,.7);
        border: 1px solid rgba(255,255,255,.8);
        border-radius: 16px;
        padding: 16px 22px;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 20px rgba(15,23,42,.06);
      }
      .stat-box .num {
        font-size: 26px;
        font-weight: 800;
        color: var(--ink);
      }
      .stat-box .lbl {
        font-size: 12px;
        color: var(--muted);
        font-weight: 500;
        margin-top: 2px;
      }
    }
    @media (max-width: 899px) {
      .side-deco { display: none; }
      .scene { justify-content: center; }
    }
  </style>
</head>
<body>

<div class="bg-scene"></div>
<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>
<div class="grid-overlay"></div>

<!-- Side decoration (desktop only) -->
<div class="side-deco">
  <div class="side-text">
    <h2>Panel Admin<br><span>XTM Ganakat</span></h2>
    <p>Kelola data sekolah, berita, galeri, dan guru dengan mudah dari satu dasbor terpusat.</p>
    <div class="stats-row">
      <div class="stat-box">
        <div class="num">2+</div>
        <div class="lbl">Role Akses</div>
      </div>
      <div class="stat-box">
        <div class="num">5</div>
        <div class="lbl">Modul Utama</div>
      </div>
      <div class="stat-box">
        <div class="num">∞</div>
        <div class="lbl">Konten</div>
      </div>
    </div>
  </div>
</div>

<!-- Main -->
<div class="scene">
  <div class="card">

    <!-- Logo -->
    <div class="logo-wrap">
      <div class="logo-icon">
        <img src="template/giwar-giwar.png" alt="Logo Ganakat">
      </div>
      <div class="logo-text">
        <div class="name">XTM Ganakat</div>
        <div class="sub">Admin Panel · v2.0</div>
      </div>
    </div>

    <!-- Heading -->
    <div class="heading">
      <h1>Selamat Datang 👋</h1>
      <p>Masuk untuk mengelola panel administrasi sekolah.</p>
    </div>

    <!-- Error alert -->
    <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'gagal'): ?>
    <div class="alert-err">
      <i class="bi bi-exclamation-circle-fill" style="font-size:18px;flex-shrink:0;"></i>
      Username atau password salah. Silakan coba lagi.
    </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="proses_login.php" method="post" autocomplete="off">

      <div class="field">
        <label>Username</label>
        <div class="input-wrap">
          <i class="bi bi-person-fill"></i>
          <input type="text" name="username" placeholder="Masukkan username" required autocomplete="username" />
        </div>
      </div>

      <div class="field">
        <label>Password</label>
        <div class="input-wrap">
          <i class="bi bi-lock-fill"></i>
          <input type="password" name="password" id="pw-input" placeholder="Masukkan password" required autocomplete="current-password" />
          <button type="button" class="toggle-pw" onclick="togglePw()" id="toggle-icon">
            <i class="bi bi-eye-fill" id="eye-icon"></i>
          </button>
        </div>
      </div>

      <div class="remember">
        <input type="checkbox" id="remember">
        <label for="remember">Ingat saya di perangkat ini</label>
      </div>

      <button type="submit" class="btn-login">
        <span class="btn-text">
          <i class="bi bi-box-arrow-in-right"></i>
          Masuk Sekarang
        </span>
      </button>

    </form>

    <div class="foot-note">
      &copy; <?= date('Y') ?> XTM Ganakat — All rights reserved
    </div>

  </div>
</div>

<script>
  function togglePw() {
    const input = document.getElementById('pw-input');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.className = 'bi bi-eye-slash-fill';
    } else {
      input.type = 'password';
      icon.className = 'bi bi-eye-fill';
    }
  }
</script>
</body>
</html>