<?php
/**
 * ROLE HELPER
 * ─────────────────────────────────────────────
 * Include di header.php (sudah auto tersedia di semua halaman).
 *
 * Fungsi:
 *   is_admin()        → true jika role = 'admin'
 *   is_user_only()    → true jika role = 'user'
 *   require_admin()   → redirect / tampilkan pesan jika bukan admin
 *   can_write()       → alias is_admin() — boleh CRUD
 */

function is_admin(): bool {
    return (($_SESSION['role'] ?? 'admin') === 'admin');
}

function is_user_only(): bool {
    return (($_SESSION['role'] ?? 'admin') === 'user');
}

function can_write(): bool {
    return is_admin();
}

/**
 * Panggil di atas proses POST yang hanya boleh admin.
 * Menghentikan eksekusi dan redirect jika bukan admin.
 */
function require_admin(string $redirect = 'hal_admin.php'): void {
    if (!is_admin()) {
        echo "<script>alert('Akses ditolak. Anda hanya memiliki hak lihat (read-only).');window.location='$redirect';</script>";
        exit;
    }
}

/**
 * Render tombol aksi (edit / hapus) hanya untuk admin.
 * Untuk role user: tampilkan badge "read-only" atau kosong.
 *
 * @param string $html   HTML tombol yang ingin dirender (hanya tampil jika admin)
 * @param bool   $badge  Tampilkan badge "Hanya Lihat" untuk user jika true
 */
function action_btn(string $html, bool $badge = false): string {
    if (is_admin()) return $html;
    if ($badge) return '<span class="badge" style="background:#F1F5F9;color:#94A3B8;font-size:10px;font-weight:600;padding:4px 8px;border-radius:6px;">Hanya Lihat</span>';
    return '';
}

/**
 * Render tombol "Tambah / Input" hanya untuk admin.
 * Jika user: kembalikan string kosong.
 */
function add_btn(string $html): string {
    return is_admin() ? $html : '';
}