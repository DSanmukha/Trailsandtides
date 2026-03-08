<?php include("includes/header.php"); ?>

<?php
// Fetch tours from DB
$tours_data = $conn->query("SELECT * FROM tours ORDER BY rating DESC");
$tours = [];
if ($tours_data && $tours_data->num_rows > 0) {
    while ($row = $tours_data->fetch_assoc()) {
        $row['inc'] = ['Guided Tours','Hotel Stay','Daily Meals','Airport Transfer'];
        $tours[] = $row;
    }
}
// Fallback if DB is empty
if (empty($tours)) {
    $tours = [
        ['title'=>'Bali Paradise Experience','description'=>'Discover the magic of Bali with pristine beaches, ancient temples, and vibrant culture.','destination'=>'Bali, Indonesia','duration'=>7,'price'=>149999,'original_price'=>199999,'image_url'=>'assets/images/tours/bali.jpg','tag'=>'POPULAR','rating'=>4.8,'reviews'=>284,'inc'=>['5-star Hotel','Daily Breakfast','Guided Tours','Airport Transfer'],'group_type'=>'Group Tours'],
        ['title'=>'Swiss Alps Adventure','description'=>'Experience breathtaking mountain landscapes, alpine meadows, and charming Swiss villages.','destination'=>'Switzerland','duration'=>5,'price'=>219999,'original_price'=>259999,'image_url'=>'assets/images/tours/swiss.jpg','tag'=>'LUXURY','rating'=>4.9,'reviews'=>156,'inc'=>['Luxury Hotel','3 Course Meals','Cable Car Tours','Wine Tasting'],'group_type'=>'Small Groups'],
        ['title'=>'Tokyo Lights & Culture','description'=>'Immerse yourself in neon-lit streets, ancient temples, and the finest Japanese cuisine.','destination'=>'Tokyo, Japan','duration'=>4,'price'=>119999,'original_price'=>149999,'image_url'=>'assets/images/tours/tokyo.jpg','tag'=>'FAMILY','rating'=>4.7,'reviews'=>342,'inc'=>['4-star Hotel','JR Pass','Museum Tours','Local Cuisine'],'group_type'=>'All Ages'],
        ['title'=>'Maldives Romantic Getaway','description'=>'Perfect romantic escape with overwater bungalows and pristine coral reefs.','destination'=>'Maldives','duration'=>6,'price'=>299999,'original_price'=>349999,'image_url'=>'assets/images/tours/maldives.jpg','tag'=>'HONEYMOON','rating'=>5.0,'reviews'=>89,'inc'=>['Water Bungalow','All Meals','Spa Treatment','Sunset Cruise'],'group_type'=>'Couples'],
        ['title'=>'Iceland Northern Lights','description'=>'Witness waterfalls, glaciers, and the magical aurora borealis in Iceland.','destination'=>'Iceland','duration'=>8,'price'=>239999,'original_price'=>289999,'image_url'=>'assets/images/tours/iceland.jpg','tag'=>'ADVENTURE','rating'=>4.6,'reviews'=>201,'inc'=>['4x4 Vehicle','Northern Lights Tour','Glacier Hike','Hot Spring Visit'],'group_type'=>'Adventure Seekers'],
        ['title'=>'Egypt Historical Journey','description'=>'Explore ancient pyramids, pharaoh tombs, and cruise the majestic Nile River.','destination'=>'Egypt','duration'=>9,'price'=>189999,'original_price'=>229999,'image_url'=>'assets/images/tours/egypt.jpg','tag'=>'CULTURAL','rating'=>4.8,'reviews'=>127,'inc'=>['Nile Cruise','Pyramid Tours','Museum Entry','Expert Guide'],'group_type'=>'History Enthusiasts'],
    ];
}
?>

<style>
.tours-hero {
    background: linear-gradient(135deg, rgba(30,27,75,0.88), rgba(76,29,149,0.78)), url('assets/images/beach.jpg') center/cover;
    padding: 160px 0 80px; text-align: center; margin-top: -76px;
}
.tours-hero h1 { font-family: 'Playfair Display'; color: white; font-size: 3rem; font-weight: 800; margin-bottom: 8px; }
.tours-hero p { color: rgba(255,255,255,0.65); font-size: 1.1rem; max-width: 500px; margin: 0 auto; }

.tours-filter-bar {
    background: rgba(255,255,255,0.92); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.08); border-radius: 16px;
    padding: 1rem 1.25rem; margin-top: -30px; position: relative; z-index: 2;
}
.tours-filter-bar .form-control, .tours-filter-bar .form-select {
    border: 1px solid rgba(139,92,246,0.12); border-radius: 10px;
}
.tours-filter-bar .form-control:focus, .tours-filter-bar .form-select:focus {
    border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.08);
}

/* Same .site-card used in hotels — consistent design */
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
.site-card-img-area { height: 200px; background-size: cover; background-position: center; position: relative; }
.site-card-img-area::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 60px; background: linear-gradient(transparent, rgba(0,0,0,0.3)); }
.tour-tag { position: absolute; top: 10px; left: 10px; z-index: 2; padding: 3px 10px; border-radius: 6px; font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; color: white; background: linear-gradient(135deg, #8b5cf6, #c084fc); }
.tour-rating { position: absolute; bottom: 10px; left: 10px; z-index: 2; background: rgba(0,0,0,0.5); backdrop-filter: blur(6px); color: white; padding: 3px 8px; border-radius: 6px; font-size: 0.7rem; }
.tour-rating i { color: #fbbf24; margin-right: 2px; }
.site-card-body { padding: 1.1rem; flex: 1; display: flex; flex-direction: column; }
.site-card-body h4 { color: #1e1b4b; font-weight: 700; font-size: 1rem; margin-bottom: 4px; }
.site-card-body .desc { color: #6b7280; font-size: 0.82rem; line-height: 1.5; margin-bottom: 10px; }
.tour-meta { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 10px; }
.tour-meta span { color: #8b5cf6; font-size: 0.75rem; font-weight: 500; display: flex; align-items: center; gap: 3px; }
.tour-includes { margin-bottom: 12px; flex: 1; }
.tour-includes h6 { color: #1e1b4b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.tour-includes ul { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }
.tour-includes li { color: #6b7280; font-size: 0.75rem; display: flex; align-items: center; gap: 5px; }
.tour-includes li i { color: #22c55e; font-size: 0.65rem; }
.site-card-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 10px; border-top: 1px solid rgba(139,92,246,0.06); }
.site-price-old { color: #9ca3af; font-size: 0.72rem; text-decoration: line-through; }
.site-price { color: #1e1b4b; font-size: 1.1rem; font-weight: 800; }
.site-btn {
    background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white;
    border: none; border-radius: 8px; padding: 7px 14px;
    font-weight: 600; font-size: 0.78rem; cursor: pointer;
    transition: all 0.3s; font-family: 'Poppins'; text-decoration: none;
}
.site-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 14px rgba(139,92,246,0.25); color: white; }

.faq-section { background: #f5f3ff; padding: 3rem 0; }
.faq-item { background: white; border-radius: 14px; margin-bottom: 10px; border: 1px solid rgba(139,92,246,0.06); overflow: hidden; }
.faq-q { padding: 14px 18px; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; color: #1e1b4b; font-size: 0.9rem; }
.faq-q i { color: #8b5cf6; transition: transform 0.3s; }
.faq-q.active i { transform: rotate(180deg); }
.faq-a { padding: 0 18px 14px; color: #6b7280; font-size: 0.85rem; line-height: 1.6; display: none; }
.faq-a.show { display: block; }
</style>

<!-- HERO -->
<div class="tours-hero">
    <div class="container">
        <h1 data-aos="fade-up">Tour Packages</h1>
        <p data-aos="fade-up" data-aos-delay="100">Complete travel bundles with hotels, meals, and guided experiences</p>
    </div>
</div>

<!-- FILTER BAR -->
<div class="container">
    <div class="tours-filter-bar" data-aos="fade-up">
        <div class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Search tours..." id="searchInput">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterDuration">
                    <option value="">All Durations</option>
                    <option value="3">3-4 Days</option><option value="5">5-7 Days</option><option value="8">8+ Days</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterPrice">
                    <option value="">All Prices</option>
                    <option value="50000">Under ₹50,000</option><option value="100000">₹50k-₹1L</option><option value="200000">₹1L+</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn w-100 site-btn" style="padding:9px;" onclick="filterTours()"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-1">
                <button class="btn w-100" style="border:1px solid rgba(139,92,246,0.2); border-radius:8px; color:#8b5cf6; padding:9px;" onclick="clearFilters()"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- TOUR CARDS -->
<section class="py-4" style="background: #f5f3ff;">
    <div class="container">
        <div class="row g-4">
            <?php foreach($tours as $i => $t): ?>
            <div class="col-lg-4 col-md-6 tour-card-wrap" data-aos="fade-up" data-aos-delay="<?= (($i % 3) + 1) * 100 ?>" data-title="<?= htmlspecialchars(strtolower($t['title'])) ?>" data-dest="<?= htmlspecialchars(strtolower($t['destination'] ?? '')) ?>" data-days="<?= $t['duration'] ?>" data-price="<?= $t['price'] ?>">
                <div class="site-card">
                    <div class="site-card-img-area" style="background-image: url('<?= htmlspecialchars($t['image_url'] ?? 'assets/images/beach.jpg') ?>');">
                        <span class="tour-tag"><?= htmlspecialchars($t['tag'] ?? 'TOUR') ?></span>
                        <span class="tour-rating"><i class="bi bi-star-fill"></i> <?= $t['rating'] ?>/5 (<?= $t['reviews'] ?? 0 ?>)</span>
                    </div>
                    <div class="site-card-body">
                        <h4><?= htmlspecialchars($t['title']) ?></h4>
                        <p class="desc"><?= htmlspecialchars($t['description'] ?? '') ?></p>
                        <div class="tour-meta">
                            <span><i class="bi bi-calendar3"></i> <?= $t['duration'] ?> Days</span>
                            <span><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($t['destination'] ?? '') ?></span>
                            <span><i class="bi bi-people"></i> <?= htmlspecialchars($t['group_type'] ?? 'Group') ?></span>
                        </div>
                        <div class="tour-includes">
                            <h6>Includes:</h6>
                            <ul>
                                <?php foreach($t['inc'] as $inc): ?>
                                <li><i class="bi bi-check-circle-fill"></i> <?= $inc ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="site-card-footer">
                            <div>
                                <span class="site-price-old">₹<?= number_format($t['original_price']) ?></span>
                                <span class="site-price">₹<?= number_format($t['price']) ?></span>
                            </div>
                            <?php if(isset($_SESSION['user'])): ?>
                            <button class="site-btn book-btn"
                                data-tour="<?= htmlspecialchars($t['title']) ?>"
                                data-price="<?= $t['price'] ?>"
                                data-days="<?= $t['duration'] ?>"
                                data-dest="<?= htmlspecialchars($t['destination'] ?? '') ?>">
                                <i class="bi bi-airplane me-1"></i>Book Now
                            </button>
                            <?php else: ?>
                            <a href="auth/login.php" class="site-btn"><i class="bi bi-box-arrow-in-right me-1"></i>Login to Book</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- BOOKING MODAL -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: 1px solid rgba(139,92,246,0.1);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(139,92,246,0.08); padding: 1.2rem;">
                <h5 class="modal-title" style="font-weight: 700; color: #1e1b4b;"><i class="bi bi-airplane me-2" style="color: #8b5cf6;"></i>Confirm Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="book_tour.php" method="POST">
                <div class="modal-body" style="padding: 1.2rem;">
                    <input type="hidden" name="tour_name" id="bookTourName">
                    <input type="hidden" name="tour_price" id="bookTourPrice">
                    <div style="background: rgba(139,92,246,0.03); border-radius: 12px; padding: 14px; margin-bottom: 1rem; border: 1px solid rgba(139,92,246,0.06);">
                        <h6 id="bookTourTitle" style="color: #1e1b4b; font-weight: 700; margin-bottom: 4px;"></h6>
                        <p id="bookTourDest" style="color: #8b5cf6; font-size: 0.82rem; margin-bottom: 6px;"></p>
                        <div class="d-flex justify-content-between">
                            <span style="color: #6b7280; font-size: 0.82rem;" id="bookTourDays"></span>
                            <span style="color: #1e1b4b; font-weight: 800; font-size: 1rem;" id="bookTourPriceDisplay"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label style="color: #6b7280; font-size: 0.8rem; font-weight: 500;">Number of Guests</label>
                        <select name="guests" class="form-select" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.12);">
                            <option value="1">1 Guest</option><option value="2" selected>2 Guests</option><option value="3">3</option><option value="4">4</option><option value="5">5+</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label style="color: #6b7280; font-size: 0.8rem; font-weight: 500;">Preferred Date</label>
                        <input type="date" name="check_in" class="form-control" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.12);" required min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="mb-3">
                        <label style="color: #6b7280; font-size: 0.8rem; font-weight: 500;">Special Requests (optional)</label>
                        <textarea name="notes" class="form-control" rows="2" style="border-radius: 10px; border: 1px solid rgba(139,92,246,0.12);" placeholder="Any dietary or accessibility needs..."></textarea>
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

<!-- FAQ -->
<section class="faq-section">
    <div class="container">
        <div class="text-center mb-4" data-aos="fade-up">
            <span style="color: #8b5cf6; font-size: 0.72rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">GOT QUESTIONS?</span>
            <h2 style="color: #1e1b4b; font-family: 'Playfair Display'; font-weight: 800; font-size: 1.8rem; margin-top: 6px;">Frequently Asked Questions</h2>
        </div>
        <div class="col-lg-8 mx-auto">
            <?php
            $faqs = [
                ["What's included in the tour price?", "Our tour packages include accommodation, daily meals, guided tours, airport transfers, and activities. International flights are not included unless specified."],
                ["Can I customize my tour?", "Yes! We offer custom itineraries for groups of 4+. Contact us at +91 98765 43210 to discuss your preferences."],
                ["What is your cancellation policy?", "Free cancellation up to 30 days before departure. 15-30 days: 25% fee. Under 15 days: 50%."],
                ["Are dietary needs accommodated?", "Absolutely! We cater to vegetarian, vegan, Jain, and gluten-free diets. Mention your preferences during booking."],
                ["Payment methods?", "UPI, net banking, credit/debit cards (Visa, Mastercard, RuPay), EMI, PhonePe, Google Pay, and Paytm."],
            ];
            foreach($faqs as $j => $f): ?>
            <div class="faq-item" data-aos="fade-up" data-aos-delay="<?= ($j+1)*50 ?>">
                <div class="faq-q" onclick="toggleFaq(this)"><?= $f[0] ?><i class="bi bi-chevron-down"></i></div>
                <div class="faq-a"><?= $f[1] ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
function filterTours() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const dur = document.getElementById('filterDuration').value;
    const price = document.getElementById('filterPrice').value;
    document.querySelectorAll('.tour-card-wrap').forEach(w => {
        const days = parseInt(w.dataset.days)||0, p = parseInt(w.dataset.price)||0;
        const text = (w.dataset.title||'') + ' ' + (w.dataset.dest||'');
        let show = true;
        if (q && !text.includes(q)) show = false;
        if (dur==='3' && !(days>=3&&days<=4)) show = false;
        if (dur==='5' && !(days>=5&&days<=7)) show = false;
        if (dur==='8' && days<8) show = false;
        if (price==='50000' && p>=50000) show = false;
        if (price==='100000' && !(p>=50000&&p<100000)) show = false;
        if (price==='200000' && p<100000) show = false;
        w.style.display = show ? '' : 'none';
    });
}
function clearFilters() {
    document.getElementById('searchInput').value='';
    document.getElementById('filterDuration').value='';
    document.getElementById('filterPrice').value='';
    filterTours();
}
document.getElementById('searchInput').addEventListener('keyup', filterTours);
document.getElementById('filterDuration').addEventListener('change', filterTours);
document.getElementById('filterPrice').addEventListener('change', filterTours);

document.querySelectorAll('.book-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        if(!btn.dataset.tour) return;
        document.getElementById('bookTourName').value = btn.dataset.tour;
        document.getElementById('bookTourPrice').value = btn.dataset.price;
        document.getElementById('bookTourTitle').textContent = btn.dataset.tour;
        document.getElementById('bookTourDest').innerHTML = '<i class="bi bi-geo-alt me-1"></i>' + btn.dataset.dest;
        document.getElementById('bookTourDays').innerHTML = '<i class="bi bi-calendar3 me-1"></i>' + btn.dataset.days + ' Days';
        document.getElementById('bookTourPriceDisplay').textContent = '₹' + Number(btn.dataset.price).toLocaleString('en-IN');
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    });
});

function toggleFaq(el) {
    const a = el.nextElementSibling, open = a.classList.contains('show');
    document.querySelectorAll('.faq-a').forEach(x => x.classList.remove('show'));
    document.querySelectorAll('.faq-q').forEach(x => x.classList.remove('active'));
    if(!open) { a.classList.add('show'); el.classList.add('active'); }
}
</script>

<?php include("includes/footer.php"); ?>
