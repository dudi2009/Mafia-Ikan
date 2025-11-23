<?php
$koneksi = new mysqli("localhost", "root", "", "resto");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ambil data pesanan
$result = $koneksi->query("SELECT * FROM pesanan WHERE id = $id");
$data = $result->fetch_assoc();

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama     = htmlspecialchars($_POST['nama_penerima']);
    $produk   = htmlspecialchars($_POST['produk']);
    $harga    = (int)str_replace('.', '', $_POST['harga']);
    $jumlah   = (int)$_POST['jumlah'];
    $total    = (int)str_replace('.', '', $_POST['total']);
    $alamat   = htmlspecialchars($_POST['alamat']);

    $sql = "UPDATE pesanan SET 
                nama_penerima = '$nama', 
                produk = '$produk', 
                harga = $harga, 
                jumlah = $jumlah, 
                total = $total, 
                alamat = '$alamat'
            WHERE id = $id";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Pesanan berhasil diperbarui!'); window.location.href='Tampilan.php';</script>";
    } else {
        echo "Error: " . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Pesanan</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f2f2f2;
      padding: 40px;
    }
    .form-container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      color: #2c3e50;
    }
    input, select, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 10px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    .btn {
      display: block;
      width: 100%;
      padding: 12px;
      background-color: #27ae60;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }
    .btn:hover {
      background-color: #219150;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Edit Pesanan</h2>
  <form method="post">
    <label>Nama Penerima</label>
    <input type="text" name="nama_penerima" value="<?= $data['nama_penerima'] ?>" required>

    <label>Produk</label>
    <select id="produk" name="produk" onchange="updateHarga()" required>
      <option value="">-- Pilih Produk --</option>
      <option value="Nila Bakar" data-harga="43000" <?= $data['produk'] == 'Nila Bakar' ? 'selected' : '' ?>>Nila Bakar</option>
      <option value="Bawal Bakar" data-harga="55000" <?= $data['produk'] == 'Bawal Bakar' ? 'selected' : '' ?>>Bawal Bakar</option>
      <option value="Cumi Bakar" data-harga="38000" <?= $data['produk'] == 'Cumi Bakar' ? 'selected' : '' ?>>Cumi Bakar</option>
      <option value="Ayam Bakar" data-harga="20000" <?= $data['produk'] == 'Ayam Bakar' ? 'selected' : '' ?>>Ayam Bakar</option>
      <option value="Gurame Bakar" data-harga="55000" <?= $data['produk'] == 'Gurame Bakar' ? 'selected' : '' ?>>Gurame Bakar</option>
      <option value="Udang Bakar" data-harga="38000" <?= $data['produk'] == 'Udang Bakar' ? 'selected' : '' ?>>Udang Bakar</option>
      <option value="Lele Bakar" data-harga="25000" <?= $data['produk'] == 'Lele Bakar' ? 'selected' : '' ?>>Lele Bakar</option>
    </select>

    <label>Harga (Rp)</label>
    <input type="text" id="harga" name="harga" readonly value="<?= number_format($data['harga'], 0, ',', '.') ?>" required>

    <label>Jumlah</label>
    <input type="number" id="jumlah" name="jumlah" value="<?= $data['jumlah'] ?>" oninput="updateTotal()" required>

    <label>Total Harga (Rp)</label>
    <input type="text" id="total" name="total" readonly value="<?= number_format($data['total'], 0, ',', '.') ?>" required>

    <label>Alamat</label>
    <textarea name="alamat" rows="4" required><?= $data['alamat'] ?></textarea>

    <button type="submit" class="btn">Simpan Perubahan</button>
  </form>
</div>

<script>
  let hargaSatuan = 0;

  function updateHarga() {
    const produk = document.getElementById("produk");
    const hargaInput = document.getElementById("harga");
    const selected = produk.options[produk.selectedIndex];
    const harga = selected.getAttribute("data-harga");

    hargaSatuan = harga ? parseInt(harga) : 0;
    hargaInput.value = hargaSatuan.toLocaleString('id-ID');
    updateTotal();
  }

  function updateTotal() {
    const jumlah = parseInt(document.getElementById("jumlah").value) || 0;
    const totalInput = document.getElementById("total");
    const total = jumlah * hargaSatuan;
    totalInput.value = total.toLocaleString('id-ID');
  }

  // Inisialisasi saat halaman dimuat
  document.addEventListener("DOMContentLoaded", () => {
    const selectedOption = document.querySelector("#produk option:checked");
    if (selectedOption) {
      hargaSatuan = parseInt(selectedOption.getAttribute("data-harga")) || 0;
    }
  });
</script>

</body>
</html>
