<?php include("includes/header.php"); ?>

<style>
.dest-hero {
    background: linear-gradient(135deg, rgba(30,27,75,0.85), rgba(76,29,149,0.75)), url('assets/images/beach.jpg') center/cover;
    padding: 160px 0 80px; text-align: center; margin-top: -76px;
}
.dest-hero h1 { font-family: 'Playfair Display'; color: white; font-size: 3rem; margin-bottom: 8px; }
.dest-hero p { color: rgba(255,255,255,0.65); font-size: 1.1rem; max-width: 500px; margin: 0 auto; }

.dest-filter-bar {
    background: rgba(255,255,255,0.85); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.1); border-radius: 20px;
    padding: 1.25rem 1.5rem; margin-top: -30px; position: relative; z-index: 2;
}
.dest-filter-bar .form-control, .dest-filter-bar .form-select {
    border: 1px solid rgba(139,92,246,0.15); border-radius: 12px;
}
.dest-filter-bar .form-control:focus, .dest-filter-bar .form-select:focus {
    border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139,92,246,0.1);
}

.dest-grid-card {
    background: rgba(255,255,255,0.85); backdrop-filter: blur(20px);
    border: 1px solid rgba(139,92,246,0.1); border-radius: 20px;
    overflow: hidden; transition: all 0.4s ease;
}
.dest-grid-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); border-color: rgba(139,92,246,0.25); }
.dest-grid-card img { width: 100%; height: 220px; object-fit: cover; }
.dest-grid-card .dest-card-body { padding: 1.5rem; }
.dest-grid-card h4 { color: #1e1b4b; font-weight: 700; font-size: 1.15rem; margin-bottom: 4px; }
.dest-grid-card .dest-country { color: #8b5cf6; font-size: 0.8rem; font-weight: 500; margin-bottom: 8px; }
.dest-grid-card p { color: #6b7280; font-size: 0.88rem; line-height: 1.5; }
.dest-grid-card .dest-card-footer {
    display: flex; justify-content: space-between; align-items: center;
    border-top: 1px solid rgba(139,92,246,0.08); padding-top: 12px; margin-top: 12px;
}
.dest-grid-card .dest-weather { color: #6b7280; font-size: 0.82rem; }
.dest-grid-card .dest-weather i { color: #f59e0b; }
.dest-grid-card .dest-rating { color: #f59e0b; font-size: 0.85rem; font-weight: 600; }
.dest-grid-card .dest-tours-count {
    background: rgba(139,92,246,0.1); color: #8b5cf6; padding: 4px 12px;
    border-radius: 20px; font-size: 0.75rem; font-weight: 600;
}
.btn-dest-explore {
    display: inline-block; background: linear-gradient(135deg, #8b5cf6, #c084fc);
    color: white; padding: 10px 24px; border-radius: 12px;
    text-decoration: none; font-weight: 600; font-size: 0.85rem;
    transition: all 0.3s ease; border: none;
}
.btn-dest-explore:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(139,92,246,0.3); color: white; }
</style>

<!-- Hero -->
<div class="dest-hero">
    <div class="container">
        <h1 data-aos="fade-up">Explore Destinations</h1>
        <p data-aos="fade-up" data-aos-delay="100">Discover the most breathtaking places on Earth</p>
    </div>
</div>

<!-- Filter Bar -->
<div class="container">
    <div class="dest-filter-bar" data-aos="fade-up">
        <div class="row g-3 align-items-center">
            <div class="col-md-5">
                <input type="text" class="form-control" placeholder="Search destinations..." id="destSearch">
            </div>
            <div class="col-md-3">
                <select class="form-select" id="destRegion">
                    <option value="">All Regions</option>
                    <option>Asia</option><option>Europe</option><option>Americas</option><option>Middle East</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="destSort">
                    <option value="">Sort By</option>
                    <option>Rating</option><option>Tours</option><option>Name</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn w-100" style="background: linear-gradient(135deg, #8b5cf6, #c084fc); color: white; border-radius: 12px; font-weight: 600; border: none; padding: 10px;" onclick="filterDests()"><i class="bi bi-search"></i></button>
            </div>
            <div class="col-md-1">
                <button class="btn w-100" style="border: 1px solid rgba(139,92,246,0.3); border-radius: 12px; color: #8b5cf6; font-weight: 600; padding: 10px;" onclick="document.getElementById('destSearch').value=''; document.getElementById('destRegion').value=''; document.getElementById('destSort').value=''; filterDests();"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
    </div>
</div>

<!-- Destinations Grid -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <?php
            $destinations = [
                ['name'=>'Bali','country'=>'Indonesia','desc'=>'Tropical beaches, ancient temples, rice terraces, and vibrant nightlife','img'=>'assets/images/bali.jpg','temp'=>'28°C','icon'=>'bi-sun','rating'=>4.8,'tours'=>12],
                ['name'=>'Paris','country'=>'France','desc'=>'City of love — iconic landmarks, world-class museums, and exquisite cuisine','img'=>'assets/images/paris.jpg','temp'=>'18°C','icon'=>'bi-cloud-sun','rating'=>4.9,'tours'=>8],
                ['name'=>'Tokyo','country'=>'Japan','desc'=>'Neon-lit streets, ancient temples, and the finest cuisine in the world','img'=>'assets/images/tokyo.jpg','temp'=>'20°C','icon'=>'bi-cloud','rating'=>4.7,'tours'=>6],
                ['name'=>'Maldives','country'=>'Maldives','desc'=>'Crystal clear waters, overwater bungalows, and pristine coral reefs','img'=>'assets/images/maldives.jpg','temp'=>'29°C','icon'=>'bi-sun','rating'=>5.0,'tours'=>4],
                ['name'=>'Swiss Alps','country'=>'Switzerland','desc'=>'Majestic mountain peaks, scenic trains, and charming alpine villages','img'=>'assets/images/swiss aples.jpg','temp'=>'12°C','icon'=>'bi-snow','rating'=>4.9,'tours'=>5],
                ['name'=>'New York','country'=>'USA','desc'=>'The city that never sleeps — iconic skyline, culture, and world-class food','img'=>'assets/images/newyork.jpg','temp'=>'15°C','icon'=>'bi-cloud-sun','rating'=>4.6,'tours'=>7],
                ['name'=>'Egypt','country'=>'Egypt','desc'=>'Ancient pyramids, the Sphinx, and cruises along the legendary Nile River','img'=>'https://images.unsplash.com/photo-1539768942893-daf53e736b68?w=600&h=400&fit=crop','temp'=>'35°C','icon'=>'bi-sun','rating'=>4.8,'tours'=>5],
                ['name'=>'Costa Rica','country'=>'Costa Rica','desc'=>'Lush rainforests, volcanic hot springs, and incredible biodiversity','img'=>'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=600&h=400&fit=crop','temp'=>'27°C','icon'=>'bi-cloud-sun','rating'=>4.7,'tours'=>4],
            ];
            foreach($destinations as $i => $d): ?>
            <div class="col-lg-3 col-md-6 dest-card-wrap" data-aos="zoom-in" data-aos-delay="<?= ($i % 4 + 1) * 100 ?>">
                <div class="dest-grid-card">
                    <img src="<?= $d['img'] ?>" alt="<?= $d['name'] ?>" onerror="this.src='https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600&h=400&fit=crop'">
                    <div class="dest-card-body">
                        <h4><?= $d['name'] ?></h4>
                        <div class="dest-country"><i class="bi bi-geo-alt me-1"></i><?= $d['country'] ?></div>
                        <p><?= $d['desc'] ?></p>
                        <div class="dest-card-footer">
                            <span class="dest-weather"><i class="<?= $d['icon'] ?> me-1"></i><?= $d['temp'] ?></span>
                            <span class="dest-rating"><i class="bi bi-star-fill me-1"></i><?= $d['rating'] ?></span>
                            <span class="dest-tours-count"><?= $d['tours'] ?> Tours</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section style="background: linear-gradient(135deg, #1e1b4b, #312e81, #4c1d95); padding: 60px 0;">
    <div class="container text-center" data-aos="fade-up">
        <h2 style="font-family: 'Playfair Display'; color: white; font-size: 2.5rem; margin-bottom: 12px;">Can't Find Your Destination?</h2>
        <p style="color: rgba(255,255,255,0.6); margin-bottom: 2rem;">Tell us where you want to go and we'll craft a custom trip for you</p>
        <a href="tours.php" class="btn-dest-explore" style="padding: 14px 36px; font-size: 1rem;">Browse Tours Instead</a>
    </div>
</section>

<script>
function filterDests() {
    const query  = document.getElementById('destSearch').value.toLowerCase();
    const region = document.getElementById('destRegion').value.toLowerCase();

    document.querySelectorAll('.dest-card-wrap').forEach(card => {
        const text = card.innerText.toLowerCase();
        let show = true;
        if (query && !text.includes(query)) show = false;
        if (region && !text.includes(region)) show = false;
        card.style.display = show ? '' : 'none';
    });
}

document.getElementById('destSearch').addEventListener('keyup', filterDests);
document.getElementById('destRegion').addEventListener('change', filterDests);
document.getElementById('destSort').addEventListener('change', filterDests);
document.getElementById('destSearch').addEventListener('keydown', e => { if(e.key === 'Enter') filterDests(); });
</script>

<?php include("includes/footer.php"); ?>
