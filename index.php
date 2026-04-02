<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SMK Ganesha Tama Boyolali</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --navy:    #0B1F3A;
      --blue:    #1255A4;
      --blue2:   #1A6FCC;
      --sky:     #3B9FE8;
      --gold:    #F0A500;
      --gold2:   #FFD166;
      --white:   #FFFFFF;
      --offwhite:#F0F6FF;
      --muted:   #6B8BB5;
      --text:    #1A2E4A;
    }

    html, body {
      height: 100%;
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--offwhite);
      overflow-x: hidden;
    }

    /* ── TOP INFO BAR ── */
    .topbar {
      background: var(--navy);
      color: rgba(255,255,255,0.82);
      font-size: 12.5px;
      font-weight: 500;
      padding: 9px 0;
      letter-spacing: 0.2px;
    }
    .topbar-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 36px;
      flex-wrap: wrap;
    }
    .topbar-item {
      display: flex;
      align-items: center;
      gap: 7px;
    }
    .topbar-item i {
      color: var(--gold);
      font-size: 13px;
    }

    /* ── HEADER ── */
    .header {
      background: var(--white);
      border-bottom: 3px solid var(--blue);
      padding: 0 32px;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 2px 20px rgba(18, 85, 164, 0.12);
    }
    .header-inner {
      max-width: 1200px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 76px;
    }
    .school-brand {
      display: flex;
      align-items: center;
      gap: 14px;
      text-decoration: none;
    }
    .school-logo {
      width: 54px;
      height: 54px;
      flex-shrink: 0;
    }
    .school-name-wrap .name {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 800;
      font-size: 17px;
      color: var(--navy);
      line-height: 1.2;
      letter-spacing: -0.3px;
    }
    .school-name-wrap .sub {
      font-size: 11px;
      color: var(--blue);
      font-weight: 600;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      margin-top: 1px;
    }
    .btn-login {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 24px;
      background: linear-gradient(135deg, var(--blue), var(--blue2));
      color: #fff;
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 14px;
      font-weight: 700;
      border-radius: 10px;
      text-decoration: none;
      letter-spacing: 0.2px;
      box-shadow: 0 4px 16px rgba(18, 85, 164, 0.30);
      transition: transform .15s, box-shadow .15s;
      position: relative;
      overflow: hidden;
    }
    .btn-login::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,.15) 0%, transparent 50%);
    }
    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(18, 85, 164, 0.40);
    }
    .btn-login:active { transform: translateY(0); }

    /* ── HERO ── */
    .hero {
      position: relative;
      min-height: calc(100vh - 115px);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
    }

    /* Sky gradient background */
    .hero-sky {
      position: absolute;
      inset: 0;
      background: linear-gradient(
        180deg,
        #BDDFF7 0%,
        #D9EEFA 25%,
        #EDF5FB 50%,
        #F7EDD8 72%,
        #F2D5A8 85%,
        #E8B87A 100%
      );
      z-index: 0;
    }

    /* Animated clouds */
    .cloud {
      position: absolute;
      z-index: 1;
      opacity: 0.9;
    }
    .cloud-1 { top: 8%; left: 5%; animation: drift1 18s ease-in-out infinite alternate; }
    .cloud-2 { top: 14%; right: 8%; animation: drift2 22s ease-in-out infinite alternate; }
    .cloud-3 { top: 28%; left: 50%; transform: translateX(-50%); animation: drift1 26s ease-in-out infinite alternate-reverse; opacity: 0.5; }

    @keyframes drift1 { 0% { transform: translateX(0); } 100% { transform: translateX(30px); } }
    @keyframes drift2 { 0% { transform: translateX(0); } 100% { transform: translateX(-25px); } }

    /* Sun */
    .sun {
      position: absolute;
      top: 6%;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1;
    }

    /* School name badge overlay */
    .hero-badge {
      position: relative;
      z-index: 10;
      margin-top: 36px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      animation: fadeDown .7s ease both;
    }
    @keyframes fadeDown {
      from { opacity:0; transform: translateY(-20px); }
      to   { opacity:1; transform: translateY(0); }
    }
    .hero-badge .badge-logo {
      width: 74px;
      height: 74px;
      border-radius: 50%;
      background: white;
      box-shadow: 0 6px 24px rgba(18,85,164,.25), 0 0 0 4px rgba(255,255,255,0.6);
      display: flex; align-items: center; justify-content: center;
      overflow: hidden;
    }
    .hero-badge .school-title {
      text-align: center;
    }
    .hero-badge .school-title .big {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 900;
      font-size: 28px;
      color: var(--navy);
      line-height: 1.15;
      text-shadow: 0 2px 8px rgba(255,255,255,0.8);
      letter-spacing: -0.5px;
    }
    .hero-badge .school-title .small {
      font-size: 13px;
      color: var(--blue);
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      margin-top: 2px;
    }

    /* Building SVG */
    .hero-building {
      position: relative;
      z-index: 5;
      width: 100%;
      max-width: 960px;
      margin: 0 auto;
      margin-top: -10px;
      flex-shrink: 0;
    }

    /* Ground strip with school name sign */
    .hero-ground {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 6;
      height: 120px;
      background: linear-gradient(180deg, #6B4C1E 0%, #8B6234 30%, #7A5528 100%);
    }
    .hero-sign {
      position: absolute;
      bottom: 100px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 20;
      background: #FFF8E1;
      border: 4px solid var(--navy);
      border-radius: 4px;
      padding: 10px 48px;
      text-align: center;
      box-shadow: 0 4px 16px rgba(0,0,0,.25);
      white-space: nowrap;
    }
    .hero-sign .sign-main {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-weight: 900;
      font-size: 22px;
      color: var(--blue);
      letter-spacing: 1.5px;
      text-transform: uppercase;
    }
    .hero-sign .sign-sub {
      font-size: 11.5px;
      color: var(--text);
      font-weight: 500;
      margin-top: 1px;
    }

    /* Footer strip */
    .footer-strip {
      background: var(--navy);
      color: rgba(255,255,255,0.6);
      text-align: center;
      font-size: 12px;
      padding: 14px 20px;
      font-weight: 500;
    }
    .footer-strip span { color: var(--gold); font-weight: 700; }

    @media (max-width: 640px) {
      .topbar-inner { gap: 14px; flex-direction: column; }
      .hero-badge .school-title .big { font-size: 20px; }
      .hero-sign .sign-main { font-size: 15px; }
      .hero-sign { padding: 8px 24px; bottom: 80px; }
      .header-inner { height: 64px; }
      .school-name-wrap .name { font-size: 14px; }
      .btn-login { padding: 8px 16px; font-size: 13px; }
    }
  </style>
</head>
<body>

<!-- ── TOP INFO BAR ── -->
<div class="topbar">
  <div class="topbar-inner">
    <span class="topbar-item">
      <i class="bi bi-geo-alt-fill"></i>
      Jl. Perintis Kemerdekaan, Boyolali 57316, Jawa Tengah
    </span>
    <span class="topbar-item">
      <i class="bi bi-telephone-fill"></i>
      (0276) 321579
    </span>
    <span class="topbar-item">
      <i class="bi bi-envelope-fill"></i>
      info@ganeshatama-byi.sch.id
    </span>
  </div>
</div>

<!-- ── HEADER ── -->
<header class="header">
  <div class="header-inner">
    <!-- Logo + Nama -->
    <a href="index.php" class="school-brand">
      <!-- SVG Logo placeholder -->
      <svg class="school-logo" viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg">
        <circle cx="27" cy="27" r="27" fill="#0B1F3A"/>
        <circle cx="27" cy="27" r="23" fill="#1255A4"/>
        <polygon points="27,8 32,20 45,20 35,28 39,40 27,32 15,40 19,28 9,20 22,20" fill="#F0A500" opacity="0.95"/>
        <circle cx="27" cy="27" r="7" fill="white" opacity="0.9"/>
        <text x="27" y="31" text-anchor="middle" font-size="7" font-weight="bold" fill="#0B1F3A" font-family="sans-serif">GT</text>
      </svg>
      <div class="school-name-wrap">
        <div class="name">SMK Ganesha Tama Boyolali</div>
        <div class="sub">Berkarakter · Inovatif · Berwawasan Lingkungan</div>
      </div>
    </a>

    <!-- Login Button -->
    <a href="login.php" class="btn-login">
      <i class="bi bi-box-arrow-in-right"></i>
      Login
    </a>
  </div>
</header>

<!-- ── HERO SECTION ── -->
<section class="hero">

  <!-- Sky gradient -->
  <div class="hero-sky"></div>

  <!-- Sun -->
  <div class="sun">
    <svg width="100" height="100" viewBox="0 0 100 100">
      <defs>
        <radialGradient id="sunGrad" cx="50%" cy="50%" r="50%">
          <stop offset="0%" stop-color="#FFE066"/>
          <stop offset="60%" stop-color="#FFB300"/>
          <stop offset="100%" stop-color="#FF8F00" stop-opacity="0"/>
        </radialGradient>
      </defs>
      <circle cx="50" cy="50" r="48" fill="url(#sunGrad)" opacity="0.7"/>
      <circle cx="50" cy="50" r="28" fill="#FFD54F" opacity="0.9"/>
      <circle cx="50" cy="50" r="20" fill="#FFEE58"/>
    </svg>
  </div>

  <!-- Clouds -->
  <div class="cloud cloud-1">
    <svg width="200" height="60" viewBox="0 0 200 60" fill="none">
      <ellipse cx="100" cy="40" rx="90" ry="22" fill="white" opacity="0.92"/>
      <ellipse cx="70" cy="32" rx="50" ry="20" fill="white" opacity="0.92"/>
      <ellipse cx="130" cy="30" rx="44" ry="18" fill="white" opacity="0.92"/>
    </svg>
  </div>
  <div class="cloud cloud-2">
    <svg width="160" height="50" viewBox="0 0 160 50" fill="none">
      <ellipse cx="80" cy="34" rx="72" ry="18" fill="white" opacity="0.85"/>
      <ellipse cx="55" cy="26" rx="38" ry="16" fill="white" opacity="0.85"/>
      <ellipse cx="108" cy="24" rx="34" ry="14" fill="white" opacity="0.85"/>
    </svg>
  </div>
  <div class="cloud cloud-3">
    <svg width="280" height="70" viewBox="0 0 280 70" fill="none">
      <ellipse cx="140" cy="48" rx="130" ry="24" fill="white" opacity="0.6"/>
      <ellipse cx="100" cy="36" rx="70" ry="22" fill="white" opacity="0.6"/>
      <ellipse cx="185" cy="34" rx="60" ry="20" fill="white" opacity="0.6"/>
    </svg>
  </div>

  <!-- School badge / title -->
  <div class="hero-badge">
    <div class="badge-logo">
      <svg viewBox="0 0 54 54" width="64" height="64" xmlns="http://www.w3.org/2000/svg">
        <circle cx="27" cy="27" r="27" fill="#0B1F3A"/>
        <circle cx="27" cy="27" r="23" fill="#1255A4"/>
        <polygon points="27,8 32,20 45,20 35,28 39,40 27,32 15,40 19,28 9,20 22,20" fill="#F0A500" opacity="0.95"/>
        <circle cx="27" cy="27" r="7" fill="white" opacity="0.9"/>
        <text x="27" y="31" text-anchor="middle" font-size="7" font-weight="bold" fill="#0B1F3A" font-family="sans-serif">GT</text>
      </svg>
    </div>
    <div class="school-title">
      <div class="big">SMK Ganesha Tama</div>
      <div class="big" style="font-size:22px;color:#1255A4;">Boyolali</div>
    </div>
  </div>

  <!-- Building Illustration (SVG) -->
  <div class="hero-building">
    <svg viewBox="0 0 960 420" xmlns="http://www.w3.org/2000/svg" style="width:100%;display:block;">

      <!-- Ground -->
      <rect x="0" y="340" width="960" height="80" fill="#8B6234"/>
      <rect x="0" y="340" width="960" height="12" fill="#A07540"/>

      <!-- Trees left -->
      <rect x="30" y="290" width="14" height="60" fill="#4A7C1F"/>
      <ellipse cx="37" cy="275" rx="26" ry="30" fill="#3A6B15"/>
      <ellipse cx="37" cy="260" rx="20" ry="24" fill="#4A8B20"/>

      <rect x="80" y="300" width="12" height="50" fill="#4A7C1F"/>
      <ellipse cx="86" cy="287" rx="22" ry="26" fill="#3A6B15"/>
      <ellipse cx="86" cy="274" rx="17" ry="20" fill="#4A8B20"/>

      <!-- Trees right -->
      <rect x="880" y="290" width="14" height="60" fill="#4A7C1F"/>
      <ellipse cx="887" cy="275" rx="26" ry="30" fill="#3A6B15"/>
      <ellipse cx="887" cy="260" rx="20" ry="24" fill="#4A8B20"/>

      <rect x="840" y="300" width="12" height="50" fill="#4A7C1F"/>
      <ellipse cx="846" cy="287" rx="22" ry="26" fill="#3A6B15"/>
      <ellipse cx="846" cy="274" rx="17" ry="20" fill="#4A8B20"/>

      <!-- ─── LEFT WING BUILDING ─── -->
      <rect x="60" y="180" width="280" height="165" fill="#E8D5B0"/>
      <rect x="60" y="168" width="280" height="16" fill="#C4A265"/>
      <!-- Windows left wing -->
      <?php for ($i = 0; $i < 5; $i++): ?>
      <rect x="<?= 80 + $i*50 ?>" y="200" width="30" height="40" rx="3" fill="#A8C4E0"/>
      <rect x="<?= 80 + $i*50 ?>" y="260" width="30" height="40" rx="3" fill="#A8C4E0"/>
      <line x1="<?= 95 + $i*50 ?>" y1="200" x2="<?= 95 + $i*50 ?>" y2="240" stroke="#6A9CC0" stroke-width="1"/>
      <line x1="<?= 80 + $i*50 ?>" y1="220" x2="<?= 110 + $i*50 ?>" y2="220" stroke="#6A9CC0" stroke-width="1"/>
      <?php endfor; ?>
      <!-- Red accent band left -->
      <rect x="60" y="320" width="280" height="22" fill="#C0392B"/>
      <rect x="60" y="310" width="280" height="10" fill="#E74C3C"/>

      <!-- ─── RIGHT WING BUILDING ─── -->
      <rect x="620" y="180" width="280" height="165" fill="#E8D5B0"/>
      <rect x="620" y="168" width="280" height="16" fill="#C4A265"/>
      <!-- Windows right wing -->
      <?php for ($i = 0; $i < 5; $i++): ?>
      <rect x="<?= 640 + $i*50 ?>" y="200" width="30" height="40" rx="3" fill="#A8C4E0"/>
      <rect x="<?= 640 + $i*50 ?>" y="260" width="30" height="40" rx="3" fill="#A8C4E0"/>
      <line x1="<?= 655 + $i*50 ?>" y1="200" x2="<?= 655 + $i*50 ?>" y2="240" stroke="#6A9CC0" stroke-width="1"/>
      <line x1="<?= 640 + $i*50 ?>" y1="220" x2="<?= 670 + $i*50 ?>" y2="220" stroke="#6A9CC0" stroke-width="1"/>
      <?php endfor; ?>
      <!-- Red accent band right -->
      <rect x="620" y="320" width="280" height="22" fill="#C0392B"/>
      <rect x="620" y="310" width="280" height="10" fill="#E74C3C"/>

      <!-- ─── MAIN CENTER TOWER ─── -->
      <!-- Tower body -->
      <rect x="340" y="80" width="280" height="265" fill="#D4C4A0"/>
      <rect x="340" y="65" width="280" height="20" fill="#B8A070"/>

      <!-- Tower roof/top -->
      <rect x="380" y="30" width="200" height="40" rx="4" fill="#0B1F3A"/>
      <rect x="400" y="15" width="160" height="20" rx="4" fill="#0B1F3A"/>
      <!-- Roof accent -->
      <rect x="380" y="30" width="200" height="8" fill="#1255A4"/>

      <!-- Tower pillar columns -->
      <rect x="350" y="140" width="18" height="205" rx="4" fill="#C4A870"/>
      <rect x="396" y="140" width="18" height="205" rx="4" fill="#C4A870"/>
      <rect x="546" y="140" width="18" height="205" rx="4" fill="#C4A870"/>
      <rect x="592" y="140" width="18" height="205" rx="4" fill="#C4A870"/>

      <!-- Tower windows (top floor) -->
      <rect x="380" y="90" width="50" height="60" rx="3" fill="#B8D4F0" opacity="0.9"/>
      <rect x="455" y="90" width="50" height="60" rx="3" fill="#B8D4F0" opacity="0.9"/>
      <rect x="530" y="90" width="50" height="60" rx="3" fill="#B8D4F0" opacity="0.9"/>
      <!-- Window dividers -->
      <line x1="405" y1="90" x2="405" y2="150" stroke="#7AA8D0" stroke-width="1"/>
      <line x1="480" y1="90" x2="480" y2="150" stroke="#7AA8D0" stroke-width="1"/>
      <line x1="555" y1="90" x2="555" y2="150" stroke="#7AA8D0" stroke-width="1"/>
      <line x1="380" y1="120" x2="430" y2="120" stroke="#7AA8D0" stroke-width="1"/>
      <line x1="455" y1="120" x2="505" y2="120" stroke="#7AA8D0" stroke-width="1"/>
      <line x1="530" y1="120" x2="580" y2="120" stroke="#7AA8D0" stroke-width="1"/>

      <!-- Tower middle windows -->
      <rect x="362" y="175" width="38" height="50" rx="3" fill="#A8C4E0" opacity="0.85"/>
      <rect x="415" y="175" width="50" height="50" rx="3" fill="#A8C4E0" opacity="0.85"/>
      <rect x="480" y="175" width="0" height="0" fill="none"/>
      <rect x="495" y="175" width="50" height="50" rx="3" fill="#A8C4E0" opacity="0.85"/>
      <rect x="560" y="175" width="38" height="50" rx="3" fill="#A8C4E0" opacity="0.85"/>

      <!-- Center grand door/entrance arch -->
      <rect x="435" y="255" width="90" height="90" rx="0" fill="#0B1F3A" opacity="0.85"/>
      <path d="M435,255 Q480,220 525,255" fill="#1255A4" opacity="0.9"/>
      <rect x="445" y="270" width="30" height="75" rx="2" fill="#7AAAC8" opacity="0.7"/>
      <rect x="485" y="270" width="30" height="75" rx="2" fill="#7AAAC8" opacity="0.7"/>
      <line x1="460" y1="270" x2="460" y2="345" stroke="#5A8AAA" stroke-width="1"/>
      <line x1="500" y1="270" x2="500" y2="345" stroke="#5A8AAA" stroke-width="1"/>
      <line x1="445" y1="307" x2="475" y2="307" stroke="#5A8AAA" stroke-width="1"/>
      <line x1="485" y1="307" x2="515" y2="307" stroke="#5A8AAA" stroke-width="1"/>

      <!-- Red accent bands tower -->
      <rect x="340" y="330" width="280" height="14" fill="#C0392B"/>
      <rect x="340" y="320" width="280" height="12" fill="#E74C3C"/>

      <!-- Blue accent stripe -->
      <rect x="340" y="132" width="280" height="10" fill="#1255A4"/>

      <!-- Flag pole -->
      <line x1="480" y1="15" x2="480" y2="-20" stroke="#888" stroke-width="2.5"/>
      <rect x="480" y="-20" width="44" height="28" fill="#CC0000"/>
      <rect x="480" y="-6" width="44" height="14" fill="white"/>

      <!-- Gate left -->
      <rect x="190" y="285" width="20" height="60" rx="2" fill="#F0A500"/>
      <rect x="168" y="270" width="24" height="80" rx="2" fill="#F0A500"/>
      <!-- Gate bars left -->
      <?php for ($i = 0; $i < 6; $i++): ?>
      <rect x="<?= 192 + $i*5 ?>" y="295" width="3" height="50" rx="1" fill="#1255A4"/>
      <?php endfor; ?>

      <!-- Gate right -->
      <rect x="750" y="285" width="20" height="60" rx="2" fill="#F0A500"/>
      <rect x="768" y="270" width="24" height="80" rx="2" fill="#F0A500"/>
      <!-- Gate bars right -->
      <?php for ($i = 0; $i < 6; $i++): ?>
      <rect x="<?= 752 + $i*5 ?>" y="295" width="3" height="50" rx="1" fill="#1255A4"/>
      <?php endfor; ?>

      <!-- Sidewalk -->
      <rect x="0" y="338" width="960" height="6" fill="#9E8060"/>

      <!-- Path/driveway -->
      <rect x="360" y="340" width="240" height="20" fill="#C4A870" opacity="0.6"/>

    </svg>
  </div>

  <!-- Sign board -->
  <div class="hero-sign">
    <div class="sign-main">SMK GANESHA TAMA BOYOLALI</div>
    <div class="sign-sub">Jl. Perintis Kemerdekaan, Boyolali 57316 &nbsp;·&nbsp; Telp. (0276) 321579</div>
  </div>

</section>

<!-- ── FOOTER STRIP ── -->
<div class="footer-strip">
  &copy; <?= date('Y') ?> <span>SMK Ganesha Tama Boyolali</span> — Berkarakter, Inovatif, dan Berwawasan Lingkungan
</div>

</body>
</html>