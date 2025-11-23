<?php
session_start();

if (isset($_POST['login'])) {
    // Koneksi ke database
    $koneksi = mysqli_connect("localhost", "root", "", "resto");

    // Cek apakah koneksi berhasil
    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Ambil data dari form
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Query untuk cek email
    $query = "SELECT * FROM users WHERE email='$email'";
    $hasil = mysqli_query($koneksi, $query);
    
    // Cek jika ada data pengguna
    if (mysqli_num_rows($hasil) > 0) {
        $data = mysqli_fetch_assoc($hasil);
        
        // Periksa password
        if ($password == $data['password']) {  // Jika menggunakan hashing, ganti dengan password_verify()
            $_SESSION['email'] = $email;
            header("Location: Tampilan.php");
            exit();
        } else {
            $error = "Email atau password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ocean Auth | Login & Signup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="https://cdn-icons-png.freepik.com/256/905/905594.png?ga=GA1.1.380298460.1745812613&semt=ais_hybrid">

    <style>
        :root {
            --deep-blue: #005f73;
            --ocean-blue: #0a9396;
            --light-blue: #94d2bd;
            --seafoam: #e9f5f5;
            --sand: #e9c46a;
            --coral: #ee9b00;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--seafoam);
            background-image: url('https://images.unsplash.com/photo-1505118380757-91f5f5632de0?q=80&w=1000');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            display: flex;
            max-width: 1200px;
            width: 90%;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            overflow: hidden;
        }

        .welcome-section {
            flex: 1;
            background: linear-gradient(135deg, rgba(0, 95, 115, 0.9), rgba(10, 147, 150, 0.9));
            color: var(--white);
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .welcome-section p {
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .welcome-section img {
            width: 200px;
            margin-bottom: 30px;
        }

        .form-section {
            flex: 1;
            background-color: var(--white);
            padding: 50px;
        }

        .form-container {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .form-header h2 {
            color: var(--deep-blue);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #666;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--deep-blue);
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus {
            border-color: var(--ocean-blue);
            outline: none;
            box-shadow: 0 0 0 3px rgba(10, 147, 150, 0.2);
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 42px;
            color: var(--ocean-blue);
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
        }

        .forgot-password a {
            color: var(--ocean-blue);
            text-decoration: none;
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: var(--ocean-blue);
            color: var(--white);
            margin-bottom: 20px;
        }

        .btn-primary:hover {
            background-color: var(--deep-blue);
        }

        .btn-secondary {
            background-color: transparent;
            border: 2px solid var(--ocean-blue);
            color: var(--ocean-blue);
        }

        .btn-secondary:hover {
            background-color: var(--seafoam);
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .social-login p {
            margin-bottom: 15px;
            color: #666;
            position: relative;
        }

        .social-login p::before,
        .social-login p::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background-color: #ddd;
        }

        .social-login p::before {
            left: 0;
        }

        .social-login p::after {
            right: 0;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--deep-blue);
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-icon:hover {
            background-color: var(--ocean-blue);
            color: var(--white);
        }

        .form-footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
        }

        .form-footer a {
            color: var(--ocean-blue);
            text-decoration: none;
            font-weight: 500;
        }

        /* Toggle between Login and Signup */
        .login-form, .signup-form {
            display: none;
        }

        .active-form {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }
            
            .welcome-section {
                padding: 30px;
            }
            
            .form-section {
                padding: 30px;
            }
        }

        @media (max-width: 480px) {
            .welcome-section h1 {
                font-size: 2rem;
            }
            
            .form-header h2 {
                font-size: 1.5rem;
            }
            
            .form-group input {
                padding: 12px 12px 12px 40px;
            }
            
            .form-group i {
                top: 37px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-section">
        <img class="ikan" src="https://cdn-icons-png.freepik.com/256/905/905594.png?ga=GA1.1.380298460.1745812613&semt=ais_hybrid" alt="Ikan">
            <h1>Selamat Datang di Bakaran Iwak</h1>
            <p>Tempat mengakses Makanan aneka hidangan ikan terbaik di seluruh dunia.</p>
            <div class="wave-animation">
                <!-- Wave animation placeholder -->
            </div>
        </div>
        
        <div class="form-section">
            <div class="form-container">
                <!-- Login Form -->
                <div class="login-form active-form">
                    <div class="form-header">
                        <h2>Login Dulu Yak</h2>
                        <p>Silakan masuk menggunakan email dan kata sandi Anda</p>
                    </div>
                    
                    <!-- Form HTML -->
<form action="login.php" method="POST">
    <div class="form-group">
        <label for="login-email">Email</label>
        <i class="fas fa-envelope"></i>
        <input type="email" name="email" id="login-email" placeholder="alamat@email.com" required>
    </div>

    <div class="form-group">
        <label for="login-password">Kata Sandi</label>
        <i class="fas fa-lock"></i>
        <input type="password" name="password" id="login-password" placeholder="••••••••" required>
    </div>

    <button type="submit" name="login" class="btn btn-primary">Masuk</button>

    <?php
    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    ?>
</form>
                        
                        <div class="remember-forgot">
                            <div class="remember-me">
                                <input type="checkbox" id="remember-me">
                                <label for="remember-me">Ingat saya</label>
                            </div>
                            <div class="forgot-password">
                                <a href="#">Lupa kata sandi?</a>
                            </div>
                        </div>
                        
                        
                        
                        <div class="social-login">
                            <p>Atau masuk dengan</p>
                            <div class="social-icons">
                                <a href="#" class="social-icon"><i class="fab fa-google"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                        
                        <div class="form-footer">
                            <p>Belum punya akun? <a href="#" id="show-signup">Daftar sekarang</a></p>
                        </div>
                    </form>
                </div>
                
                <!-- Signup Form -->
                <div class="signup-form">
                    <div class="form-header">
                        <h2>Buat Akun Baru</h2>
                        <p>Isi formulir berikut untuk membuat akun baru</p>
                    </div>
                    
                    <form action="#">
                        <div class="form-group">
                            <label for="signup-name">Nama Lengkap</label>
                            <i class="fas fa-user"></i>
                            <input type="text" id="signup-name" placeholder="Nama Anda" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="signup-email">Email</label>
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="login-email" name="email" placeholder="alamat@email.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="signup-password">Kata Sandi</label>
                            <i class="fas fa-lock"></i>
                            <input type="password" id="login-password" name="password" placeholder="••••••••" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="signup-confirm-password">Konfirmasi Kata Sandi</label>
                            <i class="fas fa-lock"></i>
                            <input type="password" id="signup-confirm-password" placeholder="••••••••" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Daftar</button>
                        
                        <div class="social-login">
                            <p>Atau daftar dengan</p>
                            <div class="social-icons">
                                <a href="#" class="social-icon"><i class="fab fa-google"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                            </div>
                        </div>
                        
                        <div class="form-footer">
                            <p>Sudah punya akun? <a href="#" id="show-login">Masuk sekarang</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   
</body>
</html>