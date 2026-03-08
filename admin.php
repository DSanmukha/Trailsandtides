<?php
include 'includes/db.php';

// ─── EMAIL-BASED ACCESS CONTROL ─────────────────────────────────
define('ADMIN_EMAIL', 'admin@trailsandtides.com');
if (!isset($_SESSION['user']) || $_SESSION['user']['email'] !== ADMIN_EMAIL) {
    header("Location: index.php"); exit;
}

// ─── CREATE HOTELS TABLE IF NOT EXISTS ──────────────────────────
$conn->query("CREATE TABLE IF NOT EXISTS hotels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    destination VARCHAR(100),
    description TEXT,
    price_per_night DECIMAL(12,2),
    original_price DECIMAL(12,2),
    rating DECIMAL(2,1) DEFAULT 4.5,
    image_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// ─── IMAGE UPLOAD HELPER ────────────────────────────────────────
function uploadImage($file, $folder) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK || $file['size'] === 0) return '';
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif'];
    if (!in_array($ext, $allowed)) return '';
    $name = pathinfo($file['name'], PATHINFO_FILENAME);
    $name = preg_replace('/[^a-zA-Z0-9_-]/', '-', $name);
    $filename = $name . '-' . time() . '.' . $ext;
    $dir = "assets/images/$folder/";
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    move_uploaded_file($file['tmp_name'], $dir . $filename);
    return $dir . $filename;
}

// ─── HANDLE DELETES FIRST (then redirect) ───────────────────────
if (isset($_GET['delete_hotel'])) {
    $id = intval($_GET['delete_hotel']);
    $conn->query("DELETE FROM hotels WHERE id=$id");
    header("Location: admin.php?tab=hotels&msg=deleted"); exit;
}
if (isset($_GET['delete_tour'])) {
    $id = intval($_GET['delete_tour']);
    $conn->query("DELETE FROM tours WHERE id=$id");
    header("Location: admin.php?tab=tours&msg=deleted"); exit;
}
if (isset($_GET['delete_destination'])) {
    $id = intval($_GET['delete_destination']);
    $conn->query("DELETE FROM destinations WHERE id=$id");
    header("Location: admin.php?tab=destinations&msg=deleted"); exit;
}

// ─── HANDLE FORM SUBMISSIONS ────────────────────────────────────
$msg = ''; $msg_type = '';
if (isset($_GET['msg'])) { $msg = 'Operation completed successfully!'; $msg_type = 'success'; }

// Hotels
if (isset($_POST['add_hotel'])) {
    $img = uploadImage($_FILES['image'] ?? null, 'hotels');
    $stmt = $conn->prepare("INSERT INTO hotels (name, destination, description, price_per_night, original_price, rating, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssddds", $_POST['name'], $_POST['destination'], $_POST['description'], $_POST['price_per_night'], $_POST['original_price'], $_POST['rating'], $img);
    $stmt->execute(); $stmt->close();
    $msg = 'Hotel added!'; $msg_type = 'success';
}
if (isset($_POST['edit_hotel'])) {
    $img = uploadImage($_FILES['image'] ?? null, 'hotels');
    if (!$img) $img = $_POST['existing_image'] ?? '';
    $stmt = $conn->prepare("UPDATE hotels SET name=?, destination=?, description=?, price_per_night=?, original_price=?, rating=?, image_url=? WHERE id=?");
    $stmt->bind_param("sssdddsi", $_POST['name'], $_POST['destination'], $_POST['description'], $_POST['price_per_night'], $_POST['original_price'], $_POST['rating'], $img, $_POST['id']);
    $stmt->execute(); $stmt->close();
    $msg = 'Hotel updated!'; $msg_type = 'success';
}

// Tours
if (isset($_POST['add_tour'])) {
    $img = uploadImage($_FILES['image'] ?? null, 'tours');
    $stmt = $conn->prepare("INSERT INTO tours (title, destination, description, duration, price, original_price, rating, reviews, tag, group_type, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssidddisss", $_POST['title'], $_POST['destination'], $_POST['description'], $_POST['duration'], $_POST['price'], $_POST['original_price'], $_POST['rating'], $_POST['reviews'], $_POST['tag'], $_POST['group_type'], $img);
    $stmt->execute(); $stmt->close();
    $msg = 'Tour added!'; $msg_type = 'success';
}
if (isset($_POST['edit_tour'])) {
    $img = uploadImage($_FILES['image'] ?? null, 'tours');
    if (!$img) $img = $_POST['existing_image'] ?? '';
    $stmt = $conn->prepare("UPDATE tours SET title=?, destination=?, description=?, duration=?, price=?, original_price=?, rating=?, reviews=?, tag=?, group_type=?, image_url=? WHERE id=?");
    $stmt->bind_param("sssidddisssi", $_POST['title'], $_POST['destination'], $_POST['description'], $_POST['duration'], $_POST['price'], $_POST['original_price'], $_POST['rating'], $_POST['reviews'], $_POST['tag'], $_POST['group_type'], $img, $_POST['id']);
    $stmt->execute(); $stmt->close();
    $msg = 'Tour updated!'; $msg_type = 'success';
}

// Destinations
if (isset($_POST['add_destination'])) {
    $img = uploadImage($_FILES['image'] ?? null, 'destinations');
    $stmt = $conn->prepare("INSERT INTO destinations (name, country, region, description, weather, temperature, rating, tour_count, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssidis", $_POST['name'], $_POST['country'], $_POST['region'], $_POST['description'], $_POST['weather'], $_POST['temperature'], $_POST['rating'], $_POST['tour_count'], $img);
    $stmt->execute(); $stmt->close();
    $msg = 'Destination added!'; $msg_type = 'success';
}
if (isset($_POST['edit_destination'])) {
    $img = uploadImage($_FILES['image'] ?? null, 'destinations');
    if (!$img) $img = $_POST['existing_image'] ?? '';
    $stmt = $conn->prepare("UPDATE destinations SET name=?, country=?, region=?, description=?, weather=?, temperature=?, rating=?, tour_count=?, image_url=? WHERE id=?");
    $stmt->bind_param("sssssidisi", $_POST['name'], $_POST['country'], $_POST['region'], $_POST['description'], $_POST['weather'], $_POST['temperature'], $_POST['rating'], $_POST['tour_count'], $img, $_POST['id']);
    $stmt->execute(); $stmt->close();
    $msg = 'Destination updated!'; $msg_type = 'success';
}

// ─── FETCH DATA ─────────────────────────────────────────────────
$hotels = $conn->query("SELECT * FROM hotels ORDER BY id DESC");
$tours  = $conn->query("SELECT * FROM tours ORDER BY id DESC");
$dests  = $conn->query("SELECT * FROM destinations ORDER BY id DESC");

$hotel_count = $hotels ? $hotels->num_rows : 0;
$tour_count  = $tours  ? $tours->num_rows  : 0;
$dest_count  = $dests  ? $dests->num_rows  : 0;
$booking_count = 0;
$bookings = null;
try {
    $bookings = $conn->query("SELECT b.*, u.name AS user_name, u.email AS user_email FROM bookings b JOIN users u ON b.user_id = u.id ORDER BY b.id DESC");
    $booking_count = $bookings ? $bookings->num_rows : 0;
} catch(Exception $e) {}

$active_tab = $_GET['tab'] ?? 'hotels';
?>
<?php include("includes/header.php"); ?>

<style>
body { background: #f5f3ff; }
.admin-hero { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); padding: 140px 0 45px; margin-top: -76px; }
.admin-stats { margin-top: -30px; position: relative; z-index: 10; }
.admin-stat { background: white; border-radius: 16px; padding: 1.2rem 1.3rem; border: 1px solid rgba(139,92,246,0.06); box-shadow: 0 4px 16px rgba(0,0,0,0.04); }
.admin-stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: white; margin-bottom: 0.6rem; }
.admin-stat-num { color: #1e1b4b; font-weight: 800; font-size: 1.8rem; line-height: 1; }
.admin-stat-label { color: #9ca3af; font-size: 0.75rem; font-weight: 500; }
.admin-tabs { display: flex; gap: 4px; background: white; border-radius: 14px; padding: 5px; border: 1px solid rgba(139,92,246,0.06); margin-bottom: 1.2rem; }
.admin-tab { flex: 1; text-align: center; padding: 10px; border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; text-decoration: none; color: #6b7280; border: none; background: none; }
.admin-tab:hover { background: rgba(139,92,246,0.05); color: #1e1b4b; }
.admin-tab.active { background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; }
.admin-card { background: white; border-radius: 18px; padding: 1.3rem; border: 1px solid rgba(139,92,246,0.06); box-shadow: 0 2px 12px rgba(0,0,0,0.03); margin-bottom: 1.2rem; }
.admin-table { width: 100%; border-collapse: separate; border-spacing: 0; }
.admin-table thead th { background: rgba(139,92,246,0.03); color: #6b7280; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 14px; border-bottom: 1px solid rgba(139,92,246,0.06); }
.admin-table thead th:first-child { border-radius: 10px 0 0 0; }
.admin-table thead th:last-child { border-radius: 0 10px 0 0; }
.admin-table tbody td { padding: 12px 14px; border-bottom: 1px solid rgba(139,92,246,0.03); color: #1e1b4b; font-size: 0.85rem; vertical-align: middle; }
.admin-table tbody tr:hover { background: rgba(139,92,246,0.015); }
.admin-table tbody tr:last-child td { border-bottom: none; }
.img-thumb { width: 48px; height: 34px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(139,92,246,0.08); }
.btn-admin-add { background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; border: none; border-radius: 10px; padding: 8px 18px; font-weight: 600; font-size: 0.82rem; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-family: 'Poppins'; }
.btn-edit { background: rgba(59,130,246,0.08); color: #3b82f6; border: none; border-radius: 6px; padding: 5px 10px; font-size: 0.75rem; font-weight: 600; cursor: pointer; font-family: 'Poppins'; }
.btn-delete { background: rgba(239,68,68,0.08); color: #ef4444; border: none; border-radius: 6px; padding: 5px 10px; font-size: 0.75rem; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; font-family: 'Poppins'; }
.btn-delete:hover { color: #ef4444; }
.admin-modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,10,46,0.5); backdrop-filter: blur(5px); z-index: 9999; justify-content: center; align-items: center; }
.admin-modal-overlay.show { display: flex; }
.admin-modal { background: white; border-radius: 20px; width: 100%; max-width: 520px; max-height: 88vh; overflow-y: auto; padding: 1.8rem; box-shadow: 0 20px 50px rgba(0,0,0,0.2); animation: mIn 0.25s ease; }
@keyframes mIn { from { opacity: 0; transform: translateY(16px); } }
.admin-modal h3 { color: #1e1b4b; font-weight: 700; font-size: 1.1rem; margin-bottom: 1.2rem; display: flex; align-items: center; gap: 8px; }
.admin-modal label { color: #6b7280; font-size: 0.78rem; font-weight: 500; display: block; margin-bottom: 3px; }
.admin-modal input, .admin-modal textarea, .admin-modal select { width: 100%; padding: 9px 12px; border: 1px solid rgba(139,92,246,0.12); border-radius: 10px; font-family: 'Poppins'; font-size: 0.85rem; color: #1e1b4b; margin-bottom: 0.8rem; box-sizing: border-box; }
.admin-modal input:focus, .admin-modal textarea:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.08); }
.admin-modal input[type="file"] { padding: 8px; background: rgba(139,92,246,0.03); }
.admin-modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 0.5rem; }
.btn-modal-cancel { padding: 9px 20px; border: 1px solid rgba(139,92,246,0.15); border-radius: 10px; background: white; color: #1e1b4b; font-weight: 600; cursor: pointer; font-family: 'Poppins'; font-size: 0.85rem; }
.btn-modal-save { padding: 9px 24px; border: none; border-radius: 10px; background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; font-weight: 600; cursor: pointer; font-family: 'Poppins'; font-size: 0.85rem; }
.admin-alert { border-radius: 12px; padding: 12px 16px; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 500; }
.admin-alert.success { background: rgba(34,197,94,0.06); border: 1px solid rgba(34,197,94,0.15); color: #16a34a; }
.empty-state { text-align: center; padding: 2.5rem 1rem; color: #9ca3af; }
.empty-state i { font-size: 2.5rem; display: block; margin-bottom: 10px; opacity: 0.25; }
.tab-panel { display: none; }
.tab-panel.active { display: block; }
.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 0 12px; }
</style>

<div class="admin-hero">
  <div class="container" style="position:relative; z-index:1;">
    <span style="background:rgba(239,68,68,0.12); color:#fca5a5; padding:3px 10px; border-radius:6px; font-size:0.7rem; font-weight:700;">🔒 ADMIN</span>
    <h1 style="font-family:'Playfair Display'; color:white; font-size:2.2rem; font-weight:800; margin:6px 0 2px;">Admin Dashboard</h1>
    <p style="color:rgba(255,255,255,0.4); font-size:0.85rem;">Manage hotels, tours, and destinations</p>
  </div>
</div>

<div style="background:#f5f3ff; padding:0 0 2.5rem;">
  <div class="container">
    <?php if($msg): ?>
    <div class="admin-alert success mt-3"><i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="admin-stats">
      <div class="row g-3">
        <?php
        $stats = [
          ['Hotels', $hotel_count, '#8b5cf6,#c084fc', 'bi-building'],
          ['Tours', $tour_count, '#f97316,#fb923c', 'bi-compass'],
          ['Destinations', $dest_count, '#06b6d4,#22d3ee', 'bi-geo-alt-fill'],
          ['Bookings', $booking_count, '#22c55e,#4ade80', 'bi-bookmark-check'],
        ];
        foreach($stats as $s): ?>
        <div class="col-6 col-md-3">
          <div class="admin-stat">
            <div class="admin-stat-icon" style="background:linear-gradient(135deg,<?= $s[2] ?>);"><i class="bi <?= $s[3] ?>"></i></div>
            <div class="admin-stat-num"><?= $s[1] ?></div>
            <div class="admin-stat-label"><?= $s[0] ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="admin-tabs mt-3">
      <a href="admin.php?tab=hotels" class="admin-tab <?= $active_tab=='hotels'?'active':'' ?>"><i class="bi bi-building me-1"></i>Hotels</a>
      <a href="admin.php?tab=tours" class="admin-tab <?= $active_tab=='tours'?'active':'' ?>"><i class="bi bi-compass me-1"></i>Tours</a>
      <a href="admin.php?tab=destinations" class="admin-tab <?= $active_tab=='destinations'?'active':'' ?>"><i class="bi bi-geo-alt me-1"></i>Destinations</a>
      <a href="admin.php?tab=bookings" class="admin-tab <?= $active_tab=='bookings'?'active':'' ?>"><i class="bi bi-bookmark-check me-1"></i>Bookings</a>
    </div>

    <!-- ════ HOTELS ════ -->
    <div class="tab-panel <?= $active_tab=='hotels'?'active':'' ?>">
      <div class="admin-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
          <h5 style="color:#1e1b4b; font-weight:700; margin:0;"><i class="bi bi-building" style="color:#8b5cf6;"></i> Manage Hotels</h5>
          <button class="btn-admin-add" onclick="openModal('hotelModal','add')"><i class="bi bi-plus-lg"></i> Add Hotel</button>
        </div>
        <?php if($hotel_count > 0): ?>
        <div style="overflow-x:auto;">
          <table class="admin-table">
            <thead><tr><th>Image</th><th>Name</th><th>Destination</th><th>Price/Night</th><th>Rating</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $hotels->data_seek(0); while($h = $hotels->fetch_assoc()): ?>
              <tr>
                <td><?php if($h['image_url']): ?><img src="<?= htmlspecialchars($h['image_url']) ?>" class="img-thumb" alt="" onerror="this.style.display='none'"><?php else: ?>—<?php endif; ?></td>
                <td><strong><?= htmlspecialchars($h['name']) ?></strong></td>
                <td><?= htmlspecialchars($h['destination']) ?></td>
                <td>₹<?= number_format($h['price_per_night']) ?></td>
                <td>⭐ <?= $h['rating'] ?></td>
                <td style="white-space:nowrap;">
                  <button class="btn-edit" onclick='editHotel(<?= json_encode($h) ?>)'><i class="bi bi-pencil"></i> Edit</button>
                  <a href="admin.php?tab=hotels&delete_hotel=<?= $h['id'] ?>" class="btn-delete" onclick="return confirm('Delete?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="bi bi-building"></i><p>No hotels yet.</p></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ════ TOURS ════ -->
    <div class="tab-panel <?= $active_tab=='tours'?'active':'' ?>">
      <div class="admin-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
          <h5 style="color:#1e1b4b; font-weight:700; margin:0;"><i class="bi bi-compass" style="color:#f97316;"></i> Manage Tours</h5>
          <button class="btn-admin-add" onclick="openModal('tourModal','add')"><i class="bi bi-plus-lg"></i> Add Tour</button>
        </div>
        <?php if($tour_count > 0): ?>
        <div style="overflow-x:auto;">
          <table class="admin-table">
            <thead><tr><th>Image</th><th>Title</th><th>Destination</th><th>Duration</th><th>Price</th><th>Rating</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $tours->data_seek(0); while($t = $tours->fetch_assoc()): ?>
              <tr>
                <td><?php if($t['image_url']): ?><img src="<?= htmlspecialchars($t['image_url']) ?>" class="img-thumb" alt="" onerror="this.style.display='none'"><?php else: ?>—<?php endif; ?></td>
                <td><strong><?= htmlspecialchars($t['title']) ?></strong></td>
                <td><?= htmlspecialchars($t['destination']) ?></td>
                <td><?= $t['duration'] ?> days</td>
                <td>₹<?= number_format($t['price']) ?></td>
                <td>⭐ <?= $t['rating'] ?></td>
                <td style="white-space:nowrap;">
                  <button class="btn-edit" onclick='editTour(<?= json_encode($t) ?>)'><i class="bi bi-pencil"></i> Edit</button>
                  <a href="admin.php?tab=tours&delete_tour=<?= $t['id'] ?>" class="btn-delete" onclick="return confirm('Delete?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="bi bi-compass"></i><p>No tours yet.</p></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ════ DESTINATIONS ════ -->
    <div class="tab-panel <?= $active_tab=='destinations'?'active':'' ?>">
      <div class="admin-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
          <h5 style="color:#1e1b4b; font-weight:700; margin:0;"><i class="bi bi-geo-alt" style="color:#06b6d4;"></i> Manage Destinations</h5>
          <button class="btn-admin-add" onclick="openModal('destModal','add')"><i class="bi bi-plus-lg"></i> Add Destination</button>
        </div>
        <?php if($dest_count > 0): ?>
        <div style="overflow-x:auto;">
          <table class="admin-table">
            <thead><tr><th>Image</th><th>Name</th><th>Country</th><th>Region</th><th>Rating</th><th>Tours</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $dests->data_seek(0); while($d = $dests->fetch_assoc()): ?>
              <tr>
                <td><?php if($d['image_url']): ?><img src="<?= htmlspecialchars($d['image_url']) ?>" class="img-thumb" alt="" onerror="this.style.display='none'"><?php else: ?>—<?php endif; ?></td>
                <td><strong><?= htmlspecialchars($d['name']) ?></strong></td>
                <td><?= htmlspecialchars($d['country']) ?></td>
                <td><?= htmlspecialchars($d['region'] ?? '') ?></td>
                <td>⭐ <?= $d['rating'] ?></td>
                <td><span style="background:rgba(139,92,246,0.08); color:#8b5cf6; padding:2px 8px; border-radius:12px; font-size:0.75rem; font-weight:600;"><?= $d['tour_count'] ?></span></td>
                <td style="white-space:nowrap;">
                  <button class="btn-edit" onclick='editDest(<?= json_encode($d) ?>)'><i class="bi bi-pencil"></i> Edit</button>
                  <a href="admin.php?tab=destinations&delete_destination=<?= $d['id'] ?>" class="btn-delete" onclick="return confirm('Delete?')"><i class="bi bi-trash"></i></a>
                </td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="bi bi-geo-alt"></i><p>No destinations yet.</p></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- ════ BOOKINGS ════ -->
    <div class="tab-panel <?= $active_tab=='bookings'?'active':'' ?>">
      <div class="admin-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
          <h5 style="color:#1e1b4b; font-weight:700; margin:0;"><i class="bi bi-bookmark-check" style="color:#22c55e;"></i> All Bookings</h5>
          <span style="background:rgba(34,197,94,0.08); color:#16a34a; padding:4px 12px; border-radius:8px; font-size:0.75rem; font-weight:600;"><?= $booking_count ?> total</span>
        </div>
        <?php if($booking_count > 0): ?>
        <div style="overflow-x:auto;">
          <table class="admin-table">
            <thead><tr><th>#</th><th>User</th><th>Email</th><th>Booked Item</th><th>Check-in</th><th>Guests</th><th>Total</th><th>Status</th><th>Booked On</th></tr></thead>
            <tbody>
            <?php $bookings->data_seek(0); while($b = $bookings->fetch_assoc()): ?>
              <tr>
                <td><?= $b['id'] ?></td>
                <td><strong><?= htmlspecialchars($b['user_name']) ?></strong></td>
                <td><a href="mailto:<?= htmlspecialchars($b['user_email']) ?>" style="color:#8b5cf6; text-decoration:none;"><?= htmlspecialchars($b['user_email']) ?></a></td>
                <td><?= htmlspecialchars($b['hotel_name'] ?? '—') ?></td>
                <td><?= $b['check_in'] ? date('d M Y', strtotime($b['check_in'])) : '—' ?></td>
                <td><?= $b['guests'] ?></td>
                <td><strong>₹<?= number_format($b['total_price']) ?></strong></td>
                <td>
                  <?php
                  $sc = ['confirmed'=>'#22c55e','pending'=>'#f59e0b','cancelled'=>'#ef4444','completed'=>'#3b82f6'];
                  $col = $sc[$b['status']] ?? '#6b7280';
                  ?>
                  <span style="background:<?= $col ?>15; color:<?= $col ?>; padding:2px 10px; border-radius:12px; font-size:0.72rem; font-weight:600; text-transform:capitalize;"><?= $b['status'] ?></span>
                </td>
                <td style="color:#9ca3af; font-size:0.78rem;"><?= date('d M Y, h:i A', strtotime($b['created_at'])) ?></td>
              </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="empty-state"><i class="bi bi-bookmark"></i><p>No bookings yet.</p></div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- ══════ MODALS ══════ -->

<!-- Hotel Modal -->
<div class="admin-modal-overlay" id="hotelModal">
  <div class="admin-modal">
    <h3><i class="bi bi-building" style="color:#8b5cf6;"></i> <span id="hotelModalTitle">Add Hotel</span></h3>
    <form method="POST" action="admin.php?tab=hotels" enctype="multipart/form-data">
      <input type="hidden" name="id" id="hotel_id">
      <input type="hidden" name="existing_image" id="hotel_existing_image">
      <label>Hotel Name *</label>
      <input type="text" name="name" id="hotel_name" required placeholder="e.g. Bali Beach Resort">
      <label>Destination</label>
      <input type="text" name="destination" id="hotel_destination" placeholder="e.g. Bali, Indonesia">
      <label>Description</label>
      <textarea name="description" id="hotel_description" rows="2" placeholder="Luxury beachfront resort..."></textarea>
      <div class="two-col">
        <div><label>Price/Night (₹)</label><input type="number" name="price_per_night" id="hotel_price" step="0.01"></div>
        <div><label>Original Price (₹)</label><input type="number" name="original_price" id="hotel_original" step="0.01"></div>
      </div>
      <label>Rating (1-5)</label>
      <input type="number" name="rating" id="hotel_rating" step="0.1" min="1" max="5">
      <label>Image (upload from your computer)</label>
      <input type="file" name="image" accept="image/*">
      <p id="hotel_img_note" style="color:#9ca3af; font-size:0.75rem; margin:-0.5rem 0 0.8rem;"></p>
      <div class="admin-modal-actions">
        <button type="button" class="btn-modal-cancel" onclick="closeModal('hotelModal')">Cancel</button>
        <button type="submit" name="add_hotel" id="hotelSubmitBtn" class="btn-modal-save">Save Hotel</button>
      </div>
    </form>
  </div>
</div>

<!-- Tour Modal -->
<div class="admin-modal-overlay" id="tourModal">
  <div class="admin-modal">
    <h3><i class="bi bi-compass" style="color:#f97316;"></i> <span id="tourModalTitle">Add Tour</span></h3>
    <form method="POST" action="admin.php?tab=tours" enctype="multipart/form-data">
      <input type="hidden" name="id" id="tour_id">
      <input type="hidden" name="existing_image" id="tour_existing_image">
      <label>Tour Title *</label>
      <input type="text" name="title" id="tour_title" required placeholder="e.g. Bali Paradise Experience">
      <label>Destination</label>
      <input type="text" name="destination" id="tour_destination" placeholder="e.g. Bali, Indonesia">
      <label>Description</label>
      <textarea name="description" id="tour_description" rows="2"></textarea>
      <div class="two-col">
        <div><label>Duration (days)</label><input type="number" name="duration" id="tour_duration"></div>
        <div><label>Tag</label><input type="text" name="tag" id="tour_tag" placeholder="POPULAR"></div>
      </div>
      <div class="two-col">
        <div><label>Price (₹)</label><input type="number" name="price" id="tour_price" step="0.01"></div>
        <div><label>Original Price (₹)</label><input type="number" name="original_price" id="tour_original" step="0.01"></div>
      </div>
      <div class="two-col">
        <div><label>Rating</label><input type="number" name="rating" id="tour_rating" step="0.1" min="1" max="5"></div>
        <div><label>Reviews</label><input type="number" name="reviews" id="tour_reviews"></div>
      </div>
      <label>Group Type</label>
      <input type="text" name="group_type" id="tour_group" placeholder="e.g. Group Tours">
      <label>Image (upload)</label>
      <input type="file" name="image" accept="image/*">
      <p id="tour_img_note" style="color:#9ca3af; font-size:0.75rem; margin:-0.5rem 0 0.8rem;"></p>
      <div class="admin-modal-actions">
        <button type="button" class="btn-modal-cancel" onclick="closeModal('tourModal')">Cancel</button>
        <button type="submit" name="add_tour" id="tourSubmitBtn" class="btn-modal-save">Save Tour</button>
      </div>
    </form>
  </div>
</div>

<!-- Destination Modal -->
<div class="admin-modal-overlay" id="destModal">
  <div class="admin-modal">
    <h3><i class="bi bi-geo-alt" style="color:#06b6d4;"></i> <span id="destModalTitle">Add Destination</span></h3>
    <form method="POST" action="admin.php?tab=destinations" enctype="multipart/form-data">
      <input type="hidden" name="id" id="dest_id">
      <input type="hidden" name="existing_image" id="dest_existing_image">
      <label>Destination Name *</label>
      <input type="text" name="name" id="dest_name" required placeholder="e.g. Bali">
      <div class="two-col">
        <div><label>Country</label><input type="text" name="country" id="dest_country"></div>
        <div><label>Region</label><input type="text" name="region" id="dest_region"></div>
      </div>
      <label>Description</label>
      <textarea name="description" id="dest_description" rows="2"></textarea>
      <div class="two-col">
        <div><label>Weather</label><input type="text" name="weather" id="dest_weather" placeholder="Sunny"></div>
        <div><label>Temperature (°C)</label><input type="number" name="temperature" id="dest_temp"></div>
      </div>
      <div class="two-col">
        <div><label>Rating</label><input type="number" name="rating" id="dest_rating" step="0.1" min="1" max="5"></div>
        <div><label>Tour Count</label><input type="number" name="tour_count" id="dest_tours"></div>
      </div>
      <label>Image (upload)</label>
      <input type="file" name="image" accept="image/*">
      <p id="dest_img_note" style="color:#9ca3af; font-size:0.75rem; margin:-0.5rem 0 0.8rem;"></p>
      <div class="admin-modal-actions">
        <button type="button" class="btn-modal-cancel" onclick="closeModal('destModal')">Cancel</button>
        <button type="submit" name="add_destination" id="destSubmitBtn" class="btn-modal-save">Save Destination</button>
      </div>
    </form>
  </div>
</div>

<script>
function openModal(id, mode) {
    const m = document.getElementById(id);
    if (mode === 'add') {
        m.querySelector('form').reset();
        m.querySelectorAll('input[type=hidden]').forEach(h => h.value = '');
        if (id==='hotelModal') { document.getElementById('hotelModalTitle').textContent='Add Hotel'; document.getElementById('hotelSubmitBtn').name='add_hotel'; document.getElementById('hotel_img_note').textContent=''; }
        if (id==='tourModal') { document.getElementById('tourModalTitle').textContent='Add Tour'; document.getElementById('tourSubmitBtn').name='add_tour'; document.getElementById('tour_img_note').textContent=''; }
        if (id==='destModal') { document.getElementById('destModalTitle').textContent='Add Destination'; document.getElementById('destSubmitBtn').name='add_destination'; document.getElementById('dest_img_note').textContent=''; }
    }
    m.classList.add('show');
    document.body.style.overflow='hidden';
}
function closeModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow=''; }
document.querySelectorAll('.admin-modal-overlay').forEach(o => { o.addEventListener('click', e => { if(e.target===o) closeModal(o.id); }); });

function editHotel(h) {
    document.getElementById('hotelModalTitle').textContent='Edit Hotel';
    document.getElementById('hotel_id').value=h.id;
    document.getElementById('hotel_name').value=h.name||'';
    document.getElementById('hotel_destination').value=h.destination||'';
    document.getElementById('hotel_description').value=h.description||'';
    document.getElementById('hotel_price').value=h.price_per_night||'';
    document.getElementById('hotel_original').value=h.original_price||'';
    document.getElementById('hotel_rating').value=h.rating||'';
    document.getElementById('hotel_existing_image').value=h.image_url||'';
    document.getElementById('hotel_img_note').textContent=h.image_url ? 'Current: '+h.image_url : '';
    document.getElementById('hotelSubmitBtn').name='edit_hotel';
    openModal('hotelModal');
}
function editTour(t) {
    document.getElementById('tourModalTitle').textContent='Edit Tour';
    document.getElementById('tour_id').value=t.id;
    document.getElementById('tour_title').value=t.title||'';
    document.getElementById('tour_destination').value=t.destination||'';
    document.getElementById('tour_description').value=t.description||'';
    document.getElementById('tour_duration').value=t.duration||'';
    document.getElementById('tour_price').value=t.price||'';
    document.getElementById('tour_original').value=t.original_price||'';
    document.getElementById('tour_rating').value=t.rating||'';
    document.getElementById('tour_reviews').value=t.reviews||'';
    document.getElementById('tour_tag').value=t.tag||'';
    document.getElementById('tour_group').value=t.group_type||'';
    document.getElementById('tour_existing_image').value=t.image_url||'';
    document.getElementById('tour_img_note').textContent=t.image_url ? 'Current: '+t.image_url : '';
    document.getElementById('tourSubmitBtn').name='edit_tour';
    openModal('tourModal');
}
function editDest(d) {
    document.getElementById('destModalTitle').textContent='Edit Destination';
    document.getElementById('dest_id').value=d.id;
    document.getElementById('dest_name').value=d.name||'';
    document.getElementById('dest_country').value=d.country||'';
    document.getElementById('dest_region').value=d.region||'';
    document.getElementById('dest_description').value=d.description||'';
    document.getElementById('dest_weather').value=d.weather||'';
    document.getElementById('dest_temp').value=d.temperature||'';
    document.getElementById('dest_rating').value=d.rating||'';
    document.getElementById('dest_tours').value=d.tour_count||'';
    document.getElementById('dest_existing_image').value=d.image_url||'';
    document.getElementById('dest_img_note').textContent=d.image_url ? 'Current: '+d.image_url : '';
    document.getElementById('destSubmitBtn').name='edit_destination';
    openModal('destModal');
}
</script>

<?php include("includes/footer.php"); ?>