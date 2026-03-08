<?php include("includes/header.php"); ?>

<style>
/* ===== INDEX PAGE ENHANCEMENTS ===== */

/* Wave Dividers */
.wave-divider { position: relative; width: 100%; overflow: hidden; line-height: 0; }
.wave-divider svg { display: block; width: 100%; height: 70px; }
.wave-divider.flip { transform: rotate(180deg); }

/* Floating accent shapes */
.floating-shapes { position: absolute; inset: 0; overflow: hidden; pointer-events: none; z-index: 0; }
.floating-shapes .shape {
    position: absolute; border-radius: 50%; opacity: 0.06;
    animation: floatShape 20s ease-in-out infinite;
}
.floating-shapes .shape:nth-child(1) { width: 300px; height: 300px; background: #8b5cf6; top: 10%; left: -5%; animation-delay: 0s; }
.floating-shapes .shape:nth-child(2) { width: 200px; height: 200px; background: #f97316; top: 50%; right: -3%; animation-delay: 5s; }
.floating-shapes .shape:nth-child(3) { width: 150px; height: 150px; background: #06b6d4; bottom: 10%; left: 30%; animation-delay: 10s; }
@keyframes floatShape { 0%,100% { transform: translateY(0) scale(1); } 50% { transform: translateY(-30px) scale(1.1); } }

/* Stats counter bar */
.stats-bar {
    background: linear-gradient(135deg, #1e1b4b, #312e81, #1e1b4b);
    padding: 3rem 0; position: relative; overflow: hidden;
}
.stats-bar::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse at 30% 50%, rgba(139,92,246,0.15) 0%, transparent 70%);
}
.stat-item { text-align: center; position: relative; }
.stat-number {
    font-family: 'Montserrat', sans-serif; font-size: 2.75rem;
    font-weight: 900; color: white; line-height: 1;
    background: linear-gradient(135deg, #c4b5fd, #f9a8d4);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
}
.stat-label { color: rgba(255,255,255,0.6); font-size: 0.82rem; margin-top: 4px; font-weight: 400; letter-spacing: 1px; text-transform: uppercase; }
.stat-divider { width: 1px; height: 50px; background: rgba(255,255,255,0.1); margin: auto; }

/* Testimonials */
.testimonial-section { position: relative; overflow: hidden; }
.testimonial-card {
    background: rgba(255,255,255,0.85); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.1); border-radius: 20px;
    padding: 2rem; text-align: center; transition: all 0.4s ease;
    position: relative; overflow: hidden;
}
.testimonial-card::before {
    content: '"'; position: absolute; top: -10px; left: 20px;
    font-size: 6rem; color: rgba(139,92,246,0.08); font-family: 'Playfair Display', serif; line-height: 1;
}
.testimonial-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.08); border-color: rgba(139,92,246,0.25); }
.testimonial-avatar {
    width: 60px; height: 60px; border-radius: 50%; margin: 0 auto 1rem;
    background: linear-gradient(135deg, #8b5cf6, #c084fc); display: flex;
    align-items: center; justify-content: center; color: white; font-size: 1.4rem; font-weight: 700;
}
.testimonial-text { color: #374151; font-size: 0.92rem; line-height: 1.7; margin-bottom: 1rem; font-style: italic; }
.testimonial-name { color: #1e1b4b; font-weight: 700; font-size: 0.9rem; margin-bottom: 2px; }
.testimonial-trip { color: #8b5cf6; font-size: 0.78rem; font-weight: 500; }
.testimonial-stars { color: #f59e0b; margin-bottom: 0.75rem; letter-spacing: 2px; }

/* Enhanced offer cards */
.offer-card-enhanced {
    background: rgba(255,255,255,0.85); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.1); border-radius: 20px;
    padding: 3rem 1.5rem 2rem; text-align: center; transition: all 0.4s ease;
    position: relative;
}
.offer-badge {
    position: absolute; top: 16px; left: 16px;
    background: linear-gradient(135deg, #8b5cf6, #c084fc);
    color: white; padding: 5px 14px; border-radius: 10px;
    font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px; z-index: 2;
}
.offer-card-enhanced::before {
    content: ''; position: absolute; top: -60px; right: -60px;
    width: 150px; height: 150px; border-radius: 50%;
    transition: all 0.4s ease; opacity: 0.1;
}
.offer-card-enhanced.offer-coral::before { background: #f97316; }
.offer-card-enhanced.offer-purple::before { background: #8b5cf6; }
.offer-card-enhanced.offer-cyan::before { background: #06b6d4; }
.offer-card-enhanced:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); border-color: rgba(139,92,246,0.25); }
.offer-card-enhanced:hover::before { transform: scale(2.5); }
.offer-icon-wrap {
    width: 70px; height: 70px; border-radius: 20px; margin: 0 auto 1.25rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem; color: white; position: relative;
}
.offer-coral .offer-icon-wrap { background: linear-gradient(135deg, #f97316, #fb923c); box-shadow: 0 8px 20px rgba(249,115,22,0.3); }
.offer-purple .offer-icon-wrap { background: linear-gradient(135deg, #8b5cf6, #a78bfa); box-shadow: 0 8px 20px rgba(139,92,246,0.3); }
.offer-cyan .offer-icon-wrap { background: linear-gradient(135deg, #06b6d4, #22d3ee); box-shadow: 0 8px 20px rgba(6,182,212,0.3); }

/* Gallery strip */
.gallery-strip {
    display: flex; gap: 0; overflow: hidden; height: 200px;
}
.gallery-strip img {
    flex: 1; min-width: 0; height: 100%; object-fit: cover;
    filter: brightness(0.85); transition: all 0.5s ease; cursor: pointer;
}
.gallery-strip img:hover { flex: 2.5; filter: brightness(1); }

/* Enhanced CTA */
.cta-enhanced {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #312e81 70%, #7c3aed 100%);
    position: relative; overflow: hidden;
}
.cta-enhanced::before {
    content: ''; position: absolute; top: -100px; right: -100px;
    width: 400px; height: 400px; border-radius: 50%;
    background: rgba(249,115,22,0.12); filter: blur(80px);
}
.cta-enhanced::after {
    content: ''; position: absolute; bottom: -80px; left: -80px;
    width: 300px; height: 300px; border-radius: 50%;
    background: rgba(6,182,212,0.1); filter: blur(60px);
}
</style>

<!-- FULL-BLEED VIDEO CAROUSEL HERO -->
<section class="video-hero-home" id="heroSection">
    <video class="hero-video active-video" autoplay muted loop playsinline data-label="Ocean">
        <source src="assets/images/mainocean.mp4" type="video/mp4">
    </video>
    <video class="hero-video" muted loop playsinline data-label="Swiss Alps">
        <source src="assets/images/swiss aples.mp4" type="video/mp4">
    </video>
    <video class="hero-video" muted loop playsinline data-label="Paris">
        <source src="assets/images/pariss.mp4" type="video/mp4">
    </video>
    <video class="hero-video" muted loop playsinline data-label="Bali">
        <source src="assets/images/bali.mp4" type="video/mp4">
    </video>
    <video class="hero-video" muted loop playsinline data-label="Tokyo">
        <source src="assets/images/tokyo.mp4" type="video/mp4">
    </video>
    <video class="hero-video" muted loop playsinline data-label="Skiing">
        <source src="assets/images/skiing.mp4" type="video/mp4">
    </video>
    
    <img id="homeHeroFallback" class="hero-fallback-img" src="assets/images/hero.jpg" alt="Travel Hero">
    <div class="video-overlay"></div>
    
    <div class="hero-content">
        <div class="hero-glass-panel">
            <h1 class="hero-title">Trip to <span class="highlight" id="heroDestination">the Ocean</span></h1>
            <p class="hero-subtitle">Discover breathtaking destinations & create unforgettable journeys</p>
            <div class="hero-buttons">
                <a href="tours.php" class="btn-hero-primary"><i class="bi bi-compass"></i> View Trip Selection</a>
                <a href="destinations.php" class="btn-hero-outline"><i class="bi bi-geo-alt"></i> Explore</a>
            </div>
        </div>
    </div>
    
    <div class="hero-info-bar">
        <div class="hero-info-pills">
            <a href="tel:+919876543210" class="hero-pill"><i class="bi bi-telephone"></i> +91 98765 43210</a>
            <a href="destinations.php" class="hero-pill"><i class="bi bi-water"></i> <span id="heroLabel">Ocean Trails</span></a>
            <a href="tours.php" class="hero-pill"><i class="bi bi-airplane"></i> Active Trips</a>
            <a href="hotels.php" class="hero-pill"><i class="bi bi-building"></i> Trails_hotel</a>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div class="carousel-dots" id="carouselDots"></div>
            <a href="#featured-tours" class="hero-pill" style="background: rgba(255,255,255,0.18);">view more about →</a>
        </div>
    </div>
</section>

<!-- STATS COUNTER BAR -->
<section class="stats-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-number" data-count="500">0</div><div class="stat-label">Happy Travelers</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-number" data-count="120">0</div><div class="stat-label">Destinations</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-number" data-count="50">0</div><div class="stat-label">Tour Packages</div></div></div>
            <div class="col-6 col-md-3"><div class="stat-item"><div class="stat-number" data-count="98">0</div><div class="stat-label">Satisfaction %</div></div></div>
        </div>
    </div>
</section>

<!-- FEATURED TOURS -->
<section class="featured-tours py-5" id="featured-tours" style="position: relative;">
    <div class="floating-shapes">
        <div class="shape"></div><div class="shape"></div><div class="shape"></div>
    </div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-label">OUR SIGNATURE EXPERIENCES</span>
            <h2 class="section-title">Popular Tours</h2>
            <p class="section-subtitle">Handpicked tours crafted by travel experts for unforgettable journeys</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="tour-card">
                    <div class="tour-image" style="background-image: url('assets/images/bali.jpg');"><div class="tour-badge">7 Days</div></div>
                    <div class="tour-content">
                        <h4>Bali Paradise</h4>
                        <p>Experience the magic of Bali — pristine beaches, sacred temples, and lush rice terraces</p>
                        <div class="tour-footer">
                            <span class="tour-rating-text"><i class="bi bi-star-fill" style="color: #f59e0b;"></i> 4.8/5</span>
                            <a href="tours.php" class="tour-btn">Learn More →</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="tour-card">
                    <div class="tour-image" style="background-image: url('assets/images/paris.jpg');"><div class="tour-badge">5 Days</div></div>
                    <div class="tour-content">
                        <h4>Paris Romance</h4>
                        <p>The city of love — iconic landmarks, world-class cuisine, and charming neighborhoods</p>
                        <div class="tour-footer">
                            <span class="tour-rating-text"><i class="bi bi-star-fill" style="color: #f59e0b;"></i> 4.9/5</span>
                            <a href="tours.php" class="tour-btn">Learn More →</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="tour-card">
                    <div class="tour-image" style="background-image: url('assets/images/tokyo.jpg');"><div class="tour-badge">4 Days</div></div>
                    <div class="tour-content">
                        <h4>Tokyo Lights</h4>
                        <p>Immerse yourself in the blend of modern technology and ancient traditions</p>
                        <div class="tour-footer">
                            <span class="tour-rating-text"><i class="bi bi-star-fill" style="color: #f59e0b;"></i> 4.7/5</span>
                            <a href="tours.php" class="tour-btn">Learn More →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-5" data-aos="fade-up">
            <a href="tours.php" class="btn-hero-primary" style="background: linear-gradient(135deg, #8b5cf6, #c084fc); border: none; box-shadow: 0 8px 24px rgba(139,92,246,0.3);">View All Tours</a>
        </div>
    </div>
</section>

<!-- WAVE DIVIDER -->
<div class="wave-divider">
    <svg viewBox="0 0 1200 80" preserveAspectRatio="none">
        <path d="M0,40 Q150,0 300,40 T600,40 T900,40 T1200,40 L1200,80 L0,80Z" fill="#faf5ff"/>
    </svg>
</div>

<!-- TOP DESTINATIONS -->
<section class="destinations-section py-5" style="background: #faf5ff; position: relative;">
    <div class="floating-shapes">
        <div class="shape" style="background: #f97316;"></div><div class="shape" style="background: #8b5cf6;"></div><div class="shape" style="background: #ec4899;"></div>
    </div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-label">EXPLORE THE WORLD</span>
            <h2 class="section-title">Top Destinations</h2>
            <p class="section-subtitle">Stunning places waiting to be discovered</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
                <div class="destination-card-modern">
                    <div class="dest-img-wrapper">
                        <img src="assets/images/bali.jpg" alt="Bali" class="dest-check-img">
                        <div class="dest-overlay"></div>
                    </div>
                    <div class="dest-content"><h4>Bali, Indonesia</h4><p>Tropical beaches & ancient temples</p><div class="dest-meta"><span class="dest-temp">28°C <i class="bi bi-sun"></i></span><a href="destinations.php" class="dest-explore-btn">Explore</a></div></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="200">
                <div class="destination-card-modern">
                    <div class="dest-img-wrapper">
                        <img src="assets/images/paris.jpg" alt="Paris" class="dest-check-img">
                        <div class="dest-overlay"></div>
                    </div>
                    <div class="dest-content"><h4>Paris, France</h4><p>City of love & iconic landmarks</p><div class="dest-meta"><span class="dest-temp">18°C <i class="bi bi-cloud-sun"></i></span><a href="destinations.php" class="dest-explore-btn">Explore</a></div></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="300">
                <div class="destination-card-modern">
                    <div class="dest-img-wrapper">
                        <img src="assets/images/maldives.jpg" alt="Maldives" class="dest-check-img">
                        <div class="dest-overlay"></div>
                    </div>
                    <div class="dest-content"><h4>Maldives</h4><p>Overwater bungalows & coral reefs</p><div class="dest-meta"><span class="dest-temp">29°C <i class="bi bi-sun"></i></span><a href="destinations.php" class="dest-explore-btn">Explore</a></div></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="400">
                <div class="destination-card-modern">
                    <div class="dest-img-wrapper">
                        <img src="assets/images/tokyo.jpg" alt="Japan" class="dest-check-img">
                        <div class="dest-overlay"></div>
                    </div>
                    <div class="dest-content"><h4>Tokyo, Japan</h4><p>Modern tech meets ancient tradition</p><div class="dest-meta"><span class="dest-temp">20°C <i class="bi bi-cloud"></i></span><a href="destinations.php" class="dest-explore-btn">Explore</a></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- GALLERY STRIP -->
<div class="gallery-strip" data-aos="fade-up">
    <img src="assets/images/bali.jpg" alt="Bali" onerror="this.style.display='none'">
    <img src="assets/images/beach.jpg" alt="Beach" onerror="this.style.display='none'">
    <img src="assets/images/swiss aples.jpg" alt="Swiss Alps" onerror="this.style.display='none'">
    <img src="assets/images/maldives.jpg" alt="Maldives" onerror="this.style.display='none'">
    <img src="assets/images/paris.jpg" alt="Paris" onerror="this.style.display='none'">
    <img src="assets/images/newyork.jpg" alt="New York" onerror="this.style.display='none'">
    <img src="assets/images/tokyo.jpg" alt="Tokyo" onerror="this.style.display='none'">
</div>

<!-- SPECIAL OFFERS — ENHANCED -->
<section class="offers-section py-5" style="position: relative;">
    <div class="floating-shapes">
        <div class="shape" style="background: #06b6d4;"></div><div class="shape" style="background: #f97316;"></div><div class="shape" style="background: #8b5cf6;"></div>
    </div>
    <div class="container" style="position: relative; z-index: 1;">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-label">EXCLUSIVE DEALS</span>
            <h2 class="section-title">Special Offers</h2>
            <p class="section-subtitle">Limited-time deals curated for adventurers like you</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="100">
                <div class="offer-card-enhanced offer-coral">
                    <div class="offer-badge" style="background: linear-gradient(135deg, #f97316, #fb923c);">20% OFF</div>
                    <div class="offer-icon-wrap"><i class="bi bi-geo-alt-fill"></i></div>
                    <h4 style="color: #1e1b4b; font-family: 'Montserrat'; font-weight: 700; margin-bottom: 8px;">Destinations</h4>
                    <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1.25rem;">Exclusive discounts on handpicked destinations around the world</p>
                    <a href="destinations.php" class="btn-hero-primary" style="background: linear-gradient(135deg, #f97316, #fb923c); border: none; font-size: 0.85rem; padding: 10px 28px;">Explore Destinations</a>
                </div>
            </div>
            <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="200">
                <div class="offer-card-enhanced offer-purple">
                    <div class="offer-badge">30% OFF</div>
                    <div class="offer-icon-wrap"><i class="bi bi-building-fill"></i></div>
                    <h4 style="color: #1e1b4b; font-family: 'Montserrat'; font-weight: 700; margin-bottom: 8px;">Hotel Packages</h4>
                    <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1.25rem;">Save big on luxury accommodations across 120+ destinations worldwide</p>
                    <a href="hotels.php" class="btn-hero-primary" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa); border: none; font-size: 0.85rem; padding: 10px 28px;">Book Now</a>
                </div>
            </div>
            <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="300">
                <div class="offer-card-enhanced offer-cyan">
                    <div class="offer-badge" style="background: linear-gradient(135deg, #06b6d4, #22d3ee);">BUY 2 GET 1</div>
                    <div class="offer-icon-wrap"><i class="bi bi-compass-fill"></i></div>
                    <h4 style="color: #1e1b4b; font-family: 'Montserrat'; font-weight: 700; margin-bottom: 8px;">Tour Bundles</h4>
                    <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1.25rem;">Book 2 tours and get a third completely free — limited time offer</p>
                    <a href="tours.php" class="btn-hero-primary" style="background: linear-gradient(135deg, #06b6d4, #22d3ee); border: none; font-size: 0.85rem; padding: 10px 28px;">View Tours</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- WAVE DIVIDER -->
<div class="wave-divider">
    <svg viewBox="0 0 1200 80" preserveAspectRatio="none">
        <path d="M0,40 Q150,80 300,40 T600,40 T900,40 T1200,40 L1200,80 L0,80Z" fill="#faf5ff"/>
    </svg>
</div>

<!-- TESTIMONIALS — NEW -->
<section class="testimonial-section py-5" style="background: #faf5ff; position: relative;">
    <div class="container" style="position: relative; z-index: 1;">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-label">LOVE FROM TRAVELERS</span>
            <h2 class="section-title">What Our Travelers Say</h2>
            <p class="section-subtitle">Real stories from real adventurers who explored with us</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <div class="testimonial-avatar">S</div>
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"The Bali tour was absolutely breathtaking! Every detail was perfectly organized. From the rice terraces to the sunset at Uluwatu — pure magic."</p>
                    <div class="testimonial-name">Sarah Mitchell</div>
                    <div class="testimonial-trip">Bali Paradise · 7 Days</div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card">
                    <div class="testimonial-avatar" style="background: linear-gradient(135deg, #f97316, #fb923c);">M</div>
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"Paris exceeded all expectations! Our guide knew the most hidden spots. The rooftop dinner with the Eiffel Tower view was unforgettable."</p>
                    <div class="testimonial-name">Marco Rivera</div>
                    <div class="testimonial-trip">Paris Romance · 5 Days</div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-card">
                    <div class="testimonial-avatar" style="background: linear-gradient(135deg, #06b6d4, #22d3ee);">A</div>
                    <div class="testimonial-stars">★★★★★</div>
                    <p class="testimonial-text">"Tokyo was a dream! The blend of ancient temples and neon-lit streets was incredible. Already planning my return trip. Best travel agency ever!"</p>
                    <div class="testimonial-name">Aiko Tanaka</div>
                    <div class="testimonial-trip">Tokyo Lights · 4 Days</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- WHY CHOOSE US -->
<section class="why-us py-5">
    <div class="container">
        <div class="section-header text-center mb-5" data-aos="fade-up">
            <span class="section-label">WHY CHOOSE US</span>
            <h2 class="section-title">Why Trails & Tides</h2>
            <p class="section-subtitle">Trusted by 500+ travelers worldwide</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="why-card"><div class="why-icon"><i class="bi bi-award"></i></div><h5>Expert Guides</h5><p>Experienced travelers leading every expedition</p></div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="why-card"><div class="why-icon"><i class="bi bi-shield-check"></i></div><h5>Safe & Secure</h5><p>Your safety is our top priority always</p></div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="why-card"><div class="why-icon"><i class="bi bi-piggy-bank"></i></div><h5>Best Prices</h5><p>Competitive rates with exceptional value</p></div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="why-card"><div class="why-icon"><i class="bi bi-headset"></i></div><h5>24/7 Support</h5><p>Always here to help when you need us</p></div>
            </div>
        </div>
    </div>
</section>

<!-- CTA — ENHANCED -->
<section class="cta-enhanced py-5" style="padding: 80px 0 !important;">
    <div class="container text-center" style="position: relative; z-index: 1;" data-aos="fade-up">
        <h2 class="cta-title" style="font-family: 'Playfair Display'; font-size: 3rem; font-weight: 900; color: white; margin-bottom: 1rem;">Ready to Start Your Journey?</h2>
        <p style="font-size: 1.1rem; color: rgba(255,255,255,0.75); margin-bottom: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">Your next adventure is just a click away. Join 500+ happy travelers and explore the world with us.</p>
        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
            <a href="auth/register.php" class="btn-hero-primary" style="background: linear-gradient(135deg, #f97316, #fb923c); border: none; font-size: 1rem; padding: 14px 36px; box-shadow: 0 8px 24px rgba(249,115,22,0.3);">Get Started Free</a>
            <a href="tours.php" class="btn-hero-outline" style="font-size: 1rem; padding: 14px 36px;">Browse Tours</a>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>
