<?php include("includes/header.php"); ?>

<?php
// Fetch hotels from DB
$hotels_data = $conn->query("SELECT * FROM hotels ORDER BY rating DESC");
$hotel_list = [];
if ($hotels_data && $hotels_data->num_rows > 0) {
    while ($row = $hotels_data->fetch_assoc()) {
        $hotel_list[] = $row;
    }
}
// Fallback if DB is empty
if (empty($hotel_list)) {
    $hotel_list = [
        ['name'=>'Bali Beach Resort','destination'=>'Bali, Indonesia','description'=>'Luxury beachfront with private pool villas and spa','price_per_night'=>20499,'original_price'=>26499,'rating'=>4.8,'image_url'=>'assets/images/hotels/bali-resort.jpg'],
        ['name'=>'Le Grand Paris','destination'=>'Paris, France','description'=>'Boutique hotel with Eiffel Tower views and fine dining','price_per_night'=>30999,'original_price'=>36999,'rating'=>4.9,'image_url'=>'assets/images/hotels/paris-hotel.jpg'],
        ['name'=>'Tokyo Sky Hotel','destination'=>'Tokyo, Japan','description'=>'Modern high-rise with panoramic city skyline views','price_per_night'=>17999,'original_price'=>22999,'rating'=>4.7,'image_url'=>'assets/images/hotels/tokyo-hotel.jpg'],
        ['name'=>'Maldives Water Villa','destination'=>'Maldives','description'=>'Overwater bungalow with private deck and coral reef access','price_per_night'=>45999,'original_price'=>55999,'rating'=>5.0,'image_url'=>'assets/images/hotels/maldives-villa.jpg'],
        ['name'=>'Alpine Lodge','destination'=>'Switzerland','description'=>'Cozy mountain chalet with ski-in/ski-out and fireplace','price_per_night'=>35999,'original_price'=>42999,'rating'=>4.8,'image_url'=>'assets/images/hotels/swiss-lodge.jpg'],
        ['name'=>'Cairo Palace Hotel','destination'=>'Egypt','description'=>'Historic luxury hotel near the Great Pyramids of Giza','price_per_night'=>12999,'original_price'=>16999,'rating'=>4.6,'image_url'=>'assets/images/hotels/egypt-palace.jpg'],
    ];
}

$dest_set = [];
foreach ($hotel_list as $h) {
    $d = trim($h['destination'] ?? '');
    if ($d && !in_array($d, $dest_set)) $dest_set[] = $d;
}
sort($dest_set);
?>

<style>
  .hotels-page { min-height: 100vh; background: #f5f3ff; }
  .hotel-hero {
    background: linear-gradient(135deg, rgba(30,27,75,0.9), rgba(76,29,149,0.8)), url('assets/images/beach.jpg') center/cover;
    padding: 160px 0 80px; text-align: center; margin-top: -76px;
  }
  .hotel-hero h1 { color: white; font-family: 'Playfair Display'; font-size: 3rem; font-weight: 800; margin-bottom: 8px; }
  .hotel-hero p  { color: rgba(255,255,255,0.65); font-size: 1.1rem; max-width: 500px; margin: 0 auto; }

  .hotel-filter-bar {
    background: rgba(255,255,255,0.92); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.08); border-radius: 16px;
    padding: 1rem 1.25rem; margin-top: -30px; position: relative; z-index: 2;
  }
  .hotel-filter-bar .form-control, .hotel-filter-bar .form-select {
    border: 1px solid rgba(139,92,246,0.12); border-radius: 10px;
  }
  .hotel-filter-bar .form-control:focus, .hotel-filter-bar .form-select:focus {
    border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.08);
  }

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
  .site-card-body { padding: 1.1rem; flex: 1; display: flex; flex-direction: column; }
  .site-card-body h4 { color: #1e1b4b; font-weight: 700; font-size: 1rem; margin-bottom: 3px; }
  .site-card-dest { color: #8b5cf6; font-size: 0.78rem; font-weight: 500; margin-bottom: 6px; }
  .site-card-body p { color: #6b7280; font-size: 0.82rem; line-height: 1.5; margin-bottom: 10px; flex: 1; }
  .site-card-footer {
    display: flex; justify-content: space-between; align-items: center;
    padding-top: 10px; border-top: 1px solid rgba(139,92,246,0.06);
  }
  .site-price-old { color: #9ca3af; font-size: 0.72rem; text-decoration: line-through; }
  .site-price { color: #1e1b4b; font-size: 1.1rem; font-weight: 800; }
  .site-price span { color: #9ca3af; font-size: 0.7rem; font-weight: 400; }
  .site-btn {
    background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white;
    border: none; border-radius: 8px; padding: 7px 14px;
    font-weight: 600; font-size: 0.78rem; cursor: pointer;
    transition: all 0.3s; font-family: 'Poppins'; text-decoration: none;
  }
  .site-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(139,92,246,0.25); color: white; }

  /* Budget Planner */
  .budget-section { background: white; border-radius: 18px; border: 1px solid rgba(139,92,246,0.06); box-shadow: 0 2px 12px rgba(0,0,0,0.03); padding: 2rem; }
  .budget-result { background: linear-gradient(135deg, #f5f3ff, #ede9fe); border-radius: 14px; padding: 1.5rem; text-align: center; margin-top: 1rem; }
  .budget-result .total { font-size: 2.2rem; font-weight: 800; color: #1e1b4b; }
  .budget-result p { color: #6b7280; font-size: 0.82rem; margin: 0; }
</style>

<div class="hotels-page">
  <div class="hotel-hero">
    <div class="container">
      <h1 data-aos="fade-up">Hotels & Stays</h1>
      <p data-aos="fade-up" data-aos-delay="100">Book your perfect accommodation separately</p>
    </div>
  </div>

  <div class="container">
    <div class="hotel-filter-bar" data-aos="fade-up">
      <div class="row g-2 align-items-center">
        <div class="col-md-5">
          <input type="text" class="form-control" placeholder="Search hotels..." id="hotelSearch">
        </div>
        <div class="col-md-5">
          <select class="form-select" id="hotelDest">
            <option value="">All Destinations</option>
            <?php foreach($dest_set as $d): ?>
            <option value="<?= htmlspecialchars(strtolower($d)) ?>"><?= htmlspecialchars($d) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-1">
          <button class="btn w-100 site-btn" style="padding:9px;" onclick="filterHotels()"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-md-1">
          <button class="btn w-100" style="border:1px solid rgba(139,92,246,0.2); border-radius:8px; color:#8b5cf6; padding:9px;" onclick="clearHotelFilters()"><i class="bi bi-x-lg"></i></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Hotel Cards -->
  <section class="py-4">
    <div class="container">
      <div class="row g-4">
        <?php foreach($hotel_list as $i => $h): ?>
        <div class="col-lg-4 col-md-6 hotel-card-wrap" data-aos="fade-up" data-aos-delay="<?= (($i % 3) + 1) * 100 ?>">
          <div class="site-card">
            <div style="position:relative; overflow:hidden;">
              <img src="<?= htmlspecialchars($h['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($h['name']) ?>" class="site-card-img" onerror="this.src='assets/images/beach.jpg'">
              <div class="site-card-badge"><i class="bi bi-star-fill"></i> <?= $h['rating'] ?></div>
            </div>
            <div class="site-card-body">
              <h4><?= htmlspecialchars($h['name']) ?></h4>
              <div class="site-card-dest"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($h['destination'] ?? '') ?></div>
              <p><?= htmlspecialchars($h['description'] ?? '') ?></p>
              <div class="site-card-footer">
                <div>
                  <?php if(!empty($h['original_price']) && $h['original_price'] > $h['price_per_night']): ?>
                  <div class="site-price-old">₹<?= number_format($h['original_price']) ?></div>
                  <?php endif; ?>
                  <div class="site-price">₹<?= number_format($h['price_per_night']) ?> <span>/night</span></div>
                </div>
                <?php if(isset($_SESSION['user'])): ?>
                <button class="site-btn" onclick="openBookHotel('<?= htmlspecialchars(addslashes($h['name'])) ?>', <?= $h['price_per_night'] ?>)"><i class="bi bi-calendar-check me-1"></i>Book</button>
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

  <!-- Budget Planner -->
  <section class="py-4 pb-5">
    <div class="container">
      <div class="budget-section" data-aos="fade-up">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <span style="color: #8b5cf6; font-size: 0.72rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">PLAN YOUR STAY</span>
            <h3 style="color: #1e1b4b; font-family: 'Playfair Display'; font-weight: 800; font-size: 1.6rem; margin: 6px 0 16px;">Budget Planner</h3>
            <div class="row g-3">
              <div class="col-md-12">
                <label style="color: #6b7280; font-size: 0.78rem; font-weight: 500;">Select Hotel</label>
                <select class="form-select" id="budgetHotel" onchange="calcBudget()" style="border: 1px solid rgba(139,92,246,0.12); border-radius: 10px;">
                  <option value="0">Choose a hotel...</option>
                  <?php foreach($hotel_list as $h): ?>
                  <option value="<?= $h['price_per_night'] ?>"><?= htmlspecialchars($h['name']) ?> — ₹<?= number_format($h['price_per_night']) ?>/night</option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-6">
                <label style="color: #6b7280; font-size: 0.78rem; font-weight: 500;">Nights</label>
                <input type="number" class="form-control" id="budgetNights" value="3" min="1" max="30" onchange="calcBudget()" style="border: 1px solid rgba(139,92,246,0.12); border-radius: 10px;">
              </div>
              <div class="col-6">
                <label style="color: #6b7280; font-size: 0.78rem; font-weight: 500;">Rooms</label>
                <input type="number" class="form-control" id="budgetRooms" value="1" min="1" max="10" onchange="calcBudget()" style="border: 1px solid rgba(139,92,246,0.12); border-radius: 10px;">
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="budget-result" id="budgetResult">
              <p>Estimated Cost</p>
              <div class="total" id="budgetTotal">₹0</div>
              <p id="budgetBreakdown" style="margin-top: 6px;">Select a hotel to calculate</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Hotel Booking Modal -->
<div class="modal fade" id="hotelBookModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 16px; border: 1px solid rgba(139,92,246,0.1);">
      <div class="modal-header" style="border-bottom: 1px solid rgba(139,92,246,0.08); padding: 1.2rem;">
        <h5 class="modal-title" style="font-weight: 700; color: #1e1b4b;"><i class="bi bi-building me-2" style="color: #8b5cf6;"></i>Book Hotel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="book_tour.php" method="POST">
        <div class="modal-body" style="padding: 1.2rem;">
          <input type="hidden" name="tour_name" id="bookHotelName">
          <input type="hidden" name="tour_price" id="bookHotelPrice">
          <div style="background: rgba(139,92,246,0.03); border-radius: 12px; padding: 14px; margin-bottom: 1rem; border: 1px solid rgba(139,92,246,0.06);">
            <h6 id="bookHotelTitle" style="color: #1e1b4b; font-weight: 700; margin-bottom: 4px;"></h6>
            <span id="bookHotelPriceShow" style="color: #1e1b4b; font-weight: 800; font-size: 1.1rem;"></span>
          </div>
          <div class="mb-3">
            <label style="color: #6b7280; font-size: 0.8rem; font-weight: 500;">Check-in Date</label>
            <input type="date" name="check_in" class="form-control" required min="<?= date('Y-m-d') ?>" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.12);">
          </div>
          <div class="mb-3">
            <label style="color: #6b7280; font-size: 0.8rem; font-weight: 500;">Number of Nights</label>
            <select name="guests" class="form-select" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.12);">
              <option value="1">1 Night</option><option value="2">2 Nights</option><option value="3" selected>3 Nights</option><option value="5">5 Nights</option><option value="7">7 Nights</option>
            </select>
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
function filterHotels() {
    const q = document.getElementById('hotelSearch').value.toLowerCase();
    const d = document.getElementById('hotelDest').value.toLowerCase();
    document.querySelectorAll('.hotel-card-wrap').forEach(w => {
        const t = w.innerText.toLowerCase();
        w.style.display = (!q || t.includes(q)) && (!d || t.includes(d)) ? '' : 'none';
    });
}
function clearHotelFilters() {
    document.getElementById('hotelSearch').value = '';
    document.getElementById('hotelDest').value = '';
    filterHotels();
}
document.getElementById('hotelSearch').addEventListener('keyup', filterHotels);
document.getElementById('hotelDest').addEventListener('change', filterHotels);

function calcBudget() {
    const price = parseFloat(document.getElementById('budgetHotel').value) || 0;
    const nights = parseInt(document.getElementById('budgetNights').value) || 1;
    const rooms = parseInt(document.getElementById('budgetRooms').value) || 1;
    const total = price * nights * rooms;
    document.getElementById('budgetTotal').textContent = '₹' + total.toLocaleString('en-IN');
    document.getElementById('budgetBreakdown').textContent = price > 0
        ? `₹${price.toLocaleString('en-IN')} × ${nights} night${nights>1?'s':''} × ${rooms} room${rooms>1?'s':''}`
        : 'Select a hotel to calculate';
}

function openBookHotel(name, price) {
    document.getElementById('bookHotelName').value = name;
    document.getElementById('bookHotelPrice').value = price;
    document.getElementById('bookHotelTitle').textContent = name;
    document.getElementById('bookHotelPriceShow').textContent = '₹' + Number(price).toLocaleString('en-IN') + '/night';
    new bootstrap.Modal(document.getElementById('hotelBookModal')).show();
}
</script>

<?php include("includes/footer.php"); ?>
