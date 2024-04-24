<?php
session_start();
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

require_once 'db.php';

// Sisipkan kode untuk memproses pencarian
if (isset($_GET['cari'])) {
    $search = $_GET['search'];
    // Query untuk mencari gambar berdasarkan nama atau kategori
    $query = "SELECT * FROM tb_image WHERE (image_name LIKE '%$search%' OR category_id IN (SELECT category_id FROM tb_category WHERE category_name LIKE '%$search%')) AND image_status = 1 ORDER BY image_id DESC LIMIT 100";
} else {
    // Jika tidak ada pencarian, tampilkan semua gambar terbaru
    $query = "SELECT * FROM tb_image WHERE image_status = 1 ORDER BY image_id DESC LIMIT 100";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WEB Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<header>
    <div class="container">
        <h1><a href="dashboard.php">My Awesome Gallery</a></h1>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="data-image.php">Data Foto</a></li>
            <li><a href="Keluar.php">Keluar</a></li>
        </ul>
    </div>
</header>
<div class="search">
    <div class="container">
        <form action="dashboard.php" method="GET"> <!-- Perbarui action ke dashboard.php -->
            <input type="text" name="search" placeholder="Cari Foto" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
            <button type="submit" name="cari"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="box">
            <h4>Selamat Datang <?php echo $_SESSION['a_global']['username'] ?> di Website Galeri Foto</h4>
        </div>
    </div>
</div>

<div class="container" id="foto-container">
    <h3>Foto Tersimpan</h3>
    <div class="loading">
        <i class="fas fa-spinner fa-spin"></i>
    </div>
    <div class="box" id="foto-box">
        <?php
        // Tampilkan gambar sesuai dengan hasil pencarian atau semua gambar terbaru
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<a href="detail-image.php?id=' . $row['image_id'] . '" class="image-link">';
                echo '<div class="col-4">';
                echo '<div class="image-container">';
                echo '<img src="foto/' . $row['image'] . '" alt="' . $row['image_name'] . '" />';
                echo '<div class="overlay">';
                echo '<div class="text">' . substr($row['image_name'], 0, 30) . '</div>';
                echo '</div>';
                echo '</div>';
                echo '<p class="admin">Uploader : ' . $row['admin_name'] . '</p>';
                echo '<p class="date">' . $row['date_created'] . '</p>';
                echo '</div>';
                echo '</a>';
            }
        } else {
            // Tampilkan pesan jika tidak ada hasil pencarian atau gambar tersedia
            echo '<p>Foto tidak ditemukan</p>';
        }
        ?>
    </div>
</div>
<footer>
    <div class="container">
        <small>Copyright &copy; 2024 - Muhamad Ilyas Shihabudin.</small>
    </div>
</footer>
<script>
    $(document).ready(function () {
        // Sisipkan kode JavaScript tetap sama seperti sebelumnya
    });
</script>
</body>
</html>
