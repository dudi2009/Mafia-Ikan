<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

require 'Koneksi.php';

// Cek apakah ada pencarian
$searchQuery = isset($_GET['nama']) ? trim($_GET['nama']) : '';

if ($searchQuery !== '') {
    // Jika ada pencarian, tampilkan hasil dari semua tabel yang cocok
    $menuQuery = "SELECT * FROM menu WHERE Nama LIKE '%$searchQuery%'";
    $minumanQuery = "SELECT * FROM minuman WHERE Nama LIKE '%$searchQuery%'";
    $lainQuery = "SELECT * FROM lain WHERE Nama LIKE '%$searchQuery%'";

    $menuResult = mysqli_query($link, $menuQuery);
    $minumanResult = mysqli_query($link, $minumanQuery);
    $lainResult = mysqli_query($link, $lainQuery);

    $isSearch = true;
} else {
    // Jika tidak ada pencarian, tampilkan semua
    $menuQuery = "SELECT * FROM menu";
    $minumanQuery = "SELECT * FROM minuman";
    $lainQuery = "SELECT * FROM lain";

    $menuResult = mysqli_query($link, $menuQuery);
    $minumanResult = mysqli_query($link, $minumanQuery);
    $lainResult = mysqli_query($link, $lainQuery);

    $isSearch = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mafia Ikan</title>
    <link rel="icon" href="https://cdn-icons-png.freepik.com/256/905/905594.png?ga=GA1.1.380298460.1745812613&semt=ais_hybrid">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        .ikan {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .video-cover {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .video-cover video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 20px;
        }

        .video-overlay h1 {
            font-size: 3.5rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .video-overlay p {
            font-size: 1.2rem;
            max-width: 800px;
        }

        .box {
            width: 100%;
            height: 50px;
            background-color: #4a90e2;
        }

        .box h1 {
            margin-left: 47%;
            color: white;
        }

        .menu-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }

        body {
            background-color: #f0f8ff;
            color: #333;
            line-height: 1.6;
        }

        .navbar {
            position: sticky;
            top: 0;
            background-color: #5b8def;
            padding: 15px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        li {
            font-weight: bold;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-menu {
            list-style-type: none;
            display: flex;
            gap: 30px;
        }

        .nav-menu li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 4px;
            transition: background-color 0.3s, filter 0.3s ease;
        }

        .nav-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.2);
            filter: brightness(1.2);
        }

        .search {
            border-radius: 20px;
            padding: 10px;
        }

        .search-buton {
            border-radius: 20px;
            padding: 10px;
            background-color: #ff7d00;
            border: none;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .content {
            padding: 50px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .content h1 {
            margin-bottom: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .menu-section {
            margin-bottom: 40px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title h2 {
            color: #4a90e2;
            font-size: 1.8rem;
        }

        .menu-items {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }

        .menu-item {
            flex: 0 0 calc(33.333% - 20px);
            box-sizing: border-box;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .menu-item::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: linear-gradient(to top, rgba(91, 141, 239, 0.3) 0%, transparent 100%);
            opacity: 0;
            transition: height 0.3s ease, opacity 0.3s ease;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            z-index: 0;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .menu-item:hover::after {
            height: 40%;
            opacity: 1;
        }

        .item-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .item-image {
            height: 200px;
            overflow: hidden;
        }

        .item-details {
            padding: 20px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px dashed #ddd;
            padding-bottom: 10px;
        }

        .item-name {
            font-weight: 600;
            font-size: 1.2rem;
            color: #3b7cb8;
        }

        .item-price {
            font-weight: 700;
            color: #3b7cb8;
        }

        .item-description {
            color: #666;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .item-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tag {
            background-color: #77aaff;
            color: #ffffff;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        footer {
            background-color: #3a506b;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }

    </style>
</head>

<body>

    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo"><img class="ikan" src="https://cdn-icons-png.freepik.com/256/905/905594.png?ga=GA1.1.380298460.1745812613&semt=ais_hybrid" alt="Ikan">Mafia Ikan</div>
                <ul class="nav-menu">
                    <li><a href="#" class="active">Home</a></li>
                    <li><a href="coba.php">Order</a></li>
                    <li><a href="Logout.php">Logout</a></li>
                    <li><a href="testing.php">Pesanan</a></li>
                    <li><a href="#">Tambah menu</a></li>
                </ul>

                <!-- SEARCH -->
                <form action="Tampilan.php" method="GET">
                    <input class="search" type="text" name="nama" placeholder="Cari menu..." />
                    <button class="search-buton" type="submit" name="submit">
                        <i class="fas fa-search"></i> Search
                    </button>
                </form>

            </div>
        </nav>
    </header>

    <!-- Video Cover Section -->
    <div class="video-cover">
            <video autoplay muted loop>
                <source src="img/4K Video grilled fish with grill.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="video-overlay">
                <h1>Aneka Ikan Bakar</h1>
                <p>Nikmati hidangan ikan bakar segar dengan bumbu rahasia kami yang menggugah selera</p>
            </div>
        </div>

    <div class="box">
        <h1> Menu</h1>
    </div>

    <main class="container">

        <!-- Aneka Ikan Bakar -->
        <?php if (!$isSearch || mysqli_num_rows($menuResult) > 0): ?>
        <section class="menu-section">
            <div class="section-title">
                <h2>Aneka Ikan Bakar</h2>
            </div>
            <div class="menu-items">
                <?php while ($data = mysqli_fetch_assoc($menuResult)) : ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="./img/<?= htmlspecialchars($data['Gambar']) ?>" alt="<?= htmlspecialchars($data['Nama']) ?>">
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <span class="item-name"><?= htmlspecialchars($data['Nama']) ?></span>
                                <span class="item-price">Rp <?= ($data['Harga']) ?></span>
                            </div>
                            <p class="item-description"><?= htmlspecialchars($data['Deskripsi']) ?></p>
                            <div class="item-tags">
                                <span class="tag"><?= htmlspecialchars($data['Kategori']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Minuman -->
        <?php if (!$isSearch || mysqli_num_rows($minumanResult) > 0): ?>
        <section class="menu-section">
            <div class="section-title">
                <h2>Minuman</h2>
            </div>
            <div class="menu-items">
                <?php while ($ombe = mysqli_fetch_assoc($minumanResult)) : ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="./img/<?= htmlspecialchars($ombe['Gambar']) ?>" alt="<?= htmlspecialchars($ombe['Nama']) ?>">
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <span class="item-name"><?= htmlspecialchars($ombe['Nama']) ?></span>
                                <span class="item-price">Rp <?= ($ombe['Harga']) ?></span>
                            </div>
                            <p class="item-description"><?= htmlspecialchars($ombe['Deskripsi']) ?></p>
                            <div class="item-tags">
                                <span class="tag"><?= htmlspecialchars($ombe['Kategori']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Lainnya -->
        <?php if (!$isSearch || mysqli_num_rows($lainResult) > 0): ?>
        <section class="menu-section">
            <div class="section-title">
                <h2>Lainnya</h2>
            </div>
            <div class="menu-items">
                <?php while ($other = mysqli_fetch_assoc($lainResult)) : ?>
                    <div class="menu-item">
                        <div class="item-image">
                            <img src="./img/<?= htmlspecialchars($other['Gambar']) ?>" alt="<?= htmlspecialchars($other['Nama']) ?>">
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <span class="item-name"><?= htmlspecialchars($other['Nama']) ?></span>
                                <span class="item-price">Rp <?= ($other['Harga']) ?></span>
                            </div>
                            <p class="item-description"><?= htmlspecialchars($other['Deskripsi']) ?></p>
                            <div class="item-tags">
                                <span class="tag"><?= htmlspecialchars($other['Kategori']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <footer>
        <div class="container">
            <p>© 2023 Gourmet Delights. All rights reserved.</p>
            <p>123 Culinary Street, Foodville | (555) 123-4567</p>
        </div>
    </footer>

</body>
</html>
