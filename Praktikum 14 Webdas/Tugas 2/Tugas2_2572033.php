<!--2572033 Joseph Xavier Tan-->

<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "latihan_login";
$conn = new mysqli($host, $user, $pass, $db);

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

$error_login = "";
$error_reg = "";
$success_reg = "";
$show_register = false; 

if (isset($_GET['page']) && $_GET['page'] == 'register') {
    $show_register = true;
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cek = $conn->query("SELECT * FROM users WHERE email='$email' OR username='$username'");
    if ($cek->num_rows > 0) {
        $error_reg = "Email atau Username sudah terdaftar.";
        $show_register = true;
    } else {
        $conn->query("INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')");
        $success_reg = "Data sudah disimpan! Silakan login.";
        $show_register = false; 
    }
}

if (isset($_POST['login'])) {
    $email_username = $_POST['email_username'];
    $password_input = $_POST['password'];

    $cek = $conn->query("SELECT * FROM users WHERE email='$email_username' OR username='$email_username'");
    
    if ($cek->num_rows > 0) {
        $row = $cek->fetch_assoc();
        if (password_verify($password_input, $row['password'])) {
            $_SESSION['user_logged_in'] = $row['username'];
            header("Location: index.php");
            exit;
        } else {
            $error_login = "Password salah!";
        }
    } else {
        $error_login = "Username / Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Register - 2572033</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        .container-custom {
            margin: 50px auto;
            justify-content: center;
        }
        .form-box {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 400px;
            background-color: white;
        }
        .dashboard-box {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            padding: 30px;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #d1e7dd;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-light">

<?php if (isset($_SESSION['user_logged_in'])): ?>
    <div class="container d-flex justify-content-center">
        <div class="dashboard-box text-center">
            <h4 class="mb-4">Selamat datang, <strong><?= htmlspecialchars($_SESSION['user_logged_in']) ?></strong></h4>
            <a href="index.php?action=logout" class="btn btn-danger">Logout</a>
        </div>
    </div>
<?php else: ?>
    <div class="container container-custom d-flex">
        
        <?php if (!$show_register): ?>
            <div class="form-box d-flex flex-column">
                <h3 class="text-center mb-4">Login</h3>
                
                <?php if ($success_reg): ?>
                    <div class="alert alert-success py-2"><?= $success_reg ?></div>
                <?php endif; ?>

                <?php if ($error_login): ?>
                    <div class="alert alert-danger py-2"><?= $error_login ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php">
                    <fieldset class="d-flex flex-column justify-content-center">
                        <input type="text" name="email_username" placeholder="Email / Username" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" name="login" class="btn btn-success mb-2 w-100">Login</button>
                    </fieldset>
                </form>
                <p class="text-center mt-3">Belum punya akun? <a href="index.php?page=register">Register</a></p>
            </div>

        <?php else: ?>
            <div class="form-box d-flex flex-column">
                <h3 class="mb-4 text-center">Register</h3>
                
                <?php if ($error_reg): ?>
                    <div class="alert alert-danger py-2"><?= $error_reg ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=register">
                    <fieldset class="d-flex flex-column justify-content-center">
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required>
                        <button type="submit" name="register" class="btn btn-primary mb-2 w-100">Register</button>
                    </fieldset>
                </form>
                <p class="text-center mt-3">Sudah punya akun? <a href="index.php">Login</a></p>
            </div>
        <?php endif; ?>

    </div>
<?php endif; ?>

</body>
</html>