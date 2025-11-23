<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "resto");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Tangkap data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = htmlspecialchars($_POST['nama_penerima']);
    $produk   = htmlspecialchars($_POST['produk']);
    $harga    = (int)str_replace('.', '', $_POST['harga']);
    $jumlah   = (int)$_POST['jumlah'];
    $total    = (int)str_replace('.', '', $_POST['total']);
    $alamat   = htmlspecialchars($_POST['alamat']);

    // Simpan ke database
    $sql = "INSERT INTO pesanan (nama_penerima, produk, harga, jumlah, total, alamat) 
            VALUES ('$nama', '$produk', $harga, $jumlah, $total, '$alamat')";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Pesanan berhasil disimpan!'); window.location.href='Tampilan.php';</script>";
    } else {
        echo "Error: " . $koneksi->error;
    }

    $koneksi->close();
}
?>
