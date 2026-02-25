<?php include 'includes/db.php';
if(!isset($_SESSION['user'])) { header("Location: auth/login.php"); exit; }
$user = $_SESSION['user'];

$bookings = false; $bookings_count = 0; $journal_count = 0; $dest_count = 6;
try {
    $bookings = $conn->query("SELECT * FROM bookings WHERE user_id={$user['id']} ORDER BY created_at DESC LIMIT 5");
    $bookings_count = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE user_id={$user['id']}")->fetch_assoc()['c'] ?? 0;
} catch(Exception $e) {}
try {
    $journal_count = $conn->query("SELECT COUNT(*) as c FROM journal_entries WHERE user_id={$user['id']}")->fetch_assoc()['c'] ?? 0;
} catch(Exception $e) {}
$first_name = htmlspecialchars(explode(' ',$user['name'])[0]);
$hour = intval(date('H'));
$greeting = $hour < 12 ? 'Good Morning' : ($hour < 17 ? 'Good Afternoon' : 'Good Evening');
?>
<?php include("includes/header.php"); ?>

<style>
body { background: #f5f3ff; }

/* ── Hero ── */
.dash-hero {
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    padding: 160px 0 50px; margin-top: -76px; position: relative; overflow: hidden;
}
.dash-hero::before {
    content:''; position:absolute; top:-100px; right:-80px;
    width:500px; height:500px; border-radius:50%;
    background:rgba(139,92,246,0.15); filter:blur(90px); pointer-events:none;
}
.dash-hero::after {
    content:''; position:absolute; bottom:-80px; left:10%;
    width:350px; height:350px; border-radius:50%;
    background:rgba(192,132,252,0.1); filter:blur(70px); pointer-events:none;
}
.dash-hero-inner { position:relative; z-index:1; }
.dash-greeting { color:rgba(255,255,255,0.55); font-size:0.85rem; letter-spacing:1px; text-transform:uppercase; margin-bottom:6px; }
.dash-title { font-family:'Playfair Display'; color:white; font-size:2.6rem; font-weight:800; margin-bottom:6px; }
.dash-subtitle { color:rgba(255,255,255,0.45); font-size:0.9rem; }

/* ── Stat Cards floating from hero ── */
.stats-grid { margin-top: -40px; position: relative; z-index: 10; margin-bottom: 2rem; }
.stat-card {
    background: white; border-radius: 20px; padding: 1.5rem 1.6rem;
    border: 1px solid rgba(139,92,246,0.07);
    box-shadow: 0 8px 30px rgba(139,92,246,0.1);
    transition: all 0.35s ease; height: 100%;
}
.stat-card:hover { transform: translateY(-6px); box-shadow: 0 20px 45px rgba(139,92,246,0.15); }
.stat-card-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
.stat-icon {
    width:52px; height:52px; border-radius:15px;
    display:flex; align-items:center; justify-content:center;
    font-size:1.35rem; color:white;
}
.stat-trend { font-size:0.72rem; font-weight:600; padding:3px 10px; border-radius:20px; }
.stat-trend.up   { background:rgba(34,197,94,0.12); color:#16a34a; }
.stat-trend.zero { background:rgba(139,92,246,0.1); color:#8b5cf6; }
.stat-num   { color:#1e1b4b; font-weight:800; font-size:2.2rem; line-height:1; margin-bottom:4px; }
.stat-label { color:#9ca3af; font-size:0.8rem; font-weight:500; margin-bottom:0.6rem; }
.stat-link  { color:#8b5cf6; font-size:0.78rem; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:4px; transition:gap 0.2s; }
.stat-link:hover { gap:8px; color:#8b5cf6; }

/* ── Section header ── */
.section-hd { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.25rem; }
.section-hd h6 { color:#1e1b4b; font-weight:700; font-size:1rem; margin:0; display:flex; align-items:center; gap:8px; }
.section-hd h6 i { color:#8b5cf6; }
.section-hd a { color:#8b5cf6; font-size:0.78rem; font-weight:600; text-decoration:none; }

/* ── Content Cards ── */
.c-card {
    background:white; border-radius:20px; padding:1.5rem;
    border:1px solid rgba(139,92,246,0.07);
    box-shadow:0 4px 20px rgba(139,92,246,0.05);
    margin-bottom:1.5rem;
}

/* ── Booking Row ── */
.booking-row {
    display:flex; align-items:center; gap:14px; padding:12px 14px;
    border-radius:14px; margin-bottom:8px;
    background:rgba(139,92,246,0.025); border:1px solid rgba(139,92,246,0.05);
    transition:all 0.25s;
}
.booking-row:hover { background:rgba(139,92,246,0.06); transform:translateX(4px); }
.b-icon { width:40px; height:40px; border-radius:12px; background:linear-gradient(135deg,#8b5cf6,#c084fc); display:flex; align-items:center; justify-content:center; color:white; flex-shrink:0; }
.b-info h6 { color:#1e1b4b; font-size:0.87rem; margin:0; font-weight:600; }
.b-info p  { color:#9ca3af; font-size:0.74rem; margin:0; }
.b-price { margin-left:auto; color:#1e1b4b; font-weight:700; font-size:0.88rem; white-space:nowrap; }
.b-status { padding:3px 9px; border-radius:8px; font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; }
.status-confirmed { background:rgba(34,197,94,0.1); color:#16a34a; }
.status-pending   { background:rgba(249,115,22,0.1); color:#f97316; }

/* ── Action Cards ── */
.action-card {
    background:white; border-radius:18px; padding:1.5rem; text-align:center;
    border:1px solid rgba(139,92,246,0.07);
    box-shadow:0 4px 20px rgba(139,92,246,0.05);
    transition:all 0.35s; text-decoration:none; display:block; color:inherit;
    height:100%;
}
.action-card:hover { transform:translateY(-8px); box-shadow:0 20px 40px rgba(139,92,246,0.13); border-color:rgba(139,92,246,0.2); color:inherit; text-decoration:none; }
.action-icon { width:62px; height:62px; border-radius:18px; margin:0 auto 1rem; display:flex; align-items:center; justify-content:center; font-size:1.5rem; transition:transform 0.35s; }
.action-card:hover .action-icon { transform:scale(1.12) rotate(-5deg); }
.action-card h6 { color:#1e1b4b; font-weight:700; margin-bottom:4px; font-size:0.9rem; }
.action-card p  { color:#9ca3af; font-size:0.78rem; margin:0; }

/* ── Mini progress strip ── */
.progress-trip {
    background:white; border-radius:18px; padding:1.4rem 1.5rem;
    border:1px solid rgba(139,92,246,0.07);
    box-shadow:0 4px 20px rgba(139,92,246,0.05);
    margin-bottom:1rem; display:flex; align-items:center; gap:14px;
}
.progress-trip-icon { width:44px; height:44px; border-radius:13px; flex-shrink:0; display:flex; align-items:center; justify-content:center; color:white; font-size:1.1rem; }
.progress-trip h6 { color:#1e1b4b; font-size:0.88rem; font-weight:700; margin:0 0 3px; }
.progress-trip p  { color:#9ca3af; font-size:0.74rem; margin:0; }
.trip-badge { margin-left:auto; padding:4px 12px; border-radius:20px; font-size:0.7rem; font-weight:700; white-space:nowrap; }

/* ── Alert ── */
.dash-alert { background:linear-gradient(135deg,rgba(34,197,94,0.08),rgba(16,185,129,0.05)); border:1px solid rgba(34,197,94,0.2); color:#16a34a; border-radius:14px; padding:14px 18px; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px; }
</style>

<!-- Hero -->
<div class="dash-hero">
  <div class="container dash-hero-inner">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <div class="dash-greeting">✈️ <?= $greeting ?></div>
        <h1 class="dash-title">Welcome back, <?= $first_name ?>! 👋</h1>
        <p class="dash-subtitle">Here's an overview of your travel adventures</p>
      </div>
      <div class="col-lg-4 d-none d-lg-flex justify-content-end">
        <a href="tours.php" style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:white; border-radius:14px; padding:12px 24px; text-decoration:none; font-weight:600; font-size:0.9rem; backdrop-filter:blur(8px); transition:all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.18)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
            <i class="bi bi-compass me-2"></i>Explore Tours
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Main -->
<div style="background:#f5f3ff; padding:0 0 3rem;">
  <div class="container">

    <?php if(isset($_GET['booked'])): ?>
    <div class="dash-alert mt-3">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div><strong>Booking confirmed!</strong> Your trip to <?= htmlspecialchars($_GET['tour'] ?? '') ?> is all set.</div>
    </div>
    <?php endif; ?>

    <!-- Floating stat cards -->
    <div class="stats-grid">
      <div class="row g-3">
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-card-top">
              <div class="stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#c084fc);">
                <i class="bi bi-airplane-fill"></i>
              </div>
              <span class="stat-trend zero">Trips</span>
            </div>
            <div class="stat-num"><?= $bookings_count ?></div>
            <div class="stat-label">Total Bookings</div>
            <a href="tours.php" class="stat-link">Book a tour <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-card-top">
              <div class="stat-icon" style="background:linear-gradient(135deg,#f97316,#fb923c);">
                <i class="bi bi-journal-text"></i>
              </div>
              <span class="stat-trend zero">Stories</span>
            </div>
            <div class="stat-num"><?= $journal_count ?></div>
            <div class="stat-label">Journal Entries</div>
            <a href="journal.php" class="stat-link">Write an entry <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-card">
            <div class="stat-card-top">
              <div class="stat-icon" style="background:linear-gradient(135deg,#06b6d4,#22d3ee);">
                <i class="bi bi-geo-alt-fill"></i>
              </div>
              <span class="stat-trend up">↑ New</span>
            </div>
            <div class="stat-num"><?= $dest_count ?></div>
            <div class="stat-label">Destinations</div>
            <a href="destinations.php" class="stat-link">Explore all <i class="bi bi-arrow-right"></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-4">

      <!-- Left col: Recent bookings + Quick actions -->
      <div class="col-lg-7">

        <!-- Recent Bookings -->
        <div class="c-card">
          <div class="section-hd">
            <h6><i class="bi bi-airplane-fill"></i> Recent Bookings</h6>
            <a href="tours.php">View all →</a>
          </div>
          <?php if($bookings && $bookings->num_rows > 0): ?>
            <?php while($b = $bookings->fetch_assoc()): ?>
            <div class="booking-row">
              <div class="b-icon"><i class="bi bi-airplane"></i></div>
              <div class="b-info">
                <h6><?= htmlspecialchars($b['hotel_name'] ?? 'Tour Package') ?></h6>
                <p><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($b['check_in'] ?? $b['created_at'])) ?> · <?= $b['guests'] ?> Guest<?= $b['guests'] > 1 ? 's' : '' ?></p>
              </div>
              <span class="b-price">₹<?= number_format($b['total_price']) ?></span>
              <span class="b-status status-<?= $b['status'] ?>"><?= $b['status'] ?></span>
            </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div style="text-align:center; padding:2.5rem; color:#9ca3af;">
              <i class="bi bi-airplane" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.3;"></i>
              <p style="margin:0 0 8px; font-weight:600; color:#6b7280;">No bookings yet</p>
              <a href="tours.php" style="color:#8b5cf6; font-weight:600; font-size:0.88rem;">Start exploring tours →</a>
            </div>
          <?php endif; ?>
        </div>

        <!-- Quick Actions -->
        <div class="section-hd mt-2 mb-3">
          <h6><i class="bi bi-grid-fill"></i> Quick Actions</h6>
        </div>
        <div class="row g-3">
          <div class="col-4">
            <a href="tours.php" class="action-card">
              <div class="action-icon" style="background:rgba(139,92,246,0.1);">
                <i class="bi bi-compass" style="color:#8b5cf6;"></i>
              </div>
              <h6>Tours</h6>
              <p>Plan a trip</p>
            </a>
          </div>
          <div class="col-4">
            <a href="journal.php" class="action-card">
              <div class="action-icon" style="background:rgba(249,115,22,0.1);">
                <i class="bi bi-journal-text" style="color:#f97316;"></i>
              </div>
              <h6>Journal</h6>
              <p>Document it</p>
            </a>
          </div>
          <div class="col-4">
            <a href="hotels.php" class="action-card">
              <div class="action-icon" style="background:rgba(6,182,212,0.1);">
                <i class="bi bi-building" style="color:#06b6d4;"></i>
              </div>
              <h6>Hotels</h6>
              <p>Find stays</p>
            </a>
          </div>
        </div>

      </div>

      <!-- Right col: Destinations + Profile card -->
      <div class="col-lg-5">

        <!-- Top destinations -->
        <div class="c-card mb-4">
          <div class="section-hd">
            <h6><i class="bi bi-geo-alt-fill"></i> Top Destinations</h6>
            <a href="destinations.php">See all →</a>
          </div>
          <div class="progress-trip">
            <div class="progress-trip-icon" style="background:linear-gradient(135deg,#047857,#065f46);"><i class="bi bi-tree"></i></div>
            <div>
              <h6>Bali, Indonesia</h6>
              <p>Island paradise · 4.9 ★</p>
            </div>
            <span class="trip-badge" style="background:rgba(4,120,87,0.1); color:#047857;">Tropical</span>
          </div>
          <div class="progress-trip">
            <div class="progress-trip-icon" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);"><i class="bi bi-building"></i></div>
            <div>
              <h6>Paris, France</h6>
              <p>City of light · 4.8 ★</p>
            </div>
            <span class="trip-badge" style="background:rgba(251,191,36,0.1); color:#d97706;">Culture</span>
          </div>
          <div class="progress-trip">
            <div class="progress-trip-icon" style="background:linear-gradient(135deg,#f472b6,#ec4899);"><i class="bi bi-sun"></i></div>
            <div>
              <h6>Tokyo, Japan</h6>
              <p>Urban adventure · 4.7 ★</p>
            </div>
            <span class="trip-badge" style="background:rgba(244,114,182,0.1); color:#db2777;">Urban</span>
          </div>
        </div>

        <!-- Profile summary -->
        <div class="c-card" style="background:linear-gradient(135deg,#1e1b4b,#312e81); border-color:rgba(139,92,246,0.2);">
          <div style="display:flex; align-items:center; gap:14px; margin-bottom:1.25rem;">
            <div style="width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,#8b5cf6,#c084fc); display:flex; align-items:center; justify-content:center; color:white; font-size:1.4rem; font-weight:800; border:3px solid rgba(255,255,255,0.15);">
              <?= strtoupper(substr($user['name'],0,1)) ?>
            </div>
            <div>
              <div style="color:white; font-weight:700; font-size:0.95rem;"><?= htmlspecialchars($user['name']) ?></div>
              <div style="color:rgba(255,255,255,0.45); font-size:0.78rem;"><?= htmlspecialchars($user['email']) ?></div>
            </div>
          </div>
          <a href="profile.php" style="display:block; text-align:center; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); color:white; border-radius:12px; padding:11px; text-decoration:none; font-weight:600; font-size:0.88rem; transition:all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.18)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
            <i class="bi bi-person-gear me-2"></i>Edit Profile
          </a>
        </div>

      </div>
    </div>

  </div>
</div>

<?php include("includes/footer.php"); ?>
