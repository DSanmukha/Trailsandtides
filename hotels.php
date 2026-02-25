<?php include("includes/header.php"); ?>

<style>
  .hotels-container { min-height: 100vh; background: linear-gradient(180deg, #f5f3ff, #faf5ff); }
  
  /* Hero — matches destinations.php pattern */
  .hotel-hero {
    background: linear-gradient(135deg, rgba(30,27,75,0.92), rgba(76,29,149,0.82)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1600&h=900&fit=crop') center/cover;
    padding: 160px 0 80px; text-align: center; margin-top: -76px;
  }
  .hotel-hero h1 { color: white; font-family: 'Playfair Display'; font-size: 3rem; font-weight: 800; margin-bottom: 8px; }
  .hotel-hero p { color: rgba(255,255,255,0.65); font-size: 1.1rem; max-width: 500px; margin: 0 auto; }

  /* White glassmorphism filter bar */
  .hotel-filter-bar {
    background: rgba(255,255,255,0.92); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.1); border-radius: 20px;
    padding: 1.25rem 1.5rem; margin-top: -30px; position: relative; z-index: 2;
  }
  .hotel-filter-bar .form-control, .hotel-filter-bar .form-select {
    border: 1px solid rgba(139,92,246,0.15); border-radius: 12px; color: #1e1b4b;
  }
  .hotel-filter-bar .form-control:focus, .hotel-filter-bar .form-select:focus {
    border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
  }
  
  /* Hotel Cards */
  .hotel-card { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border: 1px solid rgba(139,92,246,0.1); border-radius: 20px; overflow: hidden; transition: all 0.4s ease; margin-bottom: 1.5rem; }
  .hotel-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: rgba(139,92,246,0.2); }
  .hotel-card-img { width: 100%; height: 200px; object-fit: cover; transition: transform 0.4s ease; }
  .hotel-card:hover .hotel-card-img { transform: scale(1.05); }
  .hotel-card-body { padding: 1.25rem; }
  
  /* Budget Calculator */
  .calc-section { background: linear-gradient(135deg, #1e1b4b, #312e81); padding: 3rem 0; }
  .calc-card { background: rgba(255,255,255,0.06); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.08); border-radius: 20px; padding: 2rem; }
  .calc-card label { color: rgba(255,255,255,0.7); font-size: 0.82rem; font-weight: 500; margin-bottom: 4px; display: block; }
  .calc-card select, .calc-card input { width: 100%; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 12px; padding: 10px 14px; color: white; font-family: 'Poppins'; margin-bottom: 1rem; }
  .calc-card select option { background: #1e1b4b; color: white; }
  
  .result-card { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 1.5rem; }
  .result-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.05); }
  .result-label { color: rgba(255,255,255,0.5); font-size: 0.85rem; }
  .result-value { color: white; font-weight: 600; font-size: 0.9rem; }
  .result-total { display: flex; justify-content: space-between; padding: 12px 0; margin-top: 6px; }
  .result-total-label { color: white; font-weight: 700; font-size: 1rem; }
  .result-total-value { color: #c084fc; font-weight: 800; font-size: 1.3rem; }
  
  /* Packages */
  .package-card { background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); border: 1px solid rgba(139,92,246,0.1); border-radius: 20px; overflow: hidden; transition: all 0.4s ease; }
  .package-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
  .package-header { background: linear-gradient(135deg, #8b5cf6, #c084fc); padding: 2rem 1.5rem; text-align: center; color: white; }
  .package-header h3 { font-weight: 700; margin-bottom: 4px; }
  .package-price { font-size: 2rem; font-weight: 800; }
  .package-body { padding: 1.5rem; }
  .package-feature { padding: 8px 0; color: #6b7280; font-size: 0.88rem; display: flex; align-items: center; gap: 8px; }
  .package-feature i { color: #22c55e; }
  .pkg-btn { width: 100%; background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; border: none; border-radius: 12px; padding: 12px; font-weight: 600; cursor: pointer; margin-top: 1rem; transition: all 0.3s ease; font-family: 'Poppins'; }
  .pkg-btn:hover { box-shadow: 0 8px 20px rgba(139,92,246,0.3); transform: translateY(-2px); }
</style>

<div class="hotels-container">
  <!-- Hero -->
  <div class="hotel-hero">
    <div class="container">
      <h1 data-aos="fade-up">Hotels & Stays</h1>
      <p data-aos="fade-up" data-aos-delay="100">Find your perfect accommodation worldwide</p>
    </div>
  </div>

  <!-- Filter Bar -->
  <div class="container">
    <div class="hotel-filter-bar" data-aos="fade-up">
      <div class="row g-3 align-items-center">
        <div class="col-md-5">
          <input type="text" class="form-control" placeholder="🔍 Search hotels by name or destination..." id="hotelSearch">
        </div>
        <div class="col-md-5">
          <select class="form-select" id="hotelDestination">
            <option value="">All Destinations</option>
            <option value="bali">Bali</option>
            <option value="paris">Paris</option>
            <option value="tokyo">Tokyo</option>
          </select>
        </div>
        <div class="col-md-1">
          <button class="btn w-100" style="background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; border-radius: 12px; font-weight: 600; border: none; padding: 10px;" onclick="filterHotels()"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-md-1">
          <button class="btn w-100" style="border: 1px solid rgba(139,92,246,0.3); border-radius: 12px; color: #8b5cf6; font-weight: 600; padding: 10px;" onclick="document.getElementById('hotelSearch').value='';document.getElementById('hotelDestination').value='';filterHotels();"><i class="bi bi-x-lg"></i></button>
        </div>
      </div>
    </div>
  </div>


  <!-- Featured Hotels -->
  <section class="pt-3 pb-5">
    <div class="container">
      <div class="text-center mb-4">
        <span class="section-label" style="color: #8b5cf6; font-size: 0.75rem; font-weight: 600; letter-spacing: 2px;">HANDPICKED</span>
        <h2 class="section-title" style="color: #1e1b4b;">Featured Hotels</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-4 hotel-card-wrap">
          <div class="hotel-card"><div style="overflow: hidden;"><img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=400&h=250&fit=crop" alt="Bali Resort" class="hotel-card-img"></div><div class="hotel-card-body"><div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.4rem;"><h4 style="color: #1e1b4b; margin: 0; font-size: 1.05rem; font-weight: 700;">Bali Beach Resort</h4><span style="background: rgba(139,92,246,0.1); color: #8b5cf6; padding: 2px 8px; border-radius: 8px; font-size: 0.72rem; font-weight: 600;">4.8★</span></div><p style="color: #6b7280; margin: 0 0 0.6rem; font-size: 0.82rem;">Luxury beachfront with private pool villas</p><div style="display: flex; justify-content: space-between; align-items: center;"><div><span style="color: #9ca3af; font-size: 0.72rem; text-decoration: line-through;">₹26,499</span><span style="color: #8b5cf6; font-size: 1.2rem; font-weight: 800; margin-left: 0.25rem;">₹20,499</span><span style="color: #9ca3af; font-size: 0.72rem;">/night</span></div><a href="tours.php" class="pkg-btn" style="width: auto; margin: 0; padding: 7px 18px; font-size: 0.82rem;">Book</a></div></div></div>
        </div>
        <div class="col-md-4 hotel-card-wrap">
          <div class="hotel-card"><div style="overflow: hidden;"><img src="https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=400&h=200&fit=crop" alt="Paris Hotel" class="hotel-card-img"></div><div class="hotel-card-body"><div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.4rem;"><h4 style="color: #1e1b4b; margin: 0; font-size: 1.05rem; font-weight: 700;">Le Grand Paris</h4><span style="background: rgba(139,92,246,0.1); color: #8b5cf6; padding: 2px 8px; border-radius: 8px; font-size: 0.72rem; font-weight: 600;">4.9★</span></div><p style="color: #6b7280; margin: 0 0 0.6rem; font-size: 0.82rem;">Boutique hotel with Eiffel Tower views</p><div style="display: flex; justify-content: space-between; align-items: center;"><div><span style="color: #9ca3af; font-size: 0.72rem; text-decoration: line-through;">₹36,999</span><span style="color: #8b5cf6; font-size: 1.2rem; font-weight: 800; margin-left: 0.25rem;">₹30,999</span><span style="color: #9ca3af; font-size: 0.72rem;">/night</span></div><a href="tours.php" class="pkg-btn" style="width: auto; margin: 0; padding: 7px 18px; font-size: 0.82rem;">Book</a></div></div></div>
        </div>
        <div class="col-md-4 hotel-card-wrap">
          <div class="hotel-card"><div style="overflow: hidden;"><img src="https://images.unsplash.com/photo-1480796927426-f609979314bd?w=400&h=250&fit=crop" alt="Tokyo Hotel" class="hotel-card-img"></div><div class="hotel-card-body"><div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.4rem;"><h4 style="color: #1e1b4b; margin: 0; font-size: 1.05rem; font-weight: 700;">Tokyo Sky Hotel</h4><span style="background: rgba(139,92,246,0.1); color: #8b5cf6; padding: 2px 8px; border-radius: 8px; font-size: 0.72rem; font-weight: 600;">4.7★</span></div><p style="color: #6b7280; margin: 0 0 0.6rem; font-size: 0.82rem;">Modern high-rise with skyline views</p><div style="display: flex; justify-content: space-between; align-items: center;"><div><span style="color: #9ca3af; font-size: 0.72rem; text-decoration: line-through;">₹22,999</span><span style="color: #8b5cf6; font-size: 1.2rem; font-weight: 800; margin-left: 0.25rem;">₹17,999</span><span style="color: #9ca3af; font-size: 0.72rem;">/night</span></div><a href="tours.php" class="pkg-btn" style="width: auto; margin: 0; padding: 7px 18px; font-size: 0.82rem;">Book</a></div></div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Budget Calculator -->
  <section class="calc-section">
    <div class="container">
      <div class="text-center mb-4">
        <span style="color: rgba(255,255,255,0.5); font-size: 0.75rem; font-weight: 600; letter-spacing: 2px;">PLAN YOUR BUDGET</span>
        <h2 style="color: white; font-family: 'Playfair Display'; font-weight: 800; font-size: 2rem;">Trip Cost Calculator</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-6">
          <div class="calc-card">
            <label>Destination</label>
            <select id="destination"><option>Bali</option><option>Paris</option><option>Tokyo</option><option>Maldives</option><option>Switzerland</option></select>
            <label>Duration (nights)</label>
            <input type="number" id="duration" value="3" min="1" max="30">
            <label>Guests</label>
            <input type="number" id="guests" value="2" min="1" max="10">
            <label>Hotel Category</label>
            <select id="hotelCategory"><option value="4000">Budget (₹4,000-6,500)</option><option value="8500">Standard (₹6,500-12,500)</option><option value="16500" selected>Premium (₹12,500-20,500)</option><option value="25000">Luxury (₹20,500+)</option></select>
            <label>Transportation</label>
            <select id="transportation"><option value="1650">Bus (₹1,650/day)</option><option value="4150" selected>Car Rental (₹4,150/day)</option><option value="1250">Taxi (₹1,250/trip)</option><option value="2499">Train (₹2,499/day)</option></select>
            <label>Meals Plan</label>
            <select id="mealsPlan"><option value="0">Self (₹0)</option><option value="1250">Breakfast (₹1,250/day)</option><option value="3300" selected>Half Board (₹3,300/day)</option><option value="5800">All Inclusive (₹5,800/day)</option></select>
            <button class="pkg-btn" onclick="calculateBudget()">Calculate Budget</button>
          </div>
        </div>
        <div class="col-md-6">
          <div class="result-card">
            <h3 style="color: white; font-weight: 700; margin-bottom: 1rem;">📊 Your Trip Breakdown</h3>
            <div class="result-row"><span class="result-label">Accommodation</span><span class="result-value" id="accomResult">₹49,500</span></div>
            <div class="result-row"><span class="result-label">Transportation</span><span class="result-value" id="transResult">₹12,450</span></div>
            <div class="result-row"><span class="result-label">Meals</span><span class="result-value" id="mealsResult">₹9,900</span></div>
            <div class="result-total"><span class="result-total-label">Total Cost:</span><span class="result-total-value" id="totalResult">₹71,850</span></div>
            <p style="color: rgba(255,255,255,0.5); font-size: 0.82rem; margin-top: 0.75rem; margin-bottom: 0;"><i class="bi bi-info-circle"></i> Per person: <strong id="perPerson">₹35,925</strong></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Packages -->
  <section class="py-5" style="background: var(--lavender-bg, #f5f3ff);">
    <div class="container">
      <div class="text-center mb-4">
        <span class="section-label" style="color: #8b5cf6; font-size: 0.75rem; font-weight: 600; letter-spacing: 2px;">VALUE DEALS</span>
        <h2 class="section-title" style="color: #1e1b4b;">Travel Packages</h2>
      </div>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="package-card"><div class="package-header"><h3>Beach Paradise</h3><div class="package-price">₹74,999</div><small style="opacity: 0.85;">per person, 5 nights</small></div><div class="package-body"><div class="package-feature"><i class="bi bi-check-circle-fill"></i> 4-Star Beachfront</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> All-Inclusive Meals</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> Airport Transfers</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> Daily Activities</div><a href="tours.php" class="pkg-btn">Book Now</a></div></div>
        </div>
        <div class="col-md-4">
          <div class="package-card"><div class="package-header" style="background: linear-gradient(135deg, #6366f1, #818cf8);"><h3>City Adventure</h3><div class="package-price">₹53,999</div><small style="opacity: 0.85;">per person, 4 nights</small></div><div class="package-body"><div class="package-feature"><i class="bi bi-check-circle-fill"></i> 3-Star City Center</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> Breakfast & Dinner</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> Metro Pass</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> City Tour</div><a href="tours.php" class="pkg-btn" style="background: linear-gradient(135deg, #6366f1, #818cf8);">Book Now</a></div></div>
        </div>
        <div class="col-md-4">
          <div class="package-card"><div class="package-header" style="background: linear-gradient(135deg, #ec4899, #f472b6);"><h3>Mountain Escape</h3><div class="package-price">₹65,999</div><small style="opacity: 0.85;">per person, 5 nights</small></div><div class="package-body"><div class="package-feature"><i class="bi bi-check-circle-fill"></i> Luxury Resort</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> Half Board</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> Car Rental</div><div class="package-feature"><i class="bi bi-check-circle-fill"></i> Hiking Tours</div><a href="tours.php" class="pkg-btn" style="background: linear-gradient(135deg, #ec4899, #f472b6);">Book Now</a></div></div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
function calculateBudget() {
    const duration = parseInt(document.getElementById('duration').value) || 3;
    const guests = parseInt(document.getElementById('guests').value) || 2;
    const hotel = parseInt(document.getElementById('hotelCategory').value);
    const transport = parseInt(document.getElementById('transportation').value);
    const meals = parseInt(document.getElementById('mealsPlan').value);
    
    const accom = hotel * duration;
    const trans = transport * duration;
    const meal = meals * duration;
    const total = (accom + trans + meal) * guests;
    const perPerson = accom + trans + meal;
    
    document.getElementById('accomResult').textContent = '₹' + accom.toLocaleString('en-IN');
    document.getElementById('transResult').textContent = '₹' + trans.toLocaleString('en-IN');
    document.getElementById('mealsResult').textContent = '₹' + meal.toLocaleString('en-IN');
    document.getElementById('totalResult').textContent = '₹' + total.toLocaleString('en-IN');
    document.getElementById('perPerson').textContent = '₹' + perPerson.toLocaleString('en-IN');
}

function filterHotels() {
    const query = document.getElementById('hotelSearch').value.toLowerCase();
    const dest  = document.getElementById('hotelDestination').value.toLowerCase();

    document.querySelectorAll('.hotel-card-wrap').forEach(wrap => {
        const text = wrap.innerText.toLowerCase();
        let show = true;
        if (query && !text.includes(query)) show = false;
        if (dest && !text.includes(dest)) show = false;
        wrap.style.display = show ? '' : 'none';
    });
}

document.getElementById('hotelSearch').addEventListener('keyup', filterHotels);
document.getElementById('hotelDestination').addEventListener('change', filterHotels);
document.getElementById('hotelSearch').addEventListener('keydown', e => { if(e.key === 'Enter') filterHotels(); });
</script>

<?php include("includes/footer.php"); ?>
