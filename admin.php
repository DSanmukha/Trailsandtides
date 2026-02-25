<?php
include 'includes/db.php';
if (!isset($_SESSION['user'])) { header("Location: auth/login.php"); exit; }
if (($_SESSION['user']['role'] ?? 'user') !== 'admin') { header("Location: index.php"); exit; }

$user = $_SESSION['user'];
$msg = '';
$section = $_GET['section'] ?? 'dashboard';
$action  = $_GET['action']  ?? '';
$id      = intval($_GET['id'] ?? 0);

// ── Helpers ──────────────────────────────────────────────────────────────────
function clean($conn, $v) { return $conn->real_escape_string(trim($v)); }
function flash($text, $type='success') { return "<div class='adm-toast adm-toast-{$type}'><i class='bi bi-" . ($type==='success'?'check-circle':'exclamation-triangle') . "'></i> {$text}</div>"; }

// ═════════════════════════════════════════════════════════════════════════════
// PROCESS POST ACTIONS
// ═════════════════════════════════════════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['_action'] ?? '';

    // ── TOURS ─────────────────────────────────────────────────────────────────
    if ($act === 'save_tour') {
        $tid   = intval($_POST['id'] ?? 0);
        $title = clean($conn, $_POST['title']); $dest  = clean($conn, $_POST['destination']);
        $dur   = intval($_POST['duration']); $price = floatval($_POST['price']);
        $orig  = floatval($_POST['original_price']); $rating = floatval($_POST['rating']);
        $rev   = intval($_POST['reviews']); $tag   = clean($conn, $_POST['tag']);
        $grp   = clean($conn, $_POST['group_type']); $img = clean($conn, $_POST['image_url']);
        $desc  = clean($conn, $_POST['description']); $inc = clean($conn, $_POST['inclusions']);
        $active = intval($_POST['active'] ?? 1);
        if ($tid) {
            $conn->query("UPDATE tours SET title='$title',destination='$dest',duration=$dur,price=$price,original_price=$orig,rating=$rating,reviews=$rev,tag='$tag',group_type='$grp',image_url='$img',description='$desc',inclusions='$inc',active=$active WHERE id=$tid");
            $msg = flash('Tour updated successfully!');
        } else {
            $conn->query("INSERT INTO tours (title,destination,duration,price,original_price,rating,reviews,tag,group_type,image_url,description,inclusions,active) VALUES ('$title','$dest',$dur,$price,$orig,$rating,$rev,'$tag','$grp','$img','$desc','$inc',$active)");
            $msg = flash('Tour added successfully!');
        }
        $section = 'tours';
    }
    if ($act === 'delete_tour') {
        $tid = intval($_POST['id']);
        $conn->query("DELETE FROM tours WHERE id=$tid");
        $msg = flash('Tour deleted.', 'danger'); $section = 'tours';
    }

    // ── DESTINATIONS ─────────────────────────────────────────────────────────
    if ($act === 'save_dest') {
        $did   = intval($_POST['id'] ?? 0);
        $name  = clean($conn,$_POST['name']); $country=clean($conn,$_POST['country']);
        $region= clean($conn,$_POST['region']); $desc=clean($conn,$_POST['description']);
        $weather=clean($conn,$_POST['weather']); $temp=intval($_POST['temperature']);
        $rating=floatval($_POST['rating']); $tc=intval($_POST['tour_count']);
        $img   = clean($conn,$_POST['image_url']); $active=intval($_POST['active']??1);
        if ($did) {
            $conn->query("UPDATE destinations SET name='$name',country='$country',region='$region',description='$desc',weather='$weather',temperature=$temp,rating=$rating,tour_count=$tc,image_url='$img',active=$active WHERE id=$did");
            $msg = flash('Destination updated!');
        } else {
            $conn->query("INSERT INTO destinations (name,country,region,description,weather,temperature,rating,tour_count,image_url,active) VALUES ('$name','$country','$region','$desc','$weather',$temp,$rating,$tc,'$img',$active)");
            $msg = flash('Destination added!');
        }
        $section = 'destinations';
    }
    if ($act === 'delete_dest') {
        $conn->query("DELETE FROM destinations WHERE id=".intval($_POST['id']));
        $msg = flash('Destination deleted.','danger'); $section='destinations';
    }

    // ── HOTELS ───────────────────────────────────────────────────────────────
    if ($act === 'save_hotel') {
        $hid  = intval($_POST['id']??0); $name=clean($conn,$_POST['name']);
        $dest = clean($conn,$_POST['destination']); $desc=clean($conn,$_POST['description']);
        $ppn  = floatval($_POST['price_per_night']); $img=clean($conn,$_POST['image_url']);
        $rat  = floatval($_POST['rating']); $am=clean($conn,$_POST['amenities']);
        $stars= intval($_POST['stars']); $active=intval($_POST['active']??1);
        if ($hid) {
            $conn->query("UPDATE hotels SET name='$name',destination='$dest',description='$desc',price_per_night=$ppn,image_url='$img',rating=$rat,amenities='$am',stars=$stars,active=$active WHERE id=$hid");
            $msg = flash('Hotel updated!');
        } else {
            $conn->query("INSERT INTO hotels (name,destination,description,price_per_night,image_url,rating,amenities,stars,active) VALUES ('$name','$dest','$desc',$ppn,'$img',$rat,'$am',$stars,$active)");
            $msg = flash('Hotel added!');
        }
        $section = 'hotels';
    }
    if ($act === 'delete_hotel') {
        $conn->query("DELETE FROM hotels WHERE id=".intval($_POST['id']));
        $msg = flash('Hotel deleted.','danger'); $section='hotels';
    }

    // ── BOOKINGS ─────────────────────────────────────────────────────────────
    if ($act === 'update_booking') {
        $bid = intval($_POST['id']); $status=clean($conn,$_POST['status']);
        $conn->query("UPDATE bookings SET status='$status' WHERE id=$bid");
        $msg = flash('Booking status updated!'); $section='bookings';
    }
    if ($act === 'delete_booking') {
        $conn->query("DELETE FROM bookings WHERE id=".intval($_POST['id']));
        $msg = flash('Booking deleted.','danger'); $section='bookings';
    }

    // ── USERS ─────────────────────────────────────────────────────────────────
    if ($act === 'toggle_admin') {
        $uid = intval($_POST['id']); $role=clean($conn,$_POST['role']);
        $newrole = $role==='admin'?'user':'admin';
        $conn->query("UPDATE users SET role='$newrole' WHERE id=$uid");
        $msg = flash("User role changed to {$newrole}!"); $section='users';
    }
    if ($act === 'delete_user') {
        $uid = intval($_POST['id']);
        if ($uid !== $user['id']) { $conn->query("DELETE FROM users WHERE id=$uid"); $msg=flash('User deleted.','danger'); }
        else { $msg=flash('Cannot delete yourself.','danger'); }
        $section='users';
    }

    // ── JOURNAL ──────────────────────────────────────────────────────────────
    if ($act === 'delete_journal') {
        $conn->query("DELETE FROM journal_entries WHERE id=".intval($_POST['id']));
        $msg=flash('Journal entry deleted.','danger'); $section='journal';
    }
}

// ═════════════════════════════════════════════════════════════════════════════
// FETCH DATA
// ═════════════════════════════════════════════════════════════════════════════
// Dashboard stats
$stats = [];
foreach(['users','tours','bookings','destinations','hotels','journal_entries'] as $t) {
    try { $stats[$t] = $conn->query("SELECT COUNT(*) as c FROM $t")->fetch_assoc()['c']; } catch(Exception $e){ $stats[$t]=0; }
}
try { $stats['revenue'] = $conn->query("SELECT SUM(total_price) as r FROM bookings WHERE status!='cancelled'")->fetch_assoc()['r'] ?? 0; } catch(Exception $e){ $stats['revenue']=0; }
try { $recentBookings = $conn->query("SELECT b.*,u.name as uname,u.email FROM bookings b JOIN users u ON b.user_id=u.id ORDER BY b.created_at DESC LIMIT 8"); } catch(Exception $e){ $recentBookings=null; }

// Editing single record
$editItem = null;
if ($action === 'edit' && $id > 0) {
    $tbl = ['tours'=>'tours','destinations'=>'destinations','hotels'=>'hotels'][$section] ?? null;
    if ($tbl) $editItem = $conn->query("SELECT * FROM $tbl WHERE id=$id")->fetch_assoc();
}
?>
<?php include 'includes/header.php'; ?>

<style>
body { background:#f4f1fb; }

/* ── Sidebar ── */
.adm-wrap { display:flex; min-height:calc(100vh - 76px); margin-top:76px; }
.adm-sidebar {
    width:240px; flex-shrink:0;
    background:linear-gradient(180deg,#1e1b4b,#2d2a6e);
    padding:1.5rem 0; position:fixed; top:76px; left:0; bottom:0; overflow-y:auto; z-index:100;
}
.adm-brand { padding:0 1.5rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.08); margin-bottom:1rem; }
.adm-brand h5 { color:white; font-weight:800; font-size:1rem; margin:0; }
.adm-brand small { color:rgba(255,255,255,0.4); font-size:0.72rem; }
.adm-nav-item { display:flex; align-items:center; gap:10px; padding:11px 1.5rem; color:rgba(255,255,255,0.6); text-decoration:none; font-size:0.85rem; font-weight:500; transition:all 0.25s; }
.adm-nav-item:hover, .adm-nav-item.active { background:rgba(255,255,255,0.1); color:white; border-left:3px solid #8b5cf6; }
.adm-nav-item i { font-size:1rem; width:18px; text-align:center; }
.adm-nav-group { padding:0.75rem 1.5rem 0.3rem; color:rgba(255,255,255,0.25); font-size:0.65rem; letter-spacing:2px; text-transform:uppercase; }

/* ── Main Content ── */
.adm-main { margin-left:240px; flex:1; padding:2rem; }

/* ── Toast ── */
.adm-toast { display:flex; align-items:center; gap:10px; padding:13px 18px; border-radius:14px; margin-bottom:1.5rem; font-size:0.88rem; font-weight:500; }
.adm-toast-success { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.25); color:#16a34a; }
.adm-toast-danger  { background:rgba(239,68,68,0.1);  border:1px solid rgba(239,68,68,0.25);  color:#dc2626; }

/* ── Stat Cards ── */
.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.2rem; margin-bottom:2rem; }
@media(max-width:1100px){ .stat-grid { grid-template-columns:repeat(2,1fr); } }
.s-card { background:white; border-radius:18px; padding:1.4rem; border:1px solid rgba(139,92,246,0.07); box-shadow:0 4px 20px rgba(139,92,246,0.06); display:flex; align-items:center; gap:14px; transition:all 0.3s; }
.s-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(139,92,246,0.12); }
.s-icon { width:48px; height:48px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; color:white; flex-shrink:0; }
.s-val  { font-size:1.8rem; font-weight:800; color:#1e1b4b; line-height:1; }
.s-lbl  { color:#9ca3af; font-size:0.76rem; font-weight:500; }

/* ── Page Header ── */
.adm-page-hd { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; }
.adm-page-hd h4 { color:#1e1b4b; font-weight:800; font-size:1.15rem; margin:0; display:flex; align-items:center; gap:8px; }
.adm-page-hd h4 i { color:#8b5cf6; }
.btn-adm { background:linear-gradient(135deg,#8b5cf6,#c084fc); color:white; border:none; border-radius:12px; padding:9px 22px; font-size:0.83rem; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:all 0.3s; }
.btn-adm:hover { transform:translateY(-2px); box-shadow:0 8px 24px rgba(139,92,246,0.3); color:white; }
.btn-adm-sm { padding:5px 13px; font-size:0.75rem; border-radius:9px; }
.btn-danger-sm { background:rgba(239,68,68,0.1); color:#dc2626; border:1px solid rgba(239,68,68,0.2); border-radius:9px; padding:5px 12px; font-size:0.75rem; font-weight:600; cursor:pointer; transition:all 0.2s; }
.btn-danger-sm:hover { background:#dc2626; color:white; }
.btn-edit-sm { background:rgba(139,92,246,0.1); color:#8b5cf6; border:1px solid rgba(139,92,246,0.2); border-radius:9px; padding:5px 12px; font-size:0.75rem; font-weight:600; text-decoration:none; transition:all 0.2s; display:inline-block; }
.btn-edit-sm:hover { background:#8b5cf6; color:white; }

/* ── Table ── */
.adm-table-wrap { background:white; border-radius:18px; border:1px solid rgba(139,92,246,0.07); box-shadow:0 4px 20px rgba(139,92,246,0.05); overflow:hidden; }
.adm-table { width:100%; border-collapse:collapse; }
.adm-table th { background:#f9f7ff; color:#6b7280; font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:1px; padding:13px 16px; border-bottom:1px solid rgba(139,92,246,0.08); }
.adm-table td { padding:12px 16px; border-bottom:1px solid rgba(139,92,246,0.05); font-size:0.84rem; color:#374151; vertical-align:middle; }
.adm-table tr:last-child td { border-bottom:none; }
.adm-table tr:hover td { background:rgba(139,92,246,0.02); }
.adm-thumb { width:48px; height:36px; border-radius:8px; object-fit:cover; }

/* ── Status Badges ── */
.badge-confirmed { background:rgba(34,197,94,0.1); color:#16a34a; padding:3px 10px; border-radius:8px; font-size:0.7rem; font-weight:700; }
.badge-pending   { background:rgba(249,115,22,0.1); color:#f97316; padding:3px 10px; border-radius:8px; font-size:0.7rem; font-weight:700; }
.badge-cancelled { background:rgba(239,68,68,0.1);  color:#dc2626;  padding:3px 10px; border-radius:8px; font-size:0.7rem; font-weight:700; }
.badge-completed { background:rgba(99,102,241,0.1);  color:#6366f1; padding:3px 10px; border-radius:8px; font-size:0.7rem; font-weight:700; }
.badge-admin  { background:rgba(139,92,246,0.15); color:#7c3aed; padding:3px 10px; border-radius:8px; font-size:0.7rem; font-weight:700; }
.badge-user   { background:rgba(107,114,128,0.1);  color:#6b7280; padding:3px 10px; border-radius:8px; font-size:0.7rem; font-weight:700; }
.badge-active   { background:rgba(34,197,94,0.1);  color:#16a34a; padding:3px 8px; border-radius:6px; font-size:0.68rem; font-weight:700; }
.badge-inactive { background:rgba(239,68,68,0.1);  color:#dc2626;  padding:3px 8px; border-radius:6px; font-size:0.68rem; font-weight:700; }

/* ── Form Card ── */
.adm-form-card { background:white; border-radius:18px; padding:2rem; border:1px solid rgba(139,92,246,0.07); box-shadow:0 4px 20px rgba(139,92,246,0.05); margin-bottom:1.5rem; }
.adm-form-card h5 { color:#1e1b4b; font-weight:700; font-size:1rem; margin-bottom:1.5rem; display:flex; align-items:center; gap:8px; padding-bottom:1rem; border-bottom:1px solid rgba(139,92,246,0.08); }
.adm-form-card h5 i { color:#8b5cf6; }
.form-label { font-size:0.8rem; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px; }
.form-control, .form-select { border:1px solid rgba(139,92,246,0.15); border-radius:10px; padding:10px 14px; font-size:0.87rem; transition:all 0.25s; }
.form-control:focus, .form-select:focus { border-color:#8b5cf6; box-shadow:0 0 0 3px rgba(139,92,246,0.1); outline:none; }

/* ── Recent Bookings on Dashboard ── */
.dash-widget { background:white; border-radius:18px; padding:1.5rem; border:1px solid rgba(139,92,246,0.07); box-shadow:0 4px 20px rgba(139,92,246,0.05); }
.dash-widget h6 { color:#1e1b4b; font-weight:700; font-size:0.9rem; margin-bottom:1.2rem; display:flex; align-items:center; gap:7px; }
.dash-widget h6 i { color:#8b5cf6; }
</style>

<div class="adm-wrap">

  <!-- ───────── SIDEBAR ───────── -->
  <aside class="adm-sidebar">
    <div class="adm-brand">
      <h5><i class="bi bi-shield-lock-fill me-2" style="color:#c084fc;"></i>Admin Panel</h5>
      <small>Trails & Tides</small>
    </div>

    <div class="adm-nav-group">Overview</div>
    <a href="?section=dashboard" class="adm-nav-item <?= $section==='dashboard'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>

    <div class="adm-nav-group">Manage</div>
    <a href="?section=tours"        class="adm-nav-item <?= $section==='tours'?'active':'' ?>"><i class="bi bi-compass"></i> Tours</a>
    <a href="?section=destinations" class="adm-nav-item <?= $section==='destinations'?'active':'' ?>"><i class="bi bi-geo-alt"></i> Destinations</a>
    <a href="?section=hotels"       class="adm-nav-item <?= $section==='hotels'?'active':'' ?>"><i class="bi bi-building"></i> Hotels</a>

    <div class="adm-nav-group">Users & Data</div>
    <a href="?section=bookings" class="adm-nav-item <?= $section==='bookings'?'active':'' ?>"><i class="bi bi-ticket-perforated"></i> Bookings</a>
    <a href="?section=users"    class="adm-nav-item <?= $section==='users'?'active':'' ?>"><i class="bi bi-people"></i> Users</a>
    <a href="?section=journal"  class="adm-nav-item <?= $section==='journal'?'active':'' ?>"><i class="bi bi-journal-text"></i> Journal Entries</a>

    <div class="adm-nav-group">Site</div>
    <a href="index.php"   class="adm-nav-item"><i class="bi bi-house"></i> View Website</a>
    <a href="auth/logout.php" class="adm-nav-item"><i class="bi bi-box-arrow-right"></i> Logout</a>
  </aside>

  <!-- ───────── MAIN ───────── -->
  <main class="adm-main">
    <?= $msg ?>

    <?php // ══════════════════════ DASHBOARD ══════════════════════
    if ($section === 'dashboard'): ?>
      <div class="adm-page-hd">
        <h4><i class="bi bi-speedometer2"></i> Dashboard</h4>
        <span style="color:#9ca3af; font-size:0.82rem;">Welcome back, <?= htmlspecialchars($user['name']) ?>!</span>
      </div>

      <div class="stat-grid">
        <div class="s-card"><div class="s-icon" style="background:linear-gradient(135deg,#8b5cf6,#c084fc)"><i class="bi bi-people-fill"></i></div><div><div class="s-val"><?= $stats['users'] ?></div><div class="s-lbl">Total Users</div></div></div>
        <div class="s-card"><div class="s-icon" style="background:linear-gradient(135deg,#f97316,#fb923c)"><i class="bi bi-compass-fill"></i></div><div><div class="s-val"><?= $stats['tours'] ?></div><div class="s-lbl">Tours Listed</div></div></div>
        <div class="s-card"><div class="s-icon" style="background:linear-gradient(135deg,#06b6d4,#22d3ee)"><i class="bi bi-ticket-perforated-fill"></i></div><div><div class="s-val"><?= $stats['bookings'] ?></div><div class="s-lbl">Bookings</div></div></div>
        <div class="s-card"><div class="s-icon" style="background:linear-gradient(135deg,#059669,#34d399)"><i class="bi bi-currency-rupee"></i></div><div><div class="s-val">₹<?= number_format($stats['revenue']/1000,1) ?>K</div><div class="s-lbl">Revenue</div></div></div>
        <div class="s-card"><div class="s-icon" style="background:linear-gradient(135deg,#ec4899,#f9a8d4)"><i class="bi bi-geo-alt-fill"></i></div><div><div class="s-val"><?= $stats['destinations'] ?></div><div class="s-lbl">Destinations</div></div></div>
        <div class="s-card"><div class="s-icon" style="background:linear-gradient(135deg,#f59e0b,#fcd34d)"><i class="bi bi-building-fill"></i></div><div><div class="s-val"><?= $stats['hotels'] ?></div><div class="s-lbl">Hotels</div></div></div>
        <div class="s-card"><div class="s-icon" style="background:linear-gradient(135deg,#6366f1,#a5b4fc)"><i class="bi bi-journal-fill"></i></div><div><div class="s-val"><?= $stats['journal_entries'] ?></div><div class="s-lbl">Journal Entries</div></div></div>
        <div class="s-card"><div class="s-icon" style="background:linear-gradient(135deg,#14b8a6,#5eead4)"><i class="bi bi-star-fill"></i></div><div><div class="s-val">4.8</div><div class="s-lbl">Avg Rating</div></div></div>
      </div>

      <!-- Recent Bookings -->
      <div class="dash-widget">
        <h6><i class="bi bi-ticket-perforated-fill"></i> Recent Bookings</h6>
        <div style="overflow-x:auto;">
          <table class="adm-table">
            <thead><tr><th>#</th><th>User</th><th>Tour/Hotel</th><th>Date</th><th>Guests</th><th>Amount</th><th>Status</th></tr></thead>
            <tbody>
            <?php if($recentBookings && $recentBookings->num_rows>0): while($b=$recentBookings->fetch_assoc()): ?>
              <tr>
                <td><?= $b['id'] ?></td>
                <td><strong><?= htmlspecialchars($b['uname']) ?></strong><br><span style="color:#9ca3af;font-size:0.72rem;"><?= htmlspecialchars($b['email']) ?></span></td>
                <td><?= htmlspecialchars($b['hotel_name'] ?? 'Tour Package') ?></td>
                <td><?= date('d M Y', strtotime($b['check_in'] ?? $b['created_at'])) ?></td>
                <td><?= $b['guests'] ?></td>
                <td><strong>₹<?= number_format($b['total_price']) ?></strong></td>
                <td><span class="badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
              </tr>
            <?php endwhile; else: ?>
              <tr><td colspan="7" style="text-align:center;color:#9ca3af;padding:2rem;">No bookings yet</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php // ══════════════════════ TOURS ══════════════════════
    elseif ($section === 'tours'):
        $editTour = null;
        if ($action === 'edit' && $id > 0) $editTour = $conn->query("SELECT * FROM tours WHERE id=$id")->fetch_assoc();
        $tours = $conn->query("SELECT * FROM tours ORDER BY created_at DESC");
    ?>
      <div class="adm-page-hd">
        <h4><i class="bi bi-compass"></i> Tours Management</h4>
        <a href="?section=tours&action=add" class="btn-adm"><i class="bi bi-plus-lg"></i> Add Tour</a>
      </div>

      <?php if ($action === 'edit' || $action === 'add'): ?>
      <div class="adm-form-card">
        <h5><i class="bi bi-<?= $action==='edit'?'pencil':'plus-circle' ?>"></i> <?= $action==='edit'?'Edit':'Add New' ?> Tour</h5>
        <form method="POST" action="?section=tours">
          <input type="hidden" name="_action" value="save_tour">
          <input type="hidden" name="id" value="<?= $editTour['id'] ?? 0 ?>">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Tour Title *</label><input class="form-control" name="title" value="<?= htmlspecialchars($editTour['title']??'') ?>" required></div>
            <div class="col-md-6"><label class="form-label">Destination *</label><input class="form-control" name="destination" value="<?= htmlspecialchars($editTour['destination']??'') ?>" required></div>
            <div class="col-md-3"><label class="form-label">Duration (days)</label><input class="form-control" type="number" name="duration" value="<?= $editTour['duration']??5 ?>"></div>
            <div class="col-md-3"><label class="form-label">Price (₹)</label><input class="form-control" type="number" name="price" value="<?= $editTour['price']??'' ?>" required></div>
            <div class="col-md-3"><label class="form-label">Original Price (₹)</label><input class="form-control" type="number" name="original_price" value="<?= $editTour['original_price']??'' ?>"></div>
            <div class="col-md-3"><label class="form-label">Rating (1-5)</label><input class="form-control" type="number" step="0.1" min="1" max="5" name="rating" value="<?= $editTour['rating']??4.5 ?>"></div>
            <div class="col-md-3"><label class="form-label">Reviews Count</label><input class="form-control" type="number" name="reviews" value="<?= $editTour['reviews']??0 ?>"></div>
            <div class="col-md-3"><label class="form-label">Tag / Badge</label>
              <select class="form-select" name="tag">
                <?php foreach(['POPULAR','LUXURY','FAMILY','HONEYMOON','ADVENTURE','CULTURAL','NEW','SALE','FEATURED'] as $t): ?>
                <option value="<?=$t?>" <?= ($editTour['tag']??'')===$t?'selected':'' ?>><?=$t?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3"><label class="form-label">Group Type</label><input class="form-control" name="group_type" value="<?= htmlspecialchars($editTour['group_type']??'Group Tours') ?>"></div>
            <div class="col-md-3"><label class="form-label">Status</label>
              <select class="form-select" name="active">
                <option value="1" <?= ($editTour['active']??1)?'selected':'' ?>>Active</option>
                <option value="0" <?= !($editTour['active']??1)?'selected':'' ?>>Hidden</option>
              </select>
            </div>
            <div class="col-12"><label class="form-label">Image URL</label><input class="form-control" name="image_url" value="<?= htmlspecialchars($editTour['image_url']??'') ?>" placeholder="https://images.unsplash.com/..."></div>
            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($editTour['description']??'') ?></textarea></div>
            <div class="col-12"><label class="form-label">Inclusions (comma-separated)</label><input class="form-control" name="inclusions" value="<?= htmlspecialchars($editTour['inclusions']??'') ?>" placeholder="Flights, Hotel, Guide, Meals"></div>
            <div class="col-12 d-flex gap-2 mt-2">
              <button type="submit" class="btn-adm"><i class="bi bi-save"></i> Save Tour</button>
              <a href="?section=tours" class="btn-adm" style="background:rgba(107,114,128,0.1);color:#6b7280;box-shadow:none;">Cancel</a>
            </div>
          </div>
        </form>
      </div>
      <?php endif; ?>

      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>ID</th><th>Image</th><th>Title</th><th>Destination</th><th>Duration</th><th>Price</th><th>Rating</th><th>Tag</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php if($tours && $tours->num_rows>0): while($t=$tours->fetch_assoc()): ?>
            <tr>
              <td><?= $t['id'] ?></td>
              <td><?php if($t['image_url']): ?><img src="<?= htmlspecialchars($t['image_url']) ?>" class="adm-thumb" onerror="this.style.display='none'"><?php else: ?>—<?php endif; ?></td>
              <td><strong><?= htmlspecialchars($t['title']) ?></strong></td>
              <td><?= htmlspecialchars($t['destination']) ?></td>
              <td><?= $t['duration'] ?> days</td>
              <td>₹<?= number_format($t['price']) ?></td>
              <td>⭐ <?= $t['rating'] ?></td>
              <td><span class="badge-confirmed"><?= $t['tag'] ?></span></td>
              <td><?= $t['active'] ? '<span class="badge-active">Active</span>' : '<span class="badge-inactive">Hidden</span>' ?></td>
              <td>
                <a href="?section=tours&action=edit&id=<?= $t['id'] ?>" class="btn-edit-sm">Edit</a>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this tour?')">
                  <input type="hidden" name="_action" value="delete_tour"><input type="hidden" name="id" value="<?= $t['id'] ?>">
                  <button class="btn-danger-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="10" style="text-align:center;color:#9ca3af;padding:2rem;">No tours yet — <a href="?section=tours&action=add">Add one</a></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

    <?php // ══════════════════════ DESTINATIONS ══════════════════════
    elseif ($section === 'destinations'):
        $editDest = null;
        if ($action === 'edit' && $id > 0) $editDest = $conn->query("SELECT * FROM destinations WHERE id=$id")->fetch_assoc();
        $dests = $conn->query("SELECT * FROM destinations ORDER BY name ASC");
    ?>
      <div class="adm-page-hd">
        <h4><i class="bi bi-geo-alt"></i> Destinations Management</h4>
        <a href="?section=destinations&action=add" class="btn-adm"><i class="bi bi-plus-lg"></i> Add Destination</a>
      </div>

      <?php if ($action === 'edit' || $action === 'add'): ?>
      <div class="adm-form-card">
        <h5><i class="bi bi-<?= $action==='edit'?'pencil':'plus-circle' ?>"></i> <?= $action==='edit'?'Edit':'Add New' ?> Destination</h5>
        <form method="POST" action="?section=destinations">
          <input type="hidden" name="_action" value="save_dest">
          <input type="hidden" name="id" value="<?= $editDest['id'] ?? 0 ?>">
          <div class="row g-3">
            <div class="col-md-4"><label class="form-label">City / Name *</label><input class="form-control" name="name" value="<?= htmlspecialchars($editDest['name']??'') ?>" required></div>
            <div class="col-md-4"><label class="form-label">Country *</label><input class="form-control" name="country" value="<?= htmlspecialchars($editDest['country']??'') ?>" required></div>
            <div class="col-md-4"><label class="form-label">Region / Continent</label>
              <select class="form-select" name="region">
                <?php foreach(['Southeast Asia','East Asia','South Asia','Europe','North America','Central America','South America','Africa','Middle East','Oceania'] as $r): ?>
                <option value="<?=$r?>" <?= ($editDest['region']??'')===$r?'selected':'' ?>><?=$r?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3"><label class="form-label">Weather</label><input class="form-control" name="weather" value="<?= htmlspecialchars($editDest['weather']??'Sunny') ?>"></div>
            <div class="col-md-3"><label class="form-label">Avg Temp (°C)</label><input class="form-control" type="number" name="temperature" value="<?= $editDest['temperature']??25 ?>"></div>
            <div class="col-md-3"><label class="form-label">Rating (1-5)</label><input class="form-control" type="number" step="0.1" name="rating" value="<?= $editDest['rating']??4.5 ?>"></div>
            <div class="col-md-3"><label class="form-label">Tour Count</label><input class="form-control" type="number" name="tour_count" value="<?= $editDest['tour_count']??0 ?>"></div>
            <div class="col-12"><label class="form-label">Image URL (Unsplash)</label><input class="form-control" name="image_url" value="<?= htmlspecialchars($editDest['image_url']??'') ?>"></div>
            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($editDest['description']??'') ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Status</label>
              <select class="form-select" name="active">
                <option value="1" <?= ($editDest['active']??1)?'selected':'' ?>>Active</option>
                <option value="0" <?= !($editDest['active']??1)?'selected':'' ?>>Hidden</option>
              </select>
            </div>
            <div class="col-12 d-flex gap-2 mt-2">
              <button type="submit" class="btn-adm"><i class="bi bi-save"></i> Save Destination</button>
              <a href="?section=destinations" class="btn-adm" style="background:rgba(107,114,128,0.1);color:#6b7280;box-shadow:none;">Cancel</a>
            </div>
          </div>
        </form>
      </div>
      <?php endif; ?>

      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Country</th><th>Region</th><th>Temp</th><th>Rating</th><th>Tours</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php if($dests && $dests->num_rows>0): while($d=$dests->fetch_assoc()): ?>
            <tr>
              <td><?= $d['id'] ?></td>
              <td><?php if($d['image_url']): ?><img src="<?= htmlspecialchars($d['image_url']) ?>" class="adm-thumb" onerror="this.style.display='none'"><?php else: ?>—<?php endif; ?></td>
              <td><strong><?= htmlspecialchars($d['name']) ?></strong></td>
              <td><?= htmlspecialchars($d['country']) ?></td>
              <td><?= htmlspecialchars($d['region']) ?></td>
              <td><?= $d['temperature'] ?>°C</td>
              <td>⭐ <?= $d['rating'] ?></td>
              <td><?= $d['tour_count'] ?></td>
              <td><?= ($d['active']??1) ? '<span class="badge-active">Active</span>' : '<span class="badge-inactive">Hidden</span>' ?></td>
              <td>
                <a href="?section=destinations&action=edit&id=<?= $d['id'] ?>" class="btn-edit-sm">Edit</a>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this destination?')">
                  <input type="hidden" name="_action" value="delete_dest"><input type="hidden" name="id" value="<?= $d['id'] ?>">
                  <button class="btn-danger-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="10" style="text-align:center;color:#9ca3af;padding:2rem;">No destinations yet</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

    <?php // ══════════════════════ HOTELS ══════════════════════
    elseif ($section === 'hotels'):
        $editHotel = null;
        if ($action === 'edit' && $id > 0) {
            try { $editHotel = $conn->query("SELECT * FROM hotels WHERE id=$id")->fetch_assoc(); } catch(Exception $e){}
        }
        try { $hotels = $conn->query("SELECT * FROM hotels ORDER BY created_at DESC"); } catch(Exception $e){ $hotels=null; }
    ?>
      <div class="adm-page-hd">
        <h4><i class="bi bi-building"></i> Hotels Management</h4>
        <a href="?section=hotels&action=add" class="btn-adm"><i class="bi bi-plus-lg"></i> Add Hotel</a>
      </div>

      <?php if ($action === 'edit' || $action === 'add'): ?>
      <div class="adm-form-card">
        <h5><i class="bi bi-<?= $action==='edit'?'pencil':'plus-circle' ?>"></i> <?= $action==='edit'?'Edit':'Add New' ?> Hotel</h5>
        <form method="POST" action="?section=hotels">
          <input type="hidden" name="_action" value="save_hotel">
          <input type="hidden" name="id" value="<?= $editHotel['id'] ?? 0 ?>">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Hotel Name *</label><input class="form-control" name="name" value="<?= htmlspecialchars($editHotel['name']??'') ?>" required></div>
            <div class="col-md-6"><label class="form-label">Destination *</label><input class="form-control" name="destination" value="<?= htmlspecialchars($editHotel['destination']??'') ?>" required></div>
            <div class="col-md-4"><label class="form-label">Price per Night (₹)</label><input class="form-control" type="number" name="price_per_night" value="<?= $editHotel['price_per_night']??'' ?>" required></div>
            <div class="col-md-4"><label class="form-label">Rating (1-5)</label><input class="form-control" type="number" step="0.1" name="rating" value="<?= $editHotel['rating']??4.5 ?>"></div>
            <div class="col-md-4"><label class="form-label">Stars</label>
              <select class="form-select" name="stars">
                <?php for($s=3;$s<=5;$s++): ?><option value="<?=$s?>" <?= ($editHotel['stars']??5)==$s?'selected':'' ?>><?=$s?>★</option><?php endfor; ?>
              </select>
            </div>
            <div class="col-12"><label class="form-label">Amenities (comma-separated)</label><input class="form-control" name="amenities" value="<?= htmlspecialchars($editHotel['amenities']??'') ?>" placeholder="Pool, WiFi, Spa, Restaurant, Gym"></div>
            <div class="col-12"><label class="form-label">Image URL</label><input class="form-control" name="image_url" value="<?= htmlspecialchars($editHotel['image_url']??'') ?>"></div>
            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($editHotel['description']??'') ?></textarea></div>
            <div class="col-md-4"><label class="form-label">Status</label>
              <select class="form-select" name="active">
                <option value="1" <?= ($editHotel['active']??1)?'selected':'' ?>>Active</option>
                <option value="0" <?= !($editHotel['active']??1)?'selected':'' ?>>Hidden</option>
              </select>
            </div>
            <div class="col-12 d-flex gap-2 mt-2">
              <button type="submit" class="btn-adm"><i class="bi bi-save"></i> Save Hotel</button>
              <a href="?section=hotels" class="btn-adm" style="background:rgba(107,114,128,0.1);color:#6b7280;box-shadow:none;">Cancel</a>
            </div>
          </div>
        </form>
      </div>
      <?php endif; ?>

      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>ID</th><th>Image</th><th>Name</th><th>Destination</th><th>/Night</th><th>Stars</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php if($hotels && $hotels->num_rows>0): while($h=$hotels->fetch_assoc()): ?>
            <tr>
              <td><?= $h['id'] ?></td>
              <td><?php if($h['image_url']): ?><img src="<?= htmlspecialchars($h['image_url']) ?>" class="adm-thumb" onerror="this.style.display='none'"><?php else: ?>—<?php endif; ?></td>
              <td><strong><?= htmlspecialchars($h['name']) ?></strong></td>
              <td><?= htmlspecialchars($h['destination']) ?></td>
              <td>₹<?= number_format($h['price_per_night']) ?></td>
              <td><?= str_repeat('★',$h['stars']) ?></td>
              <td>⭐ <?= $h['rating'] ?></td>
              <td><?= $h['active'] ? '<span class="badge-active">Active</span>' : '<span class="badge-inactive">Hidden</span>' ?></td>
              <td>
                <a href="?section=hotels&action=edit&id=<?= $h['id'] ?>" class="btn-edit-sm">Edit</a>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this hotel?')">
                  <input type="hidden" name="_action" value="delete_hotel"><input type="hidden" name="id" value="<?= $h['id'] ?>">
                  <button class="btn-danger-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="9" style="text-align:center;color:#9ca3af;padding:2rem;">No hotels yet — <a href="?section=hotels&action=add">Add one</a></td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

    <?php // ══════════════════════ BOOKINGS ══════════════════════
    elseif ($section === 'bookings'):
        try { $allBookings = $conn->query("SELECT b.*,u.name as uname,u.email FROM bookings b JOIN users u ON b.user_id=u.id ORDER BY b.created_at DESC"); } catch(Exception $e){ $allBookings=null; }
    ?>
      <div class="adm-page-hd"><h4><i class="bi bi-ticket-perforated"></i> All Bookings</h4></div>
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>#</th><th>User</th><th>Tour / Hotel</th><th>Check-In</th><th>Guests</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php if($allBookings && $allBookings->num_rows>0): while($b=$allBookings->fetch_assoc()): ?>
            <tr>
              <td><?= $b['id'] ?></td>
              <td><strong><?= htmlspecialchars($b['uname']) ?></strong><br><span style="color:#9ca3af;font-size:0.72rem;"><?= htmlspecialchars($b['email']) ?></span></td>
              <td><?= htmlspecialchars($b['hotel_name'] ?? 'Tour Package') ?></td>
              <td><?= $b['check_in'] ? date('d M Y', strtotime($b['check_in'])) : '—' ?></td>
              <td><?= $b['guests'] ?></td>
              <td><strong>₹<?= number_format($b['total_price']) ?></strong></td>
              <td>
                <form method="POST" style="display:inline-flex; align-items:center; gap:6px;">
                  <input type="hidden" name="_action" value="update_booking">
                  <input type="hidden" name="id" value="<?= $b['id'] ?>">
                  <select name="status" class="form-select" style="padding:3px 8px;font-size:0.75rem;border-radius:8px;height:auto;" onchange="this.form.submit()">
                    <?php foreach(['pending','confirmed','cancelled','completed'] as $st): ?>
                    <option value="<?=$st?>" <?= $b['status']===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </td>
              <td>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this booking?')">
                  <input type="hidden" name="_action" value="delete_booking"><input type="hidden" name="id" value="<?= $b['id'] ?>">
                  <button class="btn-danger-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:2rem;">No bookings yet</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

    <?php // ══════════════════════ USERS ══════════════════════
    elseif ($section === 'users'):
        $users = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
    ?>
      <div class="adm-page-hd"><h4><i class="bi bi-people"></i> Users Management</h4><span style="color:#9ca3af;font-size:0.82rem;"><?= $stats['users'] ?> registered</span></div>
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
          <tbody>
          <?php while($u=$users->fetch_assoc()): ?>
            <tr>
              <td><?= $u['id'] ?></td>
              <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
              <td><span class="badge-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span></td>
              <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
              <td>
                <?php if($u['id'] !== $user['id']): ?>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="_action" value="toggle_admin">
                  <input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <input type="hidden" name="role" value="<?= $u['role'] ?>">
                  <button class="btn-edit-sm" style="font-size:0.72rem;"><?= $u['role']==='admin'?'Make User':'Make Admin' ?></button>
                </form>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete user <?= addslashes($u['name']) ?>?')">
                  <input type="hidden" name="_action" value="delete_user"><input type="hidden" name="id" value="<?= $u['id'] ?>">
                  <button class="btn-danger-sm">Delete</button>
                </form>
                <?php else: ?><span style="color:#9ca3af;font-size:0.75rem;">You</span><?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    <?php // ══════════════════════ JOURNAL ══════════════════════
    elseif ($section === 'journal'):
        try { $journals = $conn->query("SELECT j.*,u.name as uname FROM journal_entries j JOIN users u ON j.user_id=u.id ORDER BY j.created_at DESC"); } catch(Exception $e){ $journals=null; }
    ?>
      <div class="adm-page-hd"><h4><i class="bi bi-journal-text"></i> Journal Entries</h4></div>
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>#</th><th>User</th><th>Title</th><th>Mood</th><th>Location</th><th>Date</th><th>Preview</th><th>Actions</th></tr></thead>
          <tbody>
          <?php if($journals && $journals->num_rows>0): while($j=$journals->fetch_assoc()): ?>
            <tr>
              <td><?= $j['id'] ?></td>
              <td><strong><?= htmlspecialchars($j['uname']) ?></strong></td>
              <td><?= htmlspecialchars($j['title']) ?></td>
              <td><span class="badge-confirmed"><?= htmlspecialchars($j['mood']) ?></span></td>
              <td><?= htmlspecialchars($j['location'] ?? '—') ?></td>
              <td><?= date('d M Y', strtotime($j['created_at'])) ?></td>
              <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#9ca3af;font-size:0.78rem;"><?= htmlspecialchars(substr($j['content'],0,80)) ?>…</td>
              <td>
                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this entry?')">
                  <input type="hidden" name="_action" value="delete_journal"><input type="hidden" name="id" value="<?= $j['id'] ?>">
                  <button class="btn-danger-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="8" style="text-align:center;color:#9ca3af;padding:2rem;">No journal entries yet</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

    <?php endif; ?>
  </main>
</div>

<?php include 'includes/footer.php'; ?>