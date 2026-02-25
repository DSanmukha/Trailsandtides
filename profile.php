<?php include 'includes/db.php';
if(!isset($_SESSION['user'])) { header("Location: auth/login.php"); exit; }

$user = $_SESSION['user'];
$success = '';
$error = '';

if(isset($_POST['update_profile'])){
    $name  = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $bio   = $conn->real_escape_string($_POST['bio']);
    $conn->query("UPDATE users SET name='$name', phone='$phone', bio='$bio' WHERE id={$user['id']}");
    $result = $conn->query("SELECT * FROM users WHERE id={$user['id']}");
    $_SESSION['user'] = $result->fetch_assoc();
    $user = $_SESSION['user'];
    $success = 'Profile updated successfully!';
}

if(isset($_POST['change_password'])){
    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];
    if(!password_verify($current, $user['password'])){
        $error = 'Current password is incorrect';
    } elseif($new !== $confirm){
        $error = 'New passwords do not match';
    } elseif(strlen($new) < 6){
        $error = 'Password must be at least 6 characters';
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed' WHERE id={$user['id']}");
        $result = $conn->query("SELECT * FROM users WHERE id={$user['id']}");
        $_SESSION['user'] = $result->fetch_assoc();
        $user = $_SESSION['user'];
        $success = 'Password changed successfully!';
    }
}

$bookings_count = 0; $journal_count = 0;
try { $bookings_count = $conn->query("SELECT COUNT(*) as c FROM bookings WHERE user_id={$user['id']}")->fetch_assoc()['c'] ?? 0; } catch(Exception $e) {}
try { $journal_count  = $conn->query("SELECT COUNT(*) as c FROM journal_entries WHERE user_id={$user['id']}")->fetch_assoc()['c'] ?? 0; } catch(Exception $e) {}

include 'includes/header.php';
?>

<style>
body { background: #f5f3ff; }

/* ── Profile Hero ── */
.profile-hero {
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    padding: 160px 0 70px; position: relative; overflow: hidden; margin-top: -76px;
}
.profile-hero::before {
    content:''; position:absolute; top:-80px; right:-80px;
    width:400px; height:400px; border-radius:50%;
    background:rgba(139,92,246,0.18); filter:blur(80px); pointer-events:none;
}
.profile-hero::after {
    content:''; position:absolute; bottom:-60px; left:-60px;
    width:300px; height:300px; border-radius:50%;
    background:rgba(192,132,252,0.12); filter:blur(60px); pointer-events:none;
}

/* Avatar */
.profile-avatar {
    width: 110px; height: 110px; border-radius: 50%;
    background: linear-gradient(135deg, #8b5cf6, #c084fc);
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 2.8rem; font-weight: 800;
    border: 4px solid rgba(255,255,255,0.25);
    margin: 0 auto 1.2rem; position: relative; z-index: 1;
    box-shadow: 0 8px 32px rgba(139,92,246,0.35);
}
.profile-name { color: white; font-family: 'Playfair Display'; font-size: 2.2rem; font-weight: 700; margin-bottom: 4px; }
.profile-meta { color: rgba(255,255,255,0.55); font-size: 0.85rem; margin-bottom: 4px; }
.profile-badge {
    display: inline-block; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18);
    color: rgba(255,255,255,0.75); border-radius: 50px; padding: 4px 16px; font-size: 0.75rem;
    letter-spacing: 0.5px; backdrop-filter: blur(8px); margin-top: 4px;
}

/* Stats bar */
.profile-stats-bar {
    display: flex; gap: 0; justify-content: center; margin-top: 2rem;
    border-top: 1px solid rgba(255,255,255,0.08); padding-top: 1.75rem;
}
.profile-stat {
    text-align: center; padding: 0 2.5rem;
    border-right: 1px solid rgba(255,255,255,0.1);
}
.profile-stat:last-child { border-right: none; }
.profile-stat-num  { color: white; font-weight: 800; font-size: 1.8rem; line-height: 1; margin-bottom: 4px; }
.profile-stat-label { color: rgba(255,255,255,0.45); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 500; }

/* ── Section cards ── */
.profile-body { padding: 2.5rem 0 3rem; }
.p-card {
    background: white; border-radius: 22px;
    border: 1px solid rgba(139,92,246,0.08);
    box-shadow: 0 4px 24px rgba(139,92,246,0.06);
    padding: 2rem; margin-bottom: 1.5rem;
}
.p-card-title {
    font-family: 'Playfair Display'; color: #1e1b4b; font-size: 1.15rem;
    font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;
    padding-bottom: 1rem; border-bottom: 1px solid rgba(139,92,246,0.07);
}
.p-card-title i { font-size: 1rem; }
.p-card label { color: #9ca3af; font-size: 0.78rem; font-weight: 600; margin-bottom: 5px; display: block; letter-spacing: 0.3px; text-transform: uppercase; }
.p-card .form-control {
    border: 1.5px solid rgba(139,92,246,0.15); border-radius: 12px;
    padding: 11px 15px; font-size: 0.9rem; font-family: 'Poppins';
    background: #fdfcff; transition: all 0.25s; color: #1e1b4b;
}
.p-card .form-control:focus { border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(139,92,246,0.07); outline: none; }
.p-card .form-control:disabled { background: #f9f9fb; color: #9ca3af; }

/* Buttons */
.btn-save {
    background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white;
    border: none; border-radius: 13px; padding: 13px 32px; font-weight: 600;
    font-size: 0.9rem; cursor: pointer; transition: all 0.3s; font-family: 'Poppins';
    display: inline-flex; align-items: center; gap: 8px; margin-top: 0.5rem;
}
.btn-save:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(139,92,246,0.35); color: white; }
.btn-save-outline {
    background: transparent; color: #8b5cf6;
    border: 1.5px solid rgba(139,92,246,0.3); border-radius: 13px;
    padding: 13px 32px; font-weight: 600; font-size: 0.9rem; cursor: pointer;
    transition: all 0.3s; font-family: 'Poppins'; width: 100%;
    display: inline-flex; align-items: center; justify-content: center; gap: 8px; margin-top: 0.5rem;
}
.btn-save-outline:hover { background: rgba(139,92,246,0.06); border-color: #8b5cf6; transform: translateY(-2px); }

/* Quick Links */
.quick-link {
    display: flex; align-items: center; gap: 12px; padding: 11px 14px;
    border-radius: 13px; text-decoration: none; color: #1e1b4b;
    background: rgba(139,92,246,0.04); border: 1px solid rgba(139,92,246,0.06);
    transition: all 0.25s ease; margin-bottom: 8px; font-size: 0.88rem; font-weight: 500;
}
.quick-link:hover { background: rgba(139,92,246,0.08); border-color: rgba(139,92,246,0.15); transform: translateX(4px); color: #1e1b4b; text-decoration: none; }
.quick-link .ql-icon { width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; }
.quick-link span { flex: 1; }
.quick-link .bi-chevron-right { color: #d1d5db; font-size: 0.75rem; }

/* Alerts */
.alert-success { background: linear-gradient(135deg, rgba(34,197,94,0.08), rgba(16,185,129,0.05)); border: 1px solid rgba(34,197,94,0.2); color: #16a34a; border-radius: 14px; padding: 14px 18px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }
.alert-error   { background: rgba(220,38,38,0.08); border: 1px solid rgba(220,38,38,0.2); color: #dc2626; border-radius: 14px; padding: 14px 18px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; }
</style>

<!-- Hero -->
<div class="profile-hero">
    <div class="container text-center" style="position:relative; z-index:1;">
        <div class="profile-avatar"><?= strtoupper(substr($user['name'],0,1)) ?></div>
        <h1 class="profile-name"><?= htmlspecialchars($user['name']) ?></h1>
        <p class="profile-meta"><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($user['email']) ?></p>
        <span class="profile-badge">
            <i class="bi bi-calendar3 me-1"></i> Member since <?= date('M Y', strtotime($user['created_at'] ?? 'now')) ?>
        </span>

        <div class="profile-stats-bar">
            <div class="profile-stat">
                <div class="profile-stat-num"><?= $bookings_count ?></div>
                <div class="profile-stat-label">Bookings</div>
            </div>
            <div class="profile-stat">
                <div class="profile-stat-num"><?= $journal_count ?></div>
                <div class="profile-stat-label">Journal Entries</div>
            </div>
            <div class="profile-stat">
                <div class="profile-stat-num">6</div>
                <div class="profile-stat-label">Destinations</div>
            </div>
        </div>
    </div>
</div>

<!-- Body -->
<div class="profile-body">
  <div class="container">

    <?php if($success): ?>
    <div class="alert-success"><i class="bi bi-check-circle-fill fs-5"></i> <?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
    <div class="alert-error"><i class="bi bi-exclamation-circle-fill fs-5"></i> <?= $error ?></div>
    <?php endif; ?>

    <div class="row g-4">

      <!-- Personal Info -->
      <div class="col-lg-8">
        <div class="p-card">
          <div class="p-card-title">
              <i class="bi bi-person-fill" style="color:#8b5cf6;"></i> Personal Information
          </div>
          <form method="post">
            <div class="row g-3">
              <div class="col-md-6">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
              </div>
              <div class="col-md-6">
                <label>Email Address</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
              </div>
              <div class="col-md-6">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="e.g. +91 98765 43210">
              </div>
              <div class="col-md-6">
                <label>Member Since</label>
                <input type="text" class="form-control" value="<?= date('d M Y', strtotime($user['created_at'] ?? 'now')) ?>" disabled>
              </div>
              <div class="col-12">
                <label>Bio</label>
                <textarea name="bio" class="form-control" rows="3" placeholder="Tell us about your travel style..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
              </div>
              <div class="col-12">
                <button type="submit" name="update_profile" class="btn-save">
                    <i class="bi bi-check2-circle"></i> Save Changes
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">

        <!-- Change Password -->
        <div class="p-card">
          <div class="p-card-title">
              <i class="bi bi-shield-lock-fill" style="color:#8b5cf6;"></i> Change Password
          </div>
          <form method="post">
            <div class="mb-3">
              <label>Current Password</label>
              <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
            </div>
            <div class="mb-3">
              <label>New Password</label>
              <input type="password" name="new_password" class="form-control" placeholder="Min. 6 characters" required minlength="6">
            </div>
            <div class="mb-3">
              <label>Confirm New Password</label>
              <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="change_password" class="btn-save-outline">
                <i class="bi bi-lock-fill"></i> Update Password
            </button>
          </form>
        </div>

        <!-- Quick Links -->
        <div class="p-card">
          <div class="p-card-title">
              <i class="bi bi-lightning-fill" style="color:#f97316;"></i> Quick Links
          </div>
          <a href="dashboard.php" class="quick-link">
              <div class="ql-icon" style="background:rgba(139,92,246,0.1);"><i class="bi bi-speedometer2" style="color:#8b5cf6;"></i></div>
              <span>Dashboard</span><i class="bi bi-chevron-right"></i>
          </a>
          <a href="journal.php" class="quick-link">
              <div class="ql-icon" style="background:rgba(249,115,22,0.1);"><i class="bi bi-journal-text" style="color:#f97316;"></i></div>
              <span>Travel Journal</span><i class="bi bi-chevron-right"></i>
          </a>
          <a href="tours.php" class="quick-link">
              <div class="ql-icon" style="background:rgba(6,182,212,0.1);"><i class="bi bi-compass" style="color:#06b6d4;"></i></div>
              <span>Browse Tours</span><i class="bi bi-chevron-right"></i>
          </a>
          <a href="hotels.php" class="quick-link">
              <div class="ql-icon" style="background:rgba(34,197,94,0.1);"><i class="bi bi-building" style="color:#22c55e;"></i></div>
              <span>Find Hotels</span><i class="bi bi-chevron-right"></i>
          </a>
        </div>

      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>