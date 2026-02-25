// Initialize AOS
AOS.init({
    duration: 800,
    once: true,
    offset: 80,
    easing: 'ease-out-cubic'
});

// ========================
// NAVBAR TRANSPARENT → SCROLLED
// ========================
const navbar = document.getElementById('mainNavbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 80) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// ========================
// SMOOTH SCROLL
// ========================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// ========================
// VIDEO CAROUSEL ENGINE
// ========================
const heroVideos = document.querySelectorAll('.hero-video');
const dotsContainer = document.getElementById('carouselDots');
const heroDestEl = document.getElementById('heroDestination');
const heroLabelEl = document.getElementById('heroLabel');

const destinationNames = {
    'Ocean': 'the Ocean',
    'Swiss Alps': 'the Alps',
    'Paris': 'Paris',
    'Bali': 'Bali',
    'Tokyo': 'Tokyo',
    'Skiing': 'the Slopes'
};

const labelNames = {
    'Ocean': 'Ocean Trails',
    'Swiss Alps': 'Alpine Trails',
    'Paris': 'City Trails',
    'Bali': 'Island Trails',
    'Tokyo': 'Urban Trails',
    'Skiing': 'Snow Trails'
};

const destinationColors = {
    'Ocean': 'linear-gradient(135deg, #0891b2, #06b6d4)',
    'Swiss Alps': 'linear-gradient(135deg, #e2e8f0, #94a3b8)',
    'Paris': 'linear-gradient(135deg, #fbbf24, #f59e0b)',
    'Bali': 'linear-gradient(135deg, #047857, #065f46)',
    'Tokyo': 'linear-gradient(135deg, #f472b6, #ec4899)',
    'Skiing': 'linear-gradient(135deg, #7dd3fc, #38bdf8)'
};

let currentVideoIndex = 0;
let carouselInterval = null;

if (heroVideos.length > 0 && dotsContainer) {
    // Create dots
    heroVideos.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
        dot.setAttribute('aria-label', `Video ${i + 1}`);
        dot.addEventListener('click', () => goToVideo(i));
        dotsContainer.appendChild(dot);
    });

    // Preload all videos
    heroVideos.forEach(video => {
        video.preload = 'auto';
        video.load();
    });

    // Play first video
    heroVideos[0].play().catch(() => {});

    // Apply color & label for the first slide immediately on load
    const firstLabel = heroVideos[0].dataset.label || '';
    if (heroDestEl && destinationColors[firstLabel]) {
        heroDestEl.style.background = destinationColors[firstLabel];
        heroDestEl.style.webkitBackgroundClip = 'text';
        heroDestEl.style.webkitTextFillColor = 'transparent';
        heroDestEl.style.backgroundClip = 'text';
    }
    if (heroLabelEl && labelNames[firstLabel]) {
        heroLabelEl.textContent = labelNames[firstLabel];
    }

    // Start carousel (8 seconds per video)
    startCarousel();
}

function startCarousel() {
    carouselInterval = setInterval(() => {
        const next = (currentVideoIndex + 1) % heroVideos.length;
        goToVideo(next);
    }, 8000);
}

function goToVideo(index) {
    if (index === currentVideoIndex) return;
    
    // Clear timer and restart
    clearInterval(carouselInterval);

    // Fade out current
    const current = heroVideos[currentVideoIndex];
    current.classList.remove('active-video');

    // Fade in next
    const next = heroVideos[index];
    next.currentTime = 0;
    next.play().catch(() => {});
    next.classList.add('active-video');

    // Pause old after transition
    setTimeout(() => {
        current.pause();
    }, 1300);

    // Update dots
    const dots = dotsContainer.querySelectorAll('.carousel-dot');
    dots.forEach(d => d.classList.remove('active'));
    dots[index].classList.add('active');

    // Update hero text with animation
    const label = next.dataset.label || '';
    if (heroDestEl && destinationNames[label]) {
        heroDestEl.style.opacity = '0';
        heroDestEl.style.transform = 'translateY(10px)';
        setTimeout(() => {
            heroDestEl.textContent = destinationNames[label];
            if (destinationColors[label]) {
                heroDestEl.style.background = destinationColors[label];
                heroDestEl.style.webkitBackgroundClip = 'text';
                heroDestEl.style.webkitTextFillColor = 'transparent';
                heroDestEl.style.backgroundClip = 'text';
            }
            heroDestEl.style.transition = 'all 0.5s ease';
            heroDestEl.style.opacity = '1';
            heroDestEl.style.transform = 'translateY(0)';
        }, 300);
    }

    if (heroLabelEl && labelNames[label]) {
        heroLabelEl.textContent = labelNames[label];
    }

    currentVideoIndex = index;
    startCarousel();
}

// ========================
// VIDEO FALLBACK
// ========================
function setupVideoFallback(videoId, fallbackId) {
    const video = document.getElementById(videoId);
    const fallback = document.getElementById(fallbackId);
    
    if (!video || !fallback) return;
    
    video.addEventListener('error', () => {
        video.style.display = 'none';
        fallback.style.display = 'block';
    });
    
    const source = video.querySelector('source');
    if (source) {
        source.addEventListener('error', () => {
            video.style.display = 'none';
            fallback.style.display = 'block';
        });
    }
    
    setTimeout(() => {
        if (video.readyState < 3) {
            video.style.display = 'none';
            fallback.style.display = 'block';
        }
    }, 5000);
}

// Dashboard hero fallback
setupVideoFallback('heroVideo', 'heroFallback');

// Homepage: if no carousel videos load, show fallback
setTimeout(() => {
    if (heroVideos.length > 0) {
        const firstVideo = heroVideos[0];
        if (firstVideo.readyState < 2) {
            const fb = document.getElementById('homeHeroFallback');
            if (fb) {
                fb.style.display = 'block';
                heroVideos.forEach(v => v.style.display = 'none');
            }
        }
    }
}, 5000);

// ========================
// IMAGE VALIDATION
// ========================
function checkDestinationImages() {
    document.querySelectorAll('.dest-check-img').forEach(img => {
        const fallbackUrl = img.dataset.fallback;
        if (fallbackUrl) {
            img.addEventListener('error', function() {
                if (this.src !== fallbackUrl) {
                    this.src = fallbackUrl;
                }
            });
        }
    });
}

window.addEventListener('DOMContentLoaded', checkDestinationImages);

// ========================
// COUNTER ANIMATION
// ========================
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    const interval = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target;
            clearInterval(interval);
        } else {
            element.textContent = Math.floor(start);
        }
    }, 16);
}

// ========================
// SEARCH TOURS
// ========================
const searchInput = document.getElementById('searchInput');
if (searchInput) searchInput.addEventListener('keyup', filterTours);

const filterDuration = document.getElementById('filterDuration');
const filterPrice = document.getElementById('filterPrice');
if (filterDuration) filterDuration.addEventListener('change', filterTours);
if (filterPrice) filterPrice.addEventListener('change', filterTours);

function filterTours() {
    const searchTerm = searchInput?.value.toLowerCase() || '';
    const duration = filterDuration?.value || '';
    const price = filterPrice?.value || '';
    
    document.querySelectorAll('[data-tour]').forEach(card => {
        let match = true;
        if (searchTerm && !card.textContent.toLowerCase().includes(searchTerm)) match = false;
        if (duration && card.dataset.duration !== duration) match = false;
        if (price && parseInt(card.dataset.price) > parseInt(price)) match = false;
        card.style.display = match ? 'block' : 'none';
    });
}

// ========================
// LAZY LOAD
// ========================
const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        }
    });
}, { rootMargin: '100px' });

document.querySelectorAll('img[data-src]').forEach(img => imageObserver.observe(img));

// ========================
// ACTIVE NAV LINK
// ========================
window.addEventListener('load', () => {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    document.querySelectorAll('.nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes(currentPage)) {
            link.classList.add('active');
        }
    });
});

// ========================
// SCROLL TO TOP
// ========================
const scrollBtn = document.createElement('button');
scrollBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
scrollBtn.className = 'scroll-to-top';
scrollBtn.setAttribute('aria-label', 'Scroll to top');
document.body.appendChild(scrollBtn);

scrollBtn.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

window.addEventListener('scroll', () => {
    scrollBtn.classList.toggle('show', window.scrollY > 400);
});

// ========================
// STAGGER ANIMATION
// ========================
const staggerObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry, index) => {
        if (entry.isIntersecting) {
            entry.target.style.animationDelay = `${index * 0.1}s`;
            entry.target.classList.add('stagger-item');
            staggerObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.glass-card, .tour-card, .offer-card, .offer-card-enhanced, .why-card, .package-card, .testimonial-card').forEach(el => {
    staggerObserver.observe(el);
});

// ========================
// STAT COUNTER ANIMATION
// ========================
const statObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const statNumbers = entry.target.querySelectorAll('.stat-number');
            statNumbers.forEach(el => {
                const target = parseInt(el.dataset.count) || 0;
                const suffix = el.dataset.suffix || '+';
                let current = 0;
                const increment = target / 60;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        el.textContent = target + suffix;
                        clearInterval(timer);
                    } else {
                        el.textContent = Math.floor(current) + suffix;
                    }
                }, 20);
            });
            statObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.3 });

const statsBar = document.querySelector('.stats-bar');
if (statsBar) statObserver.observe(statsBar);