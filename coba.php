<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Form Pemesanan</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
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
      color: #2c3e50;
      margin-bottom: 25px;
      text-align: center;
    }
    label {
      font-weight: 600;
      display: block;
      margin-bottom: 8px;
    }
    input, select, textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-sizing: border-box;
    }
    input[readonly] {
      background-color: #eaeaea;
    }
    button {
      width: 48%;
      padding: 12px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
    }
    .btn-submit {
      background-color: #27ae60;
      color: white;
    }
    .btn-edit {
      background-color: #2980b9;
      color: white;
      float: right;
    }
    .btn-submit:hover {
      background-color: #219150;
    }
    .btn-edit:hover {
      background-color: #1f6394;
    }
    .button-group {
      display: flex;
      justify-content: center;
      align-items:center;
    }
    textarea {
      resize: vertical;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Form Pemesanan</h2>
  <form id="orderForm" method="post" action="simpan_pesanan.php">
    <label for="nama">Nama Penerima</label>
    <input type="text" id="nama" name="nama_penerima" placeholder="Masukkan nama penerima" required>

    <label for="produk">Produk</label>
    <select id="produk" name="produk" onchange="updateHarga()" required>
      <option value="">-- Pilih Produk --</option>
      <option value=" Nila Bakar" data-harga="43000">Ikan Bakar</option>
      <option value="Bawal Bakar" data-harga="55000">Bawal Bakar</option>
      <option value="Cumi Bakar" data-harga="38000">Cumi Bakar</option>
      <option value="Ayam Bakar" data-harga="20000">Ayam Bakar</option>
      <option value="Gurame Bakar" data-harga="55000">Gurame Bakar</option>
      <option value="Udang Bakar" data-harga="38000">Udang Bakar</option>
      <option value="Lele Bakar" data-harga="25000">Lele Bakar</option>
      <option value="Patin Bakar" data-harga="94.545">Patin Bakar</option>
      <option value="Mujahir Bakar" data-harga="158400">Mujahir Bakar</option>
      <option value="Melem Bakar" data-harga="33000">Melem Bakar</option>
      <option value="Gabus Bakar" data-harga="70000">Gabus Bakar</option>
      <option value="Pindang Bakar" data-harga="36000">Pindang Bakar</option>
      <option value="Es teh" data-harga="2000">Es teh</option>
      <option value="Es Jeruk" data-harga="2500">Es Jeruk</option>
      <option value="Aqua" data-harga="2000">Aqua</option>
      <option value="Jus Mangga" data-harga="5000">Jus Mangga</option>
      <option value="Jus Jambu" data-harga="5000">Jus Jambu</option>
      <option value="Jus Alpukat" data-harga="6000">Jus Alpukat</option>
      <option value="Tahu Tempe" data-harga="5000">Tahu Tempe</option>
      <option value="Sayur Asem" data-harga="10000">Sayur Asem</option>
    </select>

    <label for="harga">Harga per Item (Rp)</label>
    <input type="text" id="harga" name="harga" readonly placeholder="Harga otomatis muncul">

    <label for="jumlah">Jumlah</label>
    <input type="number" id="jumlah" name="jumlah" min="1" placeholder="Masukkan jumlah pesanan" oninput="updateTotal()" required>

    <label for="total">Total Harga (Rp)</label>
    <input type="text" id="total" name="total" readonly placeholder="Total akan dihitung otomatis">

    <label for="alamat">Alamat Pengiriman</label>
    <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan alamat lengkap" required></textarea>

    <div class="button-group">
      <button type="submit" class="btn-submit">Simpan Pesanan</button>
     
    </div>
</form>
</div>

<script>
  let hargaSatuan = 0;

  function updateHarga() {
    const produk = document.getElementById("produk");
    const hargaInput = document.getElementById("harga");
    const selectedOption = produk.options[produk.selectedIndex];
    const harga = selectedOption.getAttribute("data-harga");

    hargaSatuan = harga ? parseInt(harga) : 0;
    hargaInput.value = hargaSatuan.toLocaleString('id-ID');

    updateTotal(); // update total jika sudah isi jumlah
  }

  function updateTotal() {
    const jumlah = parseInt(document.getElementById("jumlah").value) || 0;
    const totalInput = document.getElementById("total");
    const total = jumlah * hargaSatuan;
    totalInput.value = total.toLocaleString('id-ID');
  }

  function editForm() {
    document.getElementById('orderForm').scrollIntoView({ behavior: 'smooth' });
    alert("Form siap untuk diedit.");
  }
</script>

</body>
</html>


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
