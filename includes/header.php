<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trails & Tides - Explore Your Journey</title>
    <meta name="description" content="Discover breathtaking destinations and create unforgettable journeys with Trails & Tides.">
    <script>/* Tailwind removed — conflicted with Bootstrap */</script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800;900&family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-nav px-4" id="mainNavbar">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold brand-logo" href="index.php">
            <i class="bi bi-water"></i> Trails & Tides
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto gap-1">
<?php if(isset($_SESSION['user'])): ?>
                <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="tours.php">Tours</a></li>
                <li class="nav-item"><a class="nav-link" href="journal.php">Journal</a></li>
                <li class="nav-item"><a class="nav-link" href="hotels.php">Hotels</a></li>
                <li class="nav-item"><a class="nav-link" href="destinations.php">Destinations</a></li>
<?php if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
<?php endif; ?>
                <li class="nav-item"><a class="nav-link" href="profile.php"><i class="bi bi-person-circle"></i> Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="auth/logout.php">Logout</a></li>
<?php else: ?>
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="tours.php">Tours</a></li>
                <li class="nav-item"><a class="nav-link" href="destinations.php">Destinations</a></li>
                <li class="nav-item"><a class="nav-link" href="hotels.php">Hotels</a></li>
                <li class="nav-item ms-2"><a class="nav-link px-3 py-1" href="auth/login.php" style="border: 1px solid rgba(196,181,253,0.4); border-radius: 10px;"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a></li>
                <li class="nav-item ms-1"><a class="nav-link btn-signup px-3 py-1" href="auth/register.php" style="background: linear-gradient(135deg, #8b5cf6, #c084fc); border-radius: 10px; color: white !important;">Register</a></li>
<?php endif; ?>
            </ul>
        </div>
    </div>
</nav>