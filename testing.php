<?php
$koneksi = new mysqli("localhost", "root", "", "resto");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Hapus pesanan jika ada parameter 'hapus'
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $koneksi->query("DELETE FROM pesanan WHERE id = $id");
    echo "<script>alert('Pesanan berhasil dihapus!'); window.location.href='Tampilan.php';</script>";
}

// Hapus semua pesanan jika parameter 'selesai' ada
if (isset($_GET['selesai'])) {
    $koneksi->query("DELETE FROM pesanan");
    echo "<script>alert('Semua pesanan berhasil ditandai sebagai diterima dan dihapus!'); window.location.href='Tampilan.php';</script>";
}

?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pesanan</title>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
        padding: 20px;
        background: linear-gradient(to right, #e0f7fa, #b2ebf2);
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #00796b;
        font-size: 28px;
    }

    a.button {
        padding: 8px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        color: white;
        margin: 0 5px;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .edit-btn {
        background-color: #0288d1;
    }

    .hapus-btn {
        background-color: #d32f2f;
    }

    .edit-btn:hover {
        background-color: #0277bd;
    }

    .hapus-btn:hover {
        background-color: #b71c1c;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        box-shadow: 0 4px 10px rgba(0, 121, 107, 0.2);
        border-radius: 10px;
        overflow: hidden;
    }

    th, td {
        padding: 14px 18px;
        border: 1px solid #b2dfdb;
        text-align: center;
    }

    th {
        background-color: #004d40;
        color: white;
        font-size: 16px;
    }

    td {
        background-color: #e0f2f1;
    }

    /* Tombol Tandai Semua Pesanan */
    .top-action {
    text-align: center;
    margin-bottom: 30px;
}

.selesai-btn {
    display: inline-block;
    padding: 12px 24px;
    background: #ff7043;
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    transition: background 0.3s ease, transform 0.2s;
}

.selesai-btn:hover {
    background: #e64a19;
    transform: scale(1.05);
}

</style>

</head>
<div class="container">
    <h2>Daftar Pesanan Resto Bakaran Ikan</h2>

    <div class="top-action">
    <a href="?selesai=1" onclick="return confirm('Semua pesanan akan dihapus. Lanjutkan?')" class="selesai-btn">
        ✅ Tandai Semua Pesanan Diterima
    </a>
</div>


    <table>
        <tr>
            <th>No</th>
            <th>Nama Penerima</th>
            <th>Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = $koneksi->query("SELECT * FROM pesanan");
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nama_penerima']}</td>
                    <td>{$row['produk']}</td>
                    <td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>
                    <td>{$row['jumlah']}</td>
                    <td>Rp " . number_format($row['total'], 0, ',', '.') . "</td>
                    <td>{$row['alamat']}</td>
                    <td>
                        <a href='edit_pesanan.php?id={$row['id']}' class='button edit-btn'>Edit</a>
                        <a href='?hapus={$row['id']}' onclick='return confirm(\"Yakin hapus?\")' class='button hapus-btn'>Hapus</a>
                    </td>
                  </tr>";
            $no++;
        }
        ?>
    </table>
</div>

</body>
</html>
