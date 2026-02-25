# 🌊 Trails & Tides

> A premium travel discovery and booking web application built with PHP, MySQL, Bootstrap 5, and vanilla JavaScript.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=flat&logo=javascript&logoColor=black)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## ✨ Features

- 🎥 **Full-bleed video carousel** hero with 6 destination clips and smooth crossfade transitions
- 🗺️ **6 Tour Packages** with live search, duration & price filtering
- 🏨 **Hotel search** with destination filter
- 🌍 **6 Destinations** with continent filtering
- 📓 **Travel Journal** — write, mood-tag, and delete diary entries
- 📊 **Personal Dashboard** — time-aware greeting, booking stats, top destinations
- 👤 **User Profile** — edit info, change password, quick navigation links
- 🔐 **Authentication** — register / login / logout with bcrypt password hashing
- 📱 **Fully responsive** — mobile-first with Bootstrap 5 grid

---

## 🖼️ Screenshots

| Page                  | Preview                                                        |
| --------------------- | -------------------------------------------------------------- |
| **Home — Video Hero** | Full-bleed ocean carousel with glassmorphism card              |
| **Dashboard**         | Time-aware greeting, floating stat cards, top destinations     |
| **Journal**           | Side-by-side write form + entries panel with mood pills        |
| **Profile**           | Dark gradient hero with stats bar and colour-coded quick links |
| **Hotels**            | Full-width filter bar + hotel cards with pricing               |

---

## 🏗️ Tech Stack

| Layer              | Technology                                           |
| ------------------ | ---------------------------------------------------- |
| Backend            | PHP 8.x                                              |
| Database           | MySQL 8.x (via MySQLi)                               |
| Frontend Framework | Bootstrap 5.3                                        |
| Icons              | Bootstrap Icons                                      |
| Fonts              | Google Fonts — Playfair Display, Poppins, Montserrat |
| Animations         | AOS (Animate On Scroll) 2.3.4                        |
| Scripting          | Vanilla JavaScript (ES6)                             |
| Local Server       | XAMPP (Apache + MySQL)                               |

---

## 📁 Project Structure

```
Trailsandtides/
│
├── index.php               # Homepage — video carousel, tours, destinations, CTAs
├── tours.php               # Tour packages with live filter
├── hotels.php              # Hotel listings with search/filter
├── destinations.php        # Destination cards with continent filter
├── dashboard.php           # User dashboard (protected)
├── journal.php             # Travel journal (protected)
├── profile.php             # User profile editing (protected)
├── book_tour.php           # Tour booking form (protected)
├── admin.php               # Admin panel (protected)
│
├── auth/
│   ├── login.php           # Login form & session creation
│   ├── register.php        # Registration with password hashing
│   └── logout.php          # Session destroy & redirect
│
├── includes/
│   ├── header.php          # Navbar, Bootstrap/AOS CDN, session check
│   ├── footer.php          # Footer with wave SVG + social links
│   └── db.php              # MySQL connection + conditional session_start()
│
├── assets/
│   ├── css/
│   │   └── style.css       # Global CSS — design tokens, components, animations
│   ├── js/
│   │   └── script.js       # Carousel engine, filters, navbar scroll, AOS
│   └── images/             # JPG images + MP4 video clips (local only)
│
├── data/
│   ├── trailsandtides.sql  # Full database dump with schema + seed data
│   └── database.sql        # Alternate schema reference
│
├── INSIGHTS.md             # Module & sub-module architecture breakdown
├── README.md               # This file
└── .gitignore              # Excludes MP4 videos and local scripts
```

---

## ⚙️ Local Setup

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any PHP 8 + MySQL stack)
- A modern browser
- Git

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/DSanmukha/Trailsandtides.git

# 2. Move to XAMPP's web root
# Windows: Move the folder to C:\xampp\htdocs\
# macOS:   Move to /Applications/XAMPP/htdocs/

# 3. Start Apache and MySQL in XAMPP Control Panel

# 4. Import the database
# Open http://localhost/phpmyadmin
# Create a database named: trailsandtides
# Import: data/trailsandtides.sql

# 5. Configure database credentials
# Edit includes/db.php and update:
#   $host, $user, $pass, $db

# 6. Open in browser
http://localhost/Trailsandtides/
```

### Seeded Demo Account

| Field    | Value                        |
| -------- | ---------------------------- |
| Email    | demo@demo.com                |
| Password | password (or check SQL dump) |

---

## 🔐 Security

- Passwords hashed with **bcrypt** (`password_hash` / `password_verify`)
- All user input sanitised with `real_escape_string()` before SQL
- All output escaped with `htmlspecialchars()`
- Every protected page checks `$_SESSION['user']` at the top
- Conditional `session_start()` prevents PHP session notices

---

## 📦 Modules

See **[INSIGHTS.md](INSIGHTS.md)** for a full module and sub-module breakdown including:

- 🔐 Authentication System
- 🗄️ Database & Session Core
- 🌐 Public Pages (Home, Tours, Hotels, Destinations)
- 📊 User Dashboard
- 📓 Travel Journal
- 👤 User Profile
- ⚙️ Admin Panel
- 🔧 Core Includes (Header, Footer, DB)
- 🎨 Asset Layer (CSS Design System, JS Carousel Engine)

---

## 🎨 Design Highlights

- **Deep gradient heroes** — `#0f0c29 → #302b63 → #24243e` across all pages
- **160px hero top padding** everywhere so content always clears the fixed navbar
- **Glassmorphism** panel on the carousel — `blur(6px)` keeps video visible behind text
- **Ocean blue-teal gradient** (`#0891b2 → #06b6d4`) for the Ocean hero slide text
- **Floating stat cards** on dashboard that lift from the hero on scroll
- **Equal-height feature cards** via CSS flex on `.why-card`

---

## 🗺️ Roadmap

- [ ] Payment gateway integration (Razorpay / Stripe)
- [ ] Email confirmations for bookings
- [ ] Real-time availability for tours
- [ ] User photo upload for profile avatar
- [ ] Admin dashboard with charts (Chart.js)
- [ ] PWA / offline support

---

## 🤝 Contributing

Pull requests are welcome! For major changes please open an issue first to discuss what you'd like to change.

---

## 📄 License

This project is licensed under the **MIT License**.

---

<div align="center">
  Made with ❤️ by <strong>D. Shanmukha</strong>
  <br>
  <a href="https://github.com/DSanmukha">@DSanmukha</a>
</div>
