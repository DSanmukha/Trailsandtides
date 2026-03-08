<?php include '../includes/db.php';

if(isset($_SESSION['user'])) { header("Location: ../dashboard.php"); exit; }

$error = '';
if(isset($_POST['login'])){
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user'] = $user;
        header("Location: ../index.php");
        exit;
    } else {
        $error = 'Invalid email or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Trails & Tides</title>
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
            content: ''; position: absolute; top: -100px; right: -100px;
            width: 400px; height: 400px; border-radius: 50%;
            background: rgba(249,115,22,0.12); filter: blur(80px);
        }
        body::after {
            content: ''; position: absolute; bottom: -80px; left: -80px;
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
        .form-floating { margin-bottom: 1rem; position: relative; }
        .form-floating .form-control {
            background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
            color: white; border-radius: 12px; height: 56px;
        }
        .form-floating .form-control:focus {
            background: rgba(255,255,255,0.1); border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139,92,246,0.15); color: white;
        }
        .form-floating label { color: rgba(255,255,255,0.5); }
        .pwd-toggle {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: rgba(255,255,255,0.4);
            cursor: pointer; z-index: 5; padding: 4px; font-size: 1.1rem;
            transition: color 0.2s;
        }
        .pwd-toggle:hover { color: rgba(255,255,255,0.8); }
        .forgot-link {
            display: block; text-align: right; margin: -4px 0 16px;
            color: rgba(255,255,255,0.4); font-size: 0.8rem; text-decoration: none;
            transition: color 0.2s;
        }
        .forgot-link:hover { color: #c4b5fd; }
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
            <h2>Welcome Back</h2>
            <p>Sign in to your Trails & Tides account</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-danger"><i class="bi bi-exclamation-circle me-2"></i><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-floating">
                <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                <label for="email"><i class="bi bi-envelope me-2"></i>Email Address</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                <button type="button" class="pwd-toggle" id="pwdToggle" tabindex="-1"><i class="bi bi-eye-slash" id="pwdIcon"></i></button>
            </div>
            <a href="#" class="forgot-link" onclick="alert('Please contact admin@trailsandtides.com to reset your password.'); return false;">Forgot password?</a>
            <button type="submit" name="login" class="btn-auth">Sign In</button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Create one</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('pwdToggle').addEventListener('click', function() {
        const pwd = document.getElementById('password');
        const icon = document.getElementById('pwdIcon');
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            pwd.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    });
    </script>
</body>
</html>