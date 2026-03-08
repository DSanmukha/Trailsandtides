<?php include 'includes/db.php';
if(!isset($_SESSION['user'])) { header("Location: auth/login.php"); exit; }
$user = $_SESSION['user'];

if(isset($_POST['save'])){
    $title   = $conn->real_escape_string($_POST['title']   ?? 'Untitled');
    $content = $conn->real_escape_string($_POST['content']);
    $mood    = $conn->real_escape_string($_POST['mood']    ?? 'happy');
    $location= $conn->real_escape_string($_POST['location']?? '');
    $conn->query("INSERT INTO journal_entries (user_id, title, content, mood, location) VALUES ({$user['id']}, '$title', '$content', '$mood', '$location')");
    header("Location: journal.php?saved=1"); exit;
}
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM journal_entries WHERE id=$id AND user_id={$user['id']}");
    header("Location: journal.php"); exit;
}
$entries = false; $journal_count = 0;
try {
    $entries = $conn->query("SELECT * FROM journal_entries WHERE user_id={$user['id']} ORDER BY created_at DESC");
    $journal_count = $conn->query("SELECT COUNT(*) as c FROM journal_entries WHERE user_id={$user['id']}")->fetch_assoc()['c'] ?? 0;
} catch(Exception $e) {}
?>
<?php include("includes/header.php"); ?>

<style>
body { background: #f5f3ff; }

/* ── Hero ── */
.journal-hero {
    background: linear-gradient(135deg, rgba(30,27,75,0.92), rgba(76,29,149,0.82)),
                url('assets/images/hero.jpg') center/cover;
    padding: 160px 0 80px; margin-top: -76px; text-align: center;
}
.journal-hero .hero-badge {
    display: inline-block; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.85); border-radius: 50px; padding: 5px 18px;
    font-size: 0.8rem; letter-spacing: 1px; margin-bottom: 1rem; backdrop-filter: blur(8px);
}
.journal-hero h1 { font-family: 'Playfair Display'; color: white; font-size: 2.6rem; font-weight: 800; margin-bottom: 6px; }
.journal-hero p  { color: rgba(255,255,255,0.65); font-size: 1rem; }

/* ── Write Card ── */
.write-card {
    background: white; border-radius: 22px;
    border: 1px solid rgba(139,92,246,0.08);
    box-shadow: 0 4px 24px rgba(139,92,246,0.07);
    padding: 2rem; margin-bottom: 2rem;
}
.write-card-title {
    font-family: 'Playfair Display'; color: #1e1b4b; font-size: 1.3rem;
    font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;
}
.write-card-title i { color: #8b5cf6; font-size: 1rem; }
.write-card .form-control {
    border: 1.5px solid rgba(139,92,246,0.15); border-radius: 14px;
    padding: 12px 16px; font-size: 0.9rem; font-family: 'Poppins';
    background: #fdfcff; transition: all 0.25s;
}
.write-card .form-control:focus {
    border-color: #8b5cf6; box-shadow: 0 0 0 4px rgba(139,92,246,0.07); outline: none;
}

/* ── Mood Pills ── */
.mood-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 1rem; }
.mood-pill {
    padding: 7px 16px; border-radius: 50px;
    border: 1.5px solid rgba(139,92,246,0.15);
    background: #fdfcff; cursor: pointer; font-size: 0.82rem; color: #6b7280;
    transition: all 0.25s ease; user-select: none;
}
.mood-pill:hover  { border-color: #8b5cf6; color: #8b5cf6; background: rgba(139,92,246,0.05); }
.mood-pill.active { background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; border-color: transparent; }
.mood-pill input  { display: none; }

/* ── Save Button ── */
.btn-save {
    background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white;
    border: none; border-radius: 14px; padding: 13px 32px; font-weight: 600;
    font-size: 0.92rem; cursor: pointer; transition: all 0.3s ease; font-family: 'Poppins';
    display: inline-flex; align-items: center; gap: 8px;
}
.btn-save:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(139,92,246,0.35); }

/* ── Entries Section ── */
.entries-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1.25rem;
}
.entries-header h5 {
    font-family: 'Playfair Display'; color: #1e1b4b; font-size: 1.3rem;
    font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px;
}
.entries-header h5 i { color: #8b5cf6; font-size: 1rem; }
.entry-count-badge {
    background: rgba(139,92,246,0.1); color: #8b5cf6; border-radius: 50px;
    padding: 3px 12px; font-size: 0.75rem; font-weight: 700;
}

/* ── Entry Card ── */
.entry-card {
    background: white; border-radius: 18px;
    border: 1px solid rgba(139,92,246,0.08);
    box-shadow: 0 2px 12px rgba(139,92,246,0.05);
    padding: 1.4rem 1.5rem; margin-bottom: 1rem; transition: all 0.3s ease;
    position: relative;
}
.entry-card:hover { transform: translateY(-4px); box-shadow: 0 14px 30px rgba(139,92,246,0.1); border-color: rgba(139,92,246,0.18); }
.entry-title { color: #1e1b4b; font-weight: 700; font-size: 1rem; margin-bottom: 6px; }
.entry-meta {
    display: flex; gap: 12px; color: #9ca3af; font-size: 0.78rem;
    margin-bottom: 10px; flex-wrap: wrap; align-items: center;
}
.mood-tag {
    background: rgba(139,92,246,0.1); color: #8b5cf6;
    padding: 2px 12px; border-radius: 20px; font-weight: 600; font-size: 0.74rem;
}
.entry-content { color: #6b7280; font-size: 0.88rem; line-height: 1.7; }
.entry-delete {
    position: absolute; top: 1.25rem; right: 1.25rem;
    color: #d1d5db; text-decoration: none; font-size: 0.82rem; transition: color 0.2s;
    width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
    border-radius: 8px; background: rgba(239,68,68,0.05);
}
.entry-delete:hover { color: #ef4444; background: rgba(239,68,68,0.1); }
</style>

<!-- Hero -->
<div class="journal-hero">
    <div class="container">
        <div class="hero-badge">✍️ YOUR TRAVEL JOURNAL</div>
        <h1>Travel Journal</h1>
        <p>Document your adventures and relive every moment</p>
    </div>
</div>

<!-- Content -->
<div style="background:#f5f3ff; padding:2rem 0 3rem;">
  <div class="container">

    <?php if(isset($_GET['saved'])): ?>
    <div style="background:linear-gradient(135deg,rgba(34,197,94,0.08),rgba(16,185,129,0.05)); border:1px solid rgba(34,197,94,0.2); color:#16a34a; border-radius:14px; padding:14px 20px; margin-bottom:1.5rem; display:flex; align-items:center; gap:10px;">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <span>Journal entry saved successfully!</span>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Write Entry -->
        <div class="col-lg-5">
            <div class="write-card">
                <div class="write-card-title">
                    <i class="bi bi-pencil-square"></i> New Entry
                </div>
                <form method="post">
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Entry title — e.g. Sunset at Uluwatu" required>
                    </div>
                    <div class="mood-pills mb-3">
                        <label class="mood-pill active"><input type="radio" name="mood" value="happy" checked> 😊 Happy</label>
                        <label class="mood-pill"><input type="radio" name="mood" value="excited"> 🤩 Excited</label>
                        <label class="mood-pill"><input type="radio" name="mood" value="peaceful"> 😌 Peaceful</label>
                        <label class="mood-pill"><input type="radio" name="mood" value="adventurous"> 🏔️ Adventurous</label>
                        <label class="mood-pill"><input type="radio" name="mood" value="nostalgic"> 🥺 Nostalgic</label>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="location" class="form-control" placeholder="📍 Location (optional)">
                    </div>
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="5" placeholder="Write about your experience..." required></textarea>
                    </div>
                    <button type="submit" name="save" class="btn-save">
                        <i class="bi bi-save2"></i> Save Entry
                    </button>
                </form>
            </div>
        </div>

        <!-- Past Entries -->
        <div class="col-lg-7">
            <div class="entries-header">
                <h5><i class="bi bi-journal-bookmark-fill"></i> Your Entries</h5>
                <?php if($journal_count > 0): ?>
                <span class="entry-count-badge"><?= $journal_count ?> <?= $journal_count == 1 ? 'entry' : 'entries' ?></span>
                <?php endif; ?>
            </div>

            <?php if($entries && $entries->num_rows > 0): ?>
                <?php while($e = $entries->fetch_assoc()): ?>
                <div class="entry-card">
                    <a href="journal.php?delete=<?= $e['id'] ?>" class="entry-delete" onclick="return confirm('Delete this entry?')">
                        <i class="bi bi-trash3"></i>
                    </a>
                    <div class="entry-title"><?= htmlspecialchars($e['title']) ?></div>
                    <div class="entry-meta">
                        <span><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($e['created_at'])) ?></span>
                        <?php if($e['location']): ?><span><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($e['location']) ?></span><?php endif; ?>
                        <span class="mood-tag"><?= ucfirst($e['mood']) ?></span>
                    </div>
                    <div class="entry-content"><?= nl2br(htmlspecialchars($e['content'])) ?></div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align:center; padding:4rem 2rem; background:white; border-radius:22px; border:1px solid rgba(139,92,246,0.08);">
                    <i class="bi bi-journal-text" style="font-size:3rem; color:#e5e7eb; display:block; margin-bottom:16px;"></i>
                    <h6 style="color:#1e1b4b; font-weight:700; margin-bottom:6px;">No entries yet</h6>
                    <p style="color:#9ca3af; font-size:0.88rem; margin:0;">Start documenting your travels using the form on the left!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

  </div>
</div>

<script>
document.querySelectorAll('.mood-pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.mood-pill').forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
    });
});
</script>

<?php include("includes/footer.php"); ?>
