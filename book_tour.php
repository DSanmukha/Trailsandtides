<?php
include 'includes/db.php';

if(!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit;
}

if(isset($_POST['book'])) {
    $user_id = $_SESSION['user']['id'];
    $tour_name = $conn->real_escape_string($_POST['tour_name']);
    $price = floatval($_POST['tour_price']);
    $guests = intval($_POST['guests']);
    $check_in = $conn->real_escape_string($_POST['check_in']);
    $notes = $conn->real_escape_string($_POST['notes'] ?? '');
    $total = $price * $guests;
    
    // Find tour_id if exists
    $tour_result = $conn->query("SELECT id FROM tours WHERE title='$tour_name' LIMIT 1");
    $tour_id = $tour_result->num_rows > 0 ? $tour_result->fetch_assoc()['id'] : null;
    
    $tour_id_sql = $tour_id ? $tour_id : 'NULL';
    $conn->query("INSERT INTO bookings (user_id, tour_id, hotel_name, check_in, guests, total_price, status) 
                   VALUES ($user_id, $tour_id_sql, '$tour_name', '$check_in', $guests, $total, 'confirmed')");
    
    header("Location: dashboard.php?booked=1&tour=" . urlencode($tour_name));
    exit;
}

header("Location: tours.php");
?>
