<?php include '../includes/db.php';

if(isset($_SESSION['user'])) { header("Location: ../dashboard.php"); exit; }

$error = '';
$success = '';
if(isset($_POST['register'])){
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    
    if($password !== $confirm){
        $error = 'Passwords do not match';
    } elseif(strlen($password) < 6){
        $error = 'Password must be at least 6 characters';
    } else {
        $check = $conn->query("SELECT id FROM users WHERE email='$email'");
        if($check->num_rows > 0){
            $error = 'Email already registered';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $conn->query("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed')");
            $success = 'Account created! You can now login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — Trails & Tides</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4c1d95 70%, #7c3aed 100%);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: absolute; top: -100px; left: -100px;
            width: 400px; height: 400px; border-radius: 50%;
            background: rgba(6,182,212,0.1); filter: blur(80px);
        }
        body::after {
            content: ''; position: absolute; bottom: -80px; right: -80px;
            width: 300px; height: 300px; border-radius: 50%;
            background: rgba(139,92,246,0.2); filter: blur(60px);
        }
        .auth-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px; padding: 3rem; width: 100%; max-width: 440px;
            position: relative; z-index: 1;
        }
        .auth-logo { text-align: center; margin-bottom: 2rem; }
        .auth-logo i { font-size: 2rem; color: #c4b5fd; }
        .auth-logo h2 { font-family: 'Playfair Display'; color: white; font-size: 1.75rem; margin: 8px 0 4px; }
        .auth-logo p { color: rgba(255,255,255,0.5); font-size: 0.85rem; }
        .form-floating { margin-bottom: 1rem; }
        .form-floating .form-control {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            color: white; border-radius: 12px; height: 56px;
        }
        .form-floating .form-control:focus {
            background: rgba(255,255,255,0.1); border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139,92,246,0.15); color: white;
        }
        .form-floating label { color: rgba(255,255,255,0.5); }
        .btn-auth {
            width: 100%; padding: 14px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, #8b5cf6, #c084fc);
            color: white; font-weight: 600; font-size: 1rem;
            cursor: pointer; transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(139,92,246,0.3);
        }
        .btn-auth:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(139,92,246,0.4); }
        .auth-footer { text-align: center; margin-top: 1.5rem; }
        .auth-footer a { color: #c4b5fd; text-decoration: none; font-weight: 500; }
        .auth-footer a:hover { color: white; }
        .auth-footer p { color: rgba(255,255,255,0.4); font-size: 0.85rem; margin: 0; }
        .alert-danger {
            background: rgba(220,38,38,0.15); border: 1px solid rgba(220,38,38,0.3);
            color: #fca5a5; border-radius: 12px; font-size: 0.85rem; padding: 12px 16px;
        }
        .alert-success {
            background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.3);
            color: #86efac; border-radius: 12px; font-size: 0.85rem; padding: 12px 16px;
        }
        .auth-home {
            position: absolute; top: 24px; left: 24px; color: rgba(255,255,255,0.5);
            text-decoration: none; font-size: 0.85rem; z-index: 2;
        }
        .auth-home:hover { color: white; }
    </style>
</head>
<body>
    <a href="../index.php" class="auth-home"><i class="bi bi-arrow-left"></i> Back to Home</a>

    <div class="auth-card">
        <div class="auth-logo">
            <i class="bi bi-water"></i>
            <h2>Create Account</h2>
            <p>Join the Trails & Tides community</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div>
        <?php endif; ?>
        <?php if($success): ?>
            <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i><?= $success ?> <a href="login.php" style="color: #86efac;">Login now</a></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-floating">
                <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
                <label for="name"><i class="bi bi-person me-2"></i>Full Name</label>
            </div>
            <div class="form-floating">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required minlength="6">
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
            </div>
            <div class="form-floating">
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm" required>
                <label for="confirm_password"><i class="bi bi-lock me-2"></i>Confirm Password</label>
            </div>
            <button type="submit" name="register" class="btn-auth">Create Account</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>