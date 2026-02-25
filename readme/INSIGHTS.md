# 🔍 Trails & Tides — Project Insights

> A deep-dive into the architecture, modules, and sub-modules of the Trails & Tides travel web application.

---

## 📐 System Architecture Overview

```
Trails & Tides
│
├── 🌐 Public Layer         (index.php, tours.php, destinations.php, hotels.php)
├── 🔐 Auth Layer           (auth/login.php, auth/register.php, auth/logout.php)
├── 👤 User Layer           (dashboard.php, journal.php, profile.php)
├── ⚙️  Admin Layer          (admin.php)
├── 🔧 Core Layer           (includes/header.php, includes/footer.php, includes/db.php)
└── 🎨 Asset Layer          (assets/css/style.css, assets/js/script.js, assets/images/)
```

---

## 📦 Module Breakdown

---

### Module 1 — Authentication System

> **Location:** `auth/`

| Sub-Module   | File                | Responsibility                                                  |
| ------------ | ------------------- | --------------------------------------------------------------- |
| Registration | `auth/register.php` | New user sign-up with password hashing (`password_hash`)        |
| Login        | `auth/login.php`    | Session creation, credential verification via `password_verify` |
| Logout       | `auth/logout.php`   | Destroys session and redirects to login                         |

**Key Behaviours:**

- Passwords are stored using **bcrypt** (PHP `PASSWORD_DEFAULT`)
- Sessions are started conditionally in `includes/db.php` to avoid duplicate `session_start()` notices
- Protected pages redirect to `auth/login.php` if `$_SESSION['user']` is not set

---

### Module 2 — Database & Session Core

> **Location:** `includes/db.php`

| Sub-Module          | Responsibility                                                            |
| ------------------- | ------------------------------------------------------------------------- |
| DB Connection       | Opens a MySQLi connection to `localhost` / `trailsandtides` DB            |
| Session Guard       | Calls `session_start()` only when `session_status() === PHP_SESSION_NONE` |
| Global Availability | Included at the top of every protected page                               |

**Tables Used:**

```
users            — user accounts (id, name, email, password, phone, bio, created_at)
bookings         — tour reservations (user_id, hotel_name, check_in, guests, total_price, status)
journal_entries  — travel diary (user_id, title, mood, location, content, created_at)
```

---

### Module 3 — Public Pages

> **Location:** Root directory

#### 3.1 Homepage (`index.php`)

| Sub-Module              | Description                                                                                       |
| ----------------------- | ------------------------------------------------------------------------------------------------- |
| Video Carousel Hero     | 6-slide full-bleed video background with JS-driven crossfade, per-slide destination text & colour |
| Destination Text Engine | `script.js` maps each video's `data-label` to a name, label pill, and gradient colour             |
| Stats Counter Bar       | Animated count-up for Travelers, Destinations, Packages, Satisfaction %                           |
| Featured Tours          | 3 highlighted tour cards pulled from `tours.php` data array                                       |
| Top Destinations        | 4 cards with hover-reveal overlays linked to `destinations.php`                                   |
| Special Offers          | 3 offer cards (Flight Deals, Hotel Packages, Tour Bundles)                                        |
| Why Choose Us           | 4 equal-height feature cards (Expert Guides, Safe & Secure, Best Prices, 24/7 Support)            |
| Testimonials            | 3 traveller review cards with avatar initials                                                     |
| CTA Strip               | Final call-to-action linking to registration and tours                                            |

#### 3.2 Tours Page (`tours.php`)

| Sub-Module      | Description                                                                              |
| --------------- | ---------------------------------------------------------------------------------------- |
| Hero Banner     | Gradient overlay hero with `160px` top padding for navbar clearance                      |
| Tour Data Array | 6 PHP array-defined packages (Bali, Swiss Alps, Tokyo, Maldives, Iceland, Egypt)         |
| Live Filter     | JS filters cards by name/destination, duration, and price range                          |
| Tour Cards      | Each card shows image, rating, inclusions, original + discounted price, group type badge |
| Book CTA        | Links each tour to `book_tour.php` with params                                           |
| FAQ Accordion   | Bootstrap collapse accordion for common questions                                        |

#### 3.3 Hotels Page (`hotels.php`)

| Sub-Module  | Description                                                                                            |
| ----------- | ------------------------------------------------------------------------------------------------------ |
| Hero Banner | Full-width gradient hero                                                                               |
| Filter Bar  | Search input + destination dropdown + icon-only search/clear buttons spanning full 12 columns          |
| Hotel Cards | Hardcoded 3-card grid (Bali Beach Resort, Le Grand Paris, Tokyo Sky Hotel) with images, ratings, price |
| Live Filter | JS `filterHotels()` matches search text + selected destination                                         |
| Book Button | Triggers booking flow                                                                                  |

#### 3.4 Destinations Page (`destinations.php`)

| Sub-Module        | Description                                                                                             |
| ----------------- | ------------------------------------------------------------------------------------------------------- |
| Hero Banner       | Gradient hero with beach background image                                                               |
| Destination Cards | PHP array of 6 destinations (Bali, Paris, Tokyo, Swiss Alps, Maldives, New York) rendered as grid cards |
| Card Data         | Each entry has: name, country, description, image, temperature, icon, rating, tour count                |
| Live Filter       | JS filters by continent badge chips                                                                     |

---

### Module 4 — User Dashboard

> **Location:** `dashboard.php`

| Sub-Module             | Description                                                                                           |
| ---------------------- | ----------------------------------------------------------------------------------------------------- |
| Auth Guard             | Redirects to login if not authenticated                                                               |
| Time-Aware Hero        | Gradient hero shows "Good Morning/Afternoon/Evening, [FirstName]!" based on server hour               |
| Explore Tours CTA      | Glass button in hero top-right linking to `tours.php`                                                 |
| Floating Stat Cards    | 3 cards (Total Bookings, Journal Entries, Destinations) that lift from the hero with hover animations |
| Recent Bookings        | Queries `bookings` table for latest 5 rows; shows status badge (confirmed/pending)                    |
| Quick Actions Grid     | 3-column icon cards → Tours, Journal, Hotels                                                          |
| Top Destinations Panel | 3 curated destination rows with category badges (Tropical, Culture, Urban)                            |
| Profile Summary Card   | Dark card with avatar initial + name/email + "Edit Profile" link                                      |

---

### Module 5 — Travel Journal

> **Location:** `journal.php`

| Sub-Module          | Description                                                                                                       |
| ------------------- | ----------------------------------------------------------------------------------------------------------------- |
| Auth Guard          | Redirect if not logged in                                                                                         |
| Save Entry          | `POST` handler writes to `journal_entries` with mood, location, title, content                                    |
| Delete Entry        | `POST` with `delete_id` removes row via prepared logic                                                            |
| Hero Banner         | Gradient wave hero                                                                                                |
| New Entry Form      | Styled card with title input, mood pills (Happy/Excited/Peaceful/Adventurous/Nostalgic), location input, textarea |
| Mood Pills          | Toggle-active gradient pill buttons; hidden input captures selected mood                                          |
| Side-by-Side Layout | Form (col-lg-5) + Entries panel (col-lg-7) sitting side by side                                                   |
| Entry Cards         | Each past entry: title, mood badge, location, date, content preview, delete icon                                  |
| Empty State         | Illustrated placeholder when no entries exist                                                                     |

---

### Module 6 — User Profile

> **Location:** `profile.php`

| Sub-Module           | Description                                                                             |
| -------------------- | --------------------------------------------------------------------------------------- |
| Auth Guard           | Redirect if not logged in                                                               |
| Profile Update       | `POST` → updates `name`, `phone`, `bio` in `users` table; refreshes `$_SESSION['user']` |
| Password Change      | Verifies current password → hashes new → updates DB                                     |
| Dark Hero            | Three-stop gradient (`#0f0c29 → #302b63 → #24243e`) with ambient glow blobs             |
| Avatar Circle        | Gradient purple circle showing first letter of name                                     |
| Stats Bar            | Bookings / Journal Entries / Destinations (3 cols with dividers)                        |
| Personal Info Card   | 4-field form (Full Name, Email disabled, Phone, Member Since disabled, Bio)             |
| Change Password Card | 3-field password form with outline button                                               |
| Quick Links Card     | 4 colour-coded icon-tile links → Dashboard, Journal, Tours, Hotels                      |

---

### Module 7 — Admin Panel

> **Location:** `admin.php`

| Sub-Module       | Description                   |
| ---------------- | ----------------------------- |
| Session Check    | Verifies admin access         |
| User Management  | Lists all registered users    |
| Booking Overview | All bookings across all users |
| Stats            | Platform-wide counts          |

---

### Module 8 — Core Includes

> **Location:** `includes/`

#### 8.1 Header (`includes/header.php`)

| Sub-Module            | Description                                                                                        |
| --------------------- | -------------------------------------------------------------------------------------------------- |
| `<head>` Setup        | Meta tags, Bootstrap 5, Bootstrap Icons, Google Fonts (Playfair Display, Poppins, Montserrat), AOS |
| Transparent Navbar    | `.glass-nav` — transparent at top, blurred dark on scroll via JS scroll listener                   |
| Active Link Detection | PHP `basename()` check highlights the current page nav link                                        |
| Responsive Toggle     | Bootstrap hamburger for mobile                                                                     |
| Auth-Aware Links      | Shows Dashboard/Journal/Profile/Logout when session active; else Login/Register                    |

#### 8.2 Footer (`includes/footer.php`)

| Sub-Module    | Description                                           |
| ------------- | ----------------------------------------------------- |
| Wave SVG      | Seamless SVG wave transition from page body to footer |
| Brand Column  | Logo, tagline, social icon links                      |
| Quick Links   | Main page links                                       |
| Services      | Feature list                                          |
| Contact       | Address, email, phone                                 |
| Copyright Bar | Year + brand name                                     |

---

### Module 9 — Asset Layer

#### 9.1 Global CSS (`assets/css/style.css`)

| Sub-Module              | Description                                                                                        |
| ----------------------- | -------------------------------------------------------------------------------------------------- |
| CSS Custom Properties   | 30+ design tokens (colours, shadows, radii, gradients) in `:root`                                  |
| Glassmorphism Utilities | `.glass-card`, `.glass-card-static`, `.glass-dark-overlay`                                         |
| Navbar Styles           | Transparent → scrolled transition, active link underline                                           |
| Video Hero              | Full-bleed `video-hero-home`, crossfade transitions, overlay gradient                              |
| Hero Glass Panel        | `backdrop-filter: blur(6px)` — lightly frosted, video clearly visible behind text                  |
| Hero Text               | `.highlight` gradient: `#0891b2 → #06b6d4` (ocean blue-teal) matching Ocean slide                  |
| Section Components      | `.tour-card`, `.destination-card-modern`, `.why-card`, `.testimonial-card`, `.offer-card-enhanced` |
| Why-Cards Equal Height  | `height:100%`, `min-height:200px`, `display:flex`, `flex-direction:column`                         |
| Footer Styles           | Wave, social icons, link hover transitions                                                         |
| Responsive Breakpoints  | Mobile < 768px, Tablet 768–1199px, Desktop 1200px+                                                 |

#### 9.2 JavaScript (`assets/js/script.js`)

| Sub-Module            | Description                                                                              |
| --------------------- | ---------------------------------------------------------------------------------------- |
| AOS Init              | `AOS.init({ duration:800, once:true, easing:'ease-out-cubic' })`                         |
| Navbar Scroll         | Adds `.scrolled` class after 80px scroll                                                 |
| Video Carousel        | Crossfades between 6 videos on 8-second intervals; handles dot creation/active state     |
| Slide Colour Engine   | `destinationColors` map applies gradient text to `#heroDestination` per slide            |
| First-Load Colour Fix | Immediately applies slide-0 colour on page load (avoids pink/coral flash of CSS default) |
| `goToVideo(index)`    | Orchestrates fade-out/fade-in, dot update, text+colour animation                         |
| Smooth Scroll         | `href^="#"` anchor smooth behaviour                                                      |
| Hotel Filter          | `filterHotels()` matches search + dropdown against `.hotel-card` elements                |
| Tour Filter           | `filterTours()` matches search, duration, and price range against `.tour-card` elements  |
| Destination Filter    | Continent chip filter on destinations page                                               |

---

## 🎨 Design System

### Colour Palette

| Token         | Hex       | Usage                              |
| ------------- | --------- | ---------------------------------- |
| `--primary`   | `#8b5cf6` | Buttons, icons, borders            |
| `--secondary` | `#c084fc` | Gradients, accents                 |
| `--accent`    | `#f97316` | CTAs, orange highlights            |
| `--teal`      | `#06b6d4` | Ocean text, Destinations stat card |
| `--dark`      | `#1e1b4b` | Headings, deep backgrounds         |
| `--darker`    | `#0f0a2e` | Hero gradients, footer             |

### Typography

| Font             | Usage                             |
| ---------------- | --------------------------------- |
| Playfair Display | Page `h1`/`h2` headings           |
| Poppins          | Body copy, form inputs, buttons   |
| Montserrat       | Stat numbers, card titles, badges |

### Spacing System

| Rule               | Value                                   |
| ------------------ | --------------------------------------- |
| Hero padding-top   | `160px` (clears fixed 76px navbar)      |
| Hero margin-top    | `-76px` (extends behind navbar)         |
| Section padding    | `py-5` (3rem top/bottom)                |
| Card border-radius | `16px` (md) / `20px` (lg) / `28px` (xl) |

---

## 🔗 Page Flow Diagram

```
index.php
    ├── tours.php → book_tour.php
    ├── hotels.php
    ├── destinations.php
    ├── auth/register.php ──┐
    └── auth/login.php ─────┘
                            │
                    [Session Created]
                            │
              ┌─────────────┼─────────────┐
          dashboard.php  journal.php  profile.php
              │
         book_tour.php (via tour cards)
```

---

## 🛡️ Security Practices

| Practice         | Implementation                                                   |
| ---------------- | ---------------------------------------------------------------- |
| Password Storage | `password_hash()` with `PASSWORD_DEFAULT` (bcrypt)               |
| Password Verify  | `password_verify()` — never plain-text comparison                |
| SQL Injection    | `$conn->real_escape_string()` on all user inputs                 |
| XSS Protection   | `htmlspecialchars()` on all output                               |
| Session Security | Conditional `session_start()` prevents duplicate-session notices |
| Auth Guards      | Every protected page checks `$_SESSION['user']` at the top       |

---

_Last updated: February 2026_
