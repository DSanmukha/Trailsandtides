<?php include("includes/header.php"); ?>

<style>
/* Tours Hero — matches destinations.php pattern */
.tours-hero {
    background: linear-gradient(135deg, rgba(30,27,75,0.88), rgba(76,29,149,0.78)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1600&h=900&fit=crop') center/cover;
    padding: 160px 0 80px; text-align: center; margin-top: -76px;
}
.tours-hero h1 { font-family: 'Playfair Display'; color: white; font-size: 3rem; font-weight: 800; margin-bottom: 8px; }
.tours-hero p { color: rgba(255,255,255,0.65); font-size: 1.1rem; max-width: 500px; margin: 0 auto; }

/* Filter bar — white glass like destinations */
.tours-filter-bar {
    background: rgba(255,255,255,0.92); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.1); border-radius: 20px;
    padding: 1.25rem 1.5rem; margin-top: -30px; position: relative; z-index: 2;
}
.tours-filter-bar .form-control, .tours-filter-bar .form-select {
    border: 1px solid rgba(139,92,246,0.15); border-radius: 12px; color: #1e1b4b;
}
.tours-filter-bar .form-control:focus, .tours-filter-bar .form-select:focus {
    border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
}

/* Tour Cards - Premium Design */
.tour-card {
    background: white; border-radius: 20px; overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06); transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    border: 1px solid rgba(139,92,246,0.06); height: 100%;
    display: flex; flex-direction: column;
}
.tour-card:hover { transform: translateY(-10px); box-shadow: 0 20px 50px rgba(139,92,246,0.12); }
.tour-card-img {
    height: 220px; background-size: cover; background-position: center;
    position: relative; overflow: hidden;
}
.tour-card-img::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 80px; background: linear-gradient(transparent, rgba(0,0,0,0.4)); }
.tour-tag {
    position: absolute; top: 14px; left: 14px; z-index: 2;
    padding: 4px 12px; border-radius: 8px; font-size: 0.68rem; font-weight: 700;
    letter-spacing: 0.5px; text-transform: uppercase; color: white;
    background: linear-gradient(135deg, #8b5cf6, #c084fc);
}
.tour-rating {
    position: absolute; bottom: 14px; left: 14px; z-index: 2;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(8px); color: white;
    padding: 4px 10px; border-radius: 8px; font-size: 0.72rem; font-weight: 500;
}
.tour-rating i { color: #fbbf24; margin-right: 3px; }
.tour-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
.tour-body h4 { color: #1e1b4b; font-weight: 700; font-size: 1.1rem; margin-bottom: 6px; }
.tour-body .desc { color: #6b7280; font-size: 0.84rem; line-height: 1.5; margin-bottom: 14px; }
.tour-meta { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
.tour-meta span { color: #8b5cf6; font-size: 0.78rem; font-weight: 500; display: flex; align-items: center; gap: 4px; }
.tour-meta span i { font-size: 0.85rem; }
.tour-includes { margin-bottom: 16px; flex: 1; }
.tour-includes h6 { color: #1e1b4b; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
.tour-includes ul { list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
.tour-includes li { color: #6b7280; font-size: 0.78rem; display: flex; align-items: center; gap: 6px; }
.tour-includes li i { color: #22c55e; font-size: 0.7rem; }
.tour-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 14px; border-top: 1px solid rgba(139,92,246,0.08); }
.tour-footer .old-price { color: #9ca3af; font-size: 0.78rem; text-decoration: line-through; display: block; }
.tour-footer .price { color: #1e1b4b; font-size: 1.3rem; font-weight: 800; }
.tour-footer .book-btn {
    background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; border: none;
    padding: 10px 22px; border-radius: 12px; font-weight: 600; font-size: 0.85rem;
    cursor: pointer; transition: all 0.3s ease; font-family: 'Poppins';
}
.tour-footer .book-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(139,92,246,0.3); }

/* FAQ Section */
.faq-section { background: linear-gradient(180deg, #f5f3ff, #ede9fe); padding: 4rem 0; }
.faq-item {
    background: white; border-radius: 16px; margin-bottom: 12px;
    border: 1px solid rgba(139,92,246,0.08); overflow: hidden;
    transition: all 0.3s ease;
}
.faq-item:hover { border-color: rgba(139,92,246,0.15); }
.faq-q {
    padding: 18px 24px; cursor: pointer; display: flex; justify-content: space-between;
    align-items: center; font-weight: 600; color: #1e1b4b; font-size: 0.95rem;
}
.faq-q i { color: #8b5cf6; transition: transform 0.3s ease; font-size: 1.1rem; }
.faq-q.active i { transform: rotate(180deg); }
.faq-a { padding: 0 24px 18px; color: #6b7280; font-size: 0.88rem; line-height: 1.7; display: none; }
.faq-a.show { display: block; }
</style>

<!-- HERO -->
<div class="tours-hero">
    <div class="container">
        <h1 data-aos="fade-up">Our Tour Packages</h1>
        <p data-aos="fade-up" data-aos-delay="100">Handcrafted experiences by travel experts across the globe</p>
    </div>
</div>

<!-- FILTER BAR -->
<div class="container">
    <div class="tours-filter-bar" data-aos="fade-up">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="🔍 Search tours..." id="searchInput">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterDuration">
                    <option value="">All Durations</option>
                    <option value="3">3-4 Days</option>
                    <option value="5">5-7 Days</option>
                    <option value="8">8+ Days</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterPrice">
                    <option value="">All Prices</option>
                    <option value="50000">Under ₹50,000</option>
                    <option value="100000">₹50,000 - ₹1,00,000</option>
                    <option value="200000">₹1,00,000+</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn w-100" id="searchBtn" style="background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; border-radius: 12px; font-weight: 600; border: none; padding: 10px;" onclick="filterTours()"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-1">
                <button class="btn w-100" style="border: 1px solid rgba(139,92,246,0.3); border-radius: 12px; color: #8b5cf6; font-weight: 600; padding: 10px;" onclick="clearFilters()"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- TOUR CARDS -->
<section class="py-5" style="background: #f5f3ff;">
    <div class="container">
        <div class="row g-4">

            <?php
            $tours = [
                ['title'=>'Bali Paradise Experience','desc'=>'Discover the magic of Bali with pristine beaches, ancient temples, and vibrant culture.','dest'=>'Bali, Indonesia','days'=>7,'price'=>'1,49,999','orig'=>'1,99,999','img'=>'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=600&h=400&fit=crop&auto=format','tag'=>'POPULAR','rating'=>'4.8','reviews'=>284,'inc'=>['5-star Hotel','Daily Breakfast','Guided Tours','Airport Transfer'],'group'=>'Group Tours'],
                ['title'=>'Swiss Alps Adventure','desc'=>'Experience breathtaking mountain landscapes, alpine meadows, and charming Swiss villages.','dest'=>'Switzerland','days'=>5,'price'=>'2,19,999','orig'=>'2,59,999','img'=>'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop&auto=format','tag'=>'LUXURY','rating'=>'4.9','reviews'=>156,'inc'=>['Luxury Hotel','3 Course Meals','Cable Car Tours','Wine Tasting'],'group'=>'Small Groups'],
                ['title'=>'Tokyo Lights &amp; Culture','desc'=>'Immerse yourself in neon-lit streets, ancient temples, and the finest Japanese cuisine.','dest'=>'Tokyo, Japan','days'=>4,'price'=>'1,19,999','orig'=>'1,49,999','img'=>'https://images.unsplash.com/photo-1532236204992-f5e85c024202?w=600&h=400&fit=crop&auto=format','tag'=>'FAMILY','rating'=>'4.7','reviews'=>342,'inc'=>['4-star Hotel','JR Pass','Museum Tours','Local Cuisine'],'group'=>'All Ages'],
                ['title'=>'Maldives Romantic Getaway','desc'=>'Perfect romantic escape with overwater bungalows and pristine coral reefs.','dest'=>'Maldives','days'=>6,'price'=>'2,99,999','orig'=>'3,49,999','img'=>'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=600&h=400&fit=crop&auto=format','tag'=>'HONEYMOON','rating'=>'5.0','reviews'=>89,'inc'=>['Water Bungalow','All Meals','Spa Treatment','Sunset Cruise'],'group'=>'Couples'],
                ['title'=>'Iceland Northern Lights','desc'=>'Witness waterfalls, glaciers, and the magical aurora borealis in Iceland.','dest'=>'Iceland','days'=>8,'price'=>'2,39,999','orig'=>'2,89,999','img'=>'https://images.unsplash.com/photo-1531366936337-7c912a4589a7?w=600&h=400&fit=crop&auto=format','tag'=>'ADVENTURE','rating'=>'4.6','reviews'=>201,'inc'=>['4x4 Vehicle','Northern Lights Tour','Glacier Hike','Hot Spring Visit'],'group'=>'Adventure Seekers'],
                ['title'=>'Egypt Historical Journey','desc'=>'Explore ancient pyramids, pharaoh tombs, and cruise the majestic Nile River.','dest'=>'Egypt','days'=>9,'price'=>'1,89,999','orig'=>'2,29,999','img'=>'https://images.unsplash.com/photo-1590422749897-47036da0b0ff?w=600&h=400&fit=crop&auto=format','tag'=>'CULTURAL','rating'=>'4.8','reviews'=>127,'inc'=>['Nile Cruise','Pyramid Tours','Museum Entry','Expert Guide'],'group'=>'History Enthusiasts'],
            ];
            foreach($tours as $i => $t): ?>
            <div class="col-lg-4 col-md-6 tour-card-wrap" data-aos="fade-up" data-aos-delay="<?= (($i % 3) + 1) * 100 ?>" data-title="<?= htmlspecialchars(strtolower($t['title'])) ?>" data-dest="<?= htmlspecialchars(strtolower($t['dest'])) ?>" data-days="<?= $t['days'] ?>" data-price="<?= str_replace(',','',$t['price']) ?>">
                <div class="tour-card">
                    <div class="tour-card-img" style="background-image: url('<?= $t['img'] ?>');">
                        <span class="tour-tag"><?= $t['tag'] ?></span>
                        <span class="tour-rating"><i class="bi bi-star-fill"></i> <?= $t['rating'] ?>/5 (<?= $t['reviews'] ?> reviews)</span>
                    </div>
                    <div class="tour-body">
                        <h4><?= $t['title'] ?></h4>
                        <p class="desc"><?= $t['desc'] ?></p>
                        
                        <div class="tour-meta">
                            <span><i class="bi bi-calendar3"></i> <?= $t['days'] ?> Days</span>
                            <span><i class="bi bi-geo-alt"></i> <?= $t['dest'] ?></span>
                            <span><i class="bi bi-people"></i> <?= $t['group'] ?></span>
                        </div>

                        <div class="tour-includes">
                            <h6>Includes:</h6>
                            <ul>
                                <?php foreach($t['inc'] as $inc): ?>
                                <li><i class="bi bi-check-circle-fill"></i> <?= $inc ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="tour-footer">
                            <div>
                                <span class="old-price">₹<?= $t['orig'] ?></span>
                                <span class="price">₹<?= $t['price'] ?></span>
                            </div>
                            <?php if(isset($_SESSION['user'])): ?>
                            <button class="book-btn" 
                                data-tour="<?= htmlspecialchars($t['title']) ?>" 
                                data-price="<?= str_replace(',','',$t['price']) ?>"
                                data-days="<?= $t['days'] ?>"
                                data-dest="<?= htmlspecialchars($t['dest']) ?>">
                                <i class="bi bi-airplane me-1"></i>Book Now
                            </button>
                            <?php else: ?>
                            <a href="auth/login.php" class="book-btn" style="text-decoration:none;"><i class="bi bi-box-arrow-in-right me-1"></i>Login to Book</a>
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
        <div class="modal-content" style="border-radius: 20px; border: 1px solid rgba(139,92,246,0.15); background: white;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(139,92,246,0.1); padding: 1.5rem;">
                <h5 class="modal-title" style="font-weight: 700; color: #1e1b4b;"><i class="bi bi-airplane me-2" style="color: #8b5cf6;"></i>Confirm Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="book_tour.php" method="POST">
                <div class="modal-body" style="padding: 1.5rem;">
                    <input type="hidden" name="tour_name" id="bookTourName">
                    <input type="hidden" name="tour_price" id="bookTourPrice">
                    
                    <div style="background: rgba(139,92,246,0.04); border-radius: 14px; padding: 16px; margin-bottom: 1.25rem; border: 1px solid rgba(139,92,246,0.08);">
                        <h6 id="bookTourTitle" style="color: #1e1b4b; font-weight: 700; margin-bottom: 4px;"></h6>
                        <p id="bookTourDest" style="color: #8b5cf6; font-size: 0.85rem; margin-bottom: 8px;"></p>
                        <div class="d-flex justify-content-between">
                            <span style="color: #6b7280; font-size: 0.85rem;" id="bookTourDays"></span>
                            <span style="color: #1e1b4b; font-weight: 800; font-size: 1.1rem;" id="bookTourPriceDisplay"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label style="color: #6b7280; font-size: 0.82rem; font-weight: 500;">Number of Guests</label>
                        <select name="guests" class="form-select" style="border-radius: 12px; border: 1px solid rgba(139,92,246,0.15);">
                            <option value="1">1 Guest</option>
                            <option value="2" selected>2 Guests</option>
                            <option value="3">3 Guests</option>
                            <option value="4">4 Guests</option>
                            <option value="5">5+ Guests</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label style="color: #6b7280; font-size: 0.82rem; font-weight: 500;">Preferred Date</label>
                        <input type="date" name="check_in" class="form-control" style="border-radius: 12px; border: 1px solid rgba(139,92,246,0.15);" required>
                    </div>
                    <div class="mb-3">
                        <label style="color: #6b7280; font-size: 0.82rem; font-weight: 500;">Special Requests (optional)</label>
                        <textarea name="notes" class="form-control" rows="2" style="border-radius: 12px; border: 1px solid rgba(139,92,246,0.15);" placeholder="Any dietary or accessibility needs..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(139,92,246,0.1); padding: 1.5rem;">
                    <button type="button" class="btn" data-bs-dismiss="modal" style="border-radius: 12px; border: 1px solid rgba(139,92,246,0.2); color: #1e1b4b; padding: 10px 24px;">Cancel</button>
                    <button type="submit" name="book" class="btn" style="background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; border: none; border-radius: 12px; padding: 10px 28px; font-weight: 600; box-shadow: 0 4px 12px rgba(139,92,246,0.25);">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<section class="faq-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span style="color: #8b5cf6; font-size: 0.75rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">GOT QUESTIONS?</span>
            <h2 style="color: #1e1b4b; font-family: 'Playfair Display'; font-weight: 800; font-size: 2.2rem; margin-top: 8px;">Frequently Asked Questions</h2>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        What's included in the tour price?
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        Our tour packages typically include accommodation, daily meals, guided tours, airport transfers, and activities as mentioned in each package. International flights are not included unless specified.
                    </div>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="150">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        Can I customize my tour itinerary?
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        Yes! We offer custom itinerary options for groups of 4 or more. Contact our travel experts at +91 98765 43210 to discuss your preferences and we'll craft a personalized experience.
                    </div>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        What is your cancellation policy?
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        Free cancellation up to 30 days before departure. Cancellations within 15-30 days incur a 25% fee. Within 15 days, 50% will be charged. Travel insurance is highly recommended.
                    </div>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="250">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        Are meals included for vegetarian/vegan travellers?
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        Absolutely! We cater to all dietary requirements including vegetarian, vegan, Jain, and gluten-free options. Please mention your preferences during booking.
                    </div>
                </div>
                <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        What payment methods do you accept?
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-a">
                        We accept UPI, net banking, credit/debit cards (Visa, Mastercard, RuPay), and EMI options through popular banks. We also accept payments via PhonePe, Google Pay, and Paytm.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// ─── SEARCH & FILTER ───────────────────────────────────────────
function filterTours() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const dur   = document.getElementById('filterDuration').value;
    const price = document.getElementById('filterPrice').value;

    document.querySelectorAll('.tour-card-wrap').forEach(wrap => {
        const title = (wrap.dataset.title || '').toLowerCase();
        const dest  = (wrap.dataset.dest  || '').toLowerCase();
        const days  = parseInt(wrap.dataset.days) || 0;
        const p     = parseInt(wrap.dataset.price) || 0;

        let show = true;
        if (query && !title.includes(query) && !dest.includes(query)) show = false;
        if (dur === '3' && !(days >= 3 && days <= 4)) show = false;
        if (dur === '5' && !(days >= 5 && days <= 7)) show = false;
        if (dur === '8' && days < 8) show = false;
        if (price === '50000'  && p >= 50000)  show = false;
        if (price === '100000' && !(p >= 50000 && p < 100000)) show = false;
        if (price === '200000' && p < 100000)  show = false;

        wrap.style.display = show ? '' : 'none';
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterDuration').value = '';
    document.getElementById('filterPrice').value = '';
    filterTours();
}

document.getElementById('searchInput').addEventListener('keyup', filterTours);
document.getElementById('filterDuration').addEventListener('change', filterTours);
document.getElementById('filterPrice').addEventListener('change', filterTours);
document.getElementById('searchInput').addEventListener('keydown', e => { if(e.key === 'Enter') filterTours(); });

// ─── BOOKING MODAL ────────────────────────────────────────────
document.querySelectorAll('.book-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        if(!btn.dataset.tour) return;
        e.preventDefault();
        document.getElementById('bookTourName').value = btn.dataset.tour;
        document.getElementById('bookTourPrice').value = btn.dataset.price;
        document.getElementById('bookTourTitle').textContent = btn.dataset.tour;
        document.getElementById('bookTourDest').innerHTML = '<i class="bi bi-geo-alt me-1"></i>' + btn.dataset.dest;
        document.getElementById('bookTourDays').innerHTML = '<i class="bi bi-calendar3 me-1"></i>' + btn.dataset.days + ' Days';
        document.getElementById('bookTourPriceDisplay').textContent = '₹' + Number(btn.dataset.price).toLocaleString('en-IN');
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    });
});

// ─── FAQ TOGGLE ───────────────────────────────────────────────
function toggleFaq(el) {
    const answer = el.nextElementSibling;
    const isOpen = answer.classList.contains('show');
    document.querySelectorAll('.faq-a').forEach(a => a.classList.remove('show'));
    document.querySelectorAll('.faq-q').forEach(q => q.classList.remove('active'));
    if(!isOpen) { answer.classList.add('show'); el.classList.add('active'); }
}
</script>

<?php include("includes/footer.php"); ?>
