<?php include("includes/header.php"); ?>

<?php
// Fetch destinations from DB
$dest_data = $conn->query("SELECT * FROM destinations ORDER BY rating DESC");
$destinations = [];
if ($dest_data && $dest_data->num_rows > 0) {
    while ($row = $dest_data->fetch_assoc()) {
        $destinations[] = $row;
    }
}
// Fallback if DB is empty
if (empty($destinations)) {
    $destinations = [
        ['name'=>'Bali','country'=>'Indonesia','region'=>'Asia','description'=>'Tropical beaches, ancient temples, rice terraces, and vibrant nightlife','weather'=>'Sunny','temperature'=>28,'rating'=>4.8,'tour_count'=>12,'image_url'=>'assets/images/destinations/bali.jpg'],
        ['name'=>'Paris','country'=>'France','region'=>'Europe','description'=>'City of love — iconic landmarks, world-class museums, and exquisite cuisine','weather'=>'Cloudy','temperature'=>18,'rating'=>4.9,'tour_count'=>8,'image_url'=>'assets/images/destinations/paris.jpg'],
        ['name'=>'Tokyo','country'=>'Japan','region'=>'Asia','description'=>'Neon-lit streets, ancient temples, and the finest cuisine in the world','weather'=>'Clear','temperature'=>20,'rating'=>4.7,'tour_count'=>6,'image_url'=>'assets/images/destinations/tokyo.jpg'],
        ['name'=>'Maldives','country'=>'Maldives','region'=>'Asia','description'=>'Crystal clear waters, overwater bungalows, and pristine coral reefs','weather'=>'Sunny','temperature'=>29,'rating'=>5.0,'tour_count'=>4,'image_url'=>'assets/images/destinations/maldives.jpg'],
        ['name'=>'Swiss Alps','country'=>'Switzerland','region'=>'Europe','description'=>'Majestic mountain peaks, scenic trains, and charming alpine villages','weather'=>'Snowy','temperature'=>5,'rating'=>4.8,'tour_count'=>5,'image_url'=>'assets/images/destinations/swiss-alps.jpg'],
        ['name'=>'New York','country'=>'USA','region'=>'Americas','description'=>'The city that never sleeps — iconic skyline, culture, and world-class food','weather'=>'Clear','temperature'=>22,'rating'=>4.6,'tour_count'=>7,'image_url'=>'assets/images/destinations/newyork.jpg'],
    ];
}

$regions = [];
foreach ($destinations as $d) {
    $r = trim($d['region'] ?? '');
    if ($r && !in_array($r, $regions)) $regions[] = $r;
}
sort($regions);

function getWeatherIcon($w) {
    $w = strtolower($w ?? '');
    if (strpos($w, 'sun')!==false || strpos($w, 'hot')!==false) return 'bi-sun-fill';
    if (strpos($w, 'cloud')!==false || strpos($w, 'clear')!==false) return 'bi-cloud-sun-fill';
    if (strpos($w, 'snow')!==false) return 'bi-snow';
    return 'bi-sun-fill';
}
?>

<style>
.dest-hero {
    background: linear-gradient(135deg, rgba(30,27,75,0.88), rgba(76,29,149,0.78)), url('assets/images/hero.jpg') center/cover;
    padding: 160px 0 80px; text-align: center; margin-top: -76px;
}
.dest-hero h1 { font-family: 'Playfair Display'; color: white; font-size: 3rem; font-weight: 800; margin-bottom: 8px; }
.dest-hero p { color: rgba(255,255,255,0.65); font-size: 1.1rem; max-width: 500px; margin: 0 auto; }

.dest-filter-bar {
    background: rgba(255,255,255,0.92); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.08); border-radius: 16px;
    padding: 1rem 1.25rem; margin-top: -30px; position: relative; z-index: 2;
}
.dest-filter-bar .form-control, .dest-filter-bar .form-select {
    border: 1px solid rgba(139,92,246,0.12); border-radius: 10px;
}
.dest-filter-bar .form-control:focus, .dest-filter-bar .form-select:focus {
    border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.08);
}

/* Same card as hotels and tours */
.site-card {
    background: white; border-radius: 16px; overflow: hidden;
    border: 1px solid rgba(139,92,246,0.06);
    box-shadow: 0 2px 12px rgba(0,0,0,0.03);
    transition: all 0.35s ease; height: 100%; display: flex; flex-direction: column;
}
.site-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(139,92,246,0.1);
    border-color: rgba(139,92,246,0.12);
}
.site-card-img { width: 100%; height: 200px; object-fit: cover; }
.site-card-badge {
    position: absolute; top: 10px; right: 10px;
    background: rgba(0,0,0,0.45); backdrop-filter: blur(6px);
    color: white; padding: 3px 8px; border-radius: 6px;
    font-size: 0.72rem; font-weight: 600;
}
.site-card-badge i { color: #fbbf24; margin-right: 3px; }
.site-card-tours-badge {
    position: absolute; top: 10px; left: 10px;
    background: linear-gradient(135deg, #8b5cf6, #c084fc);
    color: white; padding: 3px 8px; border-radius: 6px;
    font-size: 0.68rem; font-weight: 700;
}
.site-card-body { padding: 1.1rem; flex: 1; display: flex; flex-direction: column; }
.site-card-body h4 { color: #1e1b4b; font-weight: 700; font-size: 1rem; margin-bottom: 3px; }
.site-card-dest { color: #8b5cf6; font-size: 0.78rem; font-weight: 500; margin-bottom: 6px; }
.site-card-body p { color: #6b7280; font-size: 0.82rem; line-height: 1.5; margin-bottom: 10px; flex: 1; }
.site-card-footer {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 10px; border-top: 1px solid rgba(139,92,246,0.06);
}
.dest-weather { color: #6b7280; font-size: 0.78rem; display: flex; align-items: center; gap: 4px; }
.dest-weather i { color: #f59e0b; }
.site-btn {
    background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white;
    border: none; border-radius: 8px; padding: 7px 14px;
    font-weight: 600; font-size: 0.78rem; cursor: pointer;
    transition: all 0.3s; font-family: 'Poppins'; text-decoration: none;
}
.site-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(139,92,246,0.25); color: white; }
</style>

<!-- Hero -->
<div class="dest-hero">
    <div class="container">
        <h1 data-aos="fade-up">Explore Destinations</h1>
        <p data-aos="fade-up" data-aos-delay="100">Discover breathtaking places & book your own adventure</p>
    </div>
</div>

<!-- Filter Bar -->
<div class="container">
    <div class="dest-filter-bar" data-aos="fade-up">
        <div class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Search destinations..." id="destSearch">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="destRegion">
                    <option value="">All Regions</option>
                    <?php foreach($regions as $r): ?>
                    <option value="<?= htmlspecialchars(strtolower($r)) ?>"><?= htmlspecialchars($r) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="destSort">
                    <option value="">Sort By</option>
                    <option>Rating</option><option>Tours</option><option>Name</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn w-100 site-btn" style="padding: 9px;" onclick="filterDests()"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-1">
                <button class="btn w-100" style="border:1px solid rgba(139,92,246,0.2); border-radius:8px; color:#8b5cf6; padding:9px;" onclick="clearDestFilters()"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Destinations Grid -->
<section class="py-4" style="background: #f5f3ff;">
    <div class="container">
        <div class="row g-4">
            <?php foreach($destinations as $i => $d): ?>
            <div class="col-lg-3 col-md-6 dest-card-wrap" data-aos="fade-up" data-aos-delay="<?= (($i % 4) + 1) * 80 ?>">
                <div class="site-card">
                    <div style="position:relative; overflow:hidden;">
                        <img src="<?= htmlspecialchars($d['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($d['name']) ?>" class="site-card-img" onerror="this.src='assets/images/beach.jpg'">
                        <div class="site-card-badge"><i class="bi bi-star-fill"></i> <?= $d['rating'] ?></div>
                        <div class="site-card-tours-badge"><?= $d['tour_count'] ?> Tours</div>
                    </div>
                    <div class="site-card-body">
                        <h4><?= htmlspecialchars($d['name']) ?></h4>
                        <div class="site-card-dest"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($d['country']) ?></div>
                        <p><?= htmlspecialchars($d['description'] ?? '') ?></p>
                        <div class="site-card-footer">
                            <span class="dest-weather">
                                <i class="bi <?= getWeatherIcon($d['weather'] ?? '') ?>"></i>
                                <?= $d['temperature'] ?>°C
                            </span>
                            <?php if(isset($_SESSION['user'])): ?>
                            <button class="site-btn" onclick="openBookDest('<?= htmlspecialchars(addslashes($d['name'])) ?>', '<?= htmlspecialchars(addslashes($d['country'])) ?>')"><i class="bi bi-calendar-check me-1"></i>Book</button>
                            <?php else: ?>
                            <a href="auth/login.php" class="site-btn"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section style="background: linear-gradient(135deg, #1e1b4b, #312e81, #4c1d95); padding: 50px 0;">
    <div class="container text-center" data-aos="fade-up">
        <h2 style="font-family: 'Playfair Display'; color: white; font-size: 2.2rem; font-weight: 800; margin-bottom: 10px;">Can't Find Your Destination?</h2>
        <p style="color: rgba(255,255,255,0.5); margin-bottom: 1.5rem;">Tell us where you want to go — we'll craft a custom trip</p>
        <a href="tours.php" class="site-btn" style="padding: 12px 30px; font-size: 0.95rem;">Browse Tour Packages</a>
    </div>
</section>

<!-- Booking Modal -->
<div class="modal fade" id="destBookModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 16px; border: 1px solid rgba(139,92,246,0.1);">
      <div class="modal-header" style="border-bottom: 1px solid rgba(139,92,246,0.08); padding: 1.2rem;">
        <h5 class="modal-title" style="font-weight: 700; color: #1e1b4b;"><i class="bi bi-geo-alt me-2" style="color: #8b5cf6;"></i>Book Destination</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="book_tour.php" method="POST">
        <div class="modal-body" style="padding: 1.2rem;">
          <input type="hidden" name="tour_name" id="bookDestName">
          <input type="hidden" name="tour_price" value="0">
          <div style="background: rgba(139,92,246,0.03); border-radius: 12px; padding: 14px; margin-bottom: 1rem; border: 1px solid rgba(139,92,246,0.06);">
            <h6 id="bookDestTitle" style="color: #1e1b4b; font-weight: 700; margin-bottom: 2px;"></h6>
            <p id="bookDestCountry" style="color: #8b5cf6; font-size: 0.82rem; margin: 0;"></p>
          </div>
          <div class="mb-3">
            <label style="color: #6b7280; font-size: 0.8rem; font-weight: 500;">Travel Date</label>
            <input type="date" name="check_in" class="form-control" required min="<?= date('Y-m-d') ?>" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.12);">
          </div>
          <div class="mb-3">
            <label style="color: #6b7280; font-size: 0.8rem; font-weight: 500;">Number of Travelers</label>
            <select name="guests" class="form-select" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.12);">
              <option value="1">1</option><option value="2" selected>2</option><option value="3">3</option><option value="4">4</option><option value="5">5+</option>
            </select>
          </div>
          <div class="mb-3">
            <label style="color: #6b7280; font-size: 0.8rem; font-weight: 500;">Notes (optional)</label>
            <textarea name="notes" class="form-control" rows="2" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.12);" placeholder="Any preferences..."></textarea>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid rgba(139,92,246,0.08); padding: 1.2rem;">
          <button type="button" class="btn" data-bs-dismiss="modal" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.15); color: #1e1b4b; padding: 8px 20px;">Cancel</button>
          <button type="submit" name="book" class="btn site-btn" style="padding: 8px 22px;">Confirm Booking</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function filterDests() {
    const q = document.getElementById('destSearch').value.toLowerCase();
    const r = document.getElementById('destRegion').value.toLowerCase();
    document.querySelectorAll('.dest-card-wrap').forEach(c => {
        const t = c.innerText.toLowerCase();
        c.style.display = (!q || t.includes(q)) && (!r || t.includes(r)) ? '' : 'none';
    });
}
function clearDestFilters() {
    document.getElementById('destSearch').value='';
    document.getElementById('destRegion').value='';
    document.getElementById('destSort').value='';
    filterDests();
}
document.getElementById('destSearch').addEventListener('keyup', filterDests);
document.getElementById('destRegion').addEventListener('change', filterDests);
document.getElementById('destSort').addEventListener('change', filterDests);

function openBookDest(name, country) {
    document.getElementById('bookDestName').value = 'Destination: ' + name;
    document.getElementById('bookDestTitle').textContent = name;
    document.getElementById('bookDestCountry').innerHTML = '<i class="bi bi-geo-alt me-1"></i>' + country;
    new bootstrap.Modal(document.getElementById('destBookModal')).show();
}
</script>

<?php include("includes/footer.php"); ?>
