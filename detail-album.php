<?php
require_once 'db.php';

if(isset($_GET['album'])) {
    $selected_album = $_GET['album'];
    if ($selected_album == "Semua Foto") {
        // Jika dipilih "Semua Foto", ambil semua foto tanpa memperhatikan album
        $query = "SELECT * FROM tb_image WHERE image_status = 1 ORDER BY image_id DESC";
    } else {
        // Jika album spesifik dipilih, ambil foto sesuai dengan albumnya
        $query = "SELECT * FROM tb_image WHERE album = '$selected_album' AND image_status = 1 ORDER BY image_id DESC";
    }
    $result = mysqli_query($conn, $query);
} else {
    // Jika tidak ada album yang dipilih, kembali ke galeri.php
    header("Location: galeri.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detail Album - WEB Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="css/index.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>
<header>
    <div class="container">
        <h1><a href="index.php">My Awesome Gallery</a></h1>
        <nav>
            <ul>
                <li><a href="javascript:void(0);" onclick="goBack()">Kembali</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="section">
    <div class="container">
        <div class="box">
            <!-- Konten foto dari album yang dipilih -->
            <?php
            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-4">';
                    echo '<a href="tampilan-image.php?id=' . $row['image_id'] . '">';
                    echo '<div class="image-container">';
                    echo '<img src="foto/' . $row['image'] . '" alt="' . $row['image_name'] . '" />';
                    echo '<div class="overlay">';
                    echo '<div class="text">' . substr($row['image_name'], 0, 30) . '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '<p class="admin">Uploader : ' . $row['admin_name'] . '</p>';
                    echo '<p class="date">' . $row['date_created'] . '</p>';
                    echo '</div>';
                }
            } else {
                // Jika tidak ada gambar yang ditemukan, tampilkan pesan
                echo '<p>Foto tidak ada</p>';
            }
            ?>
        </div>
    </div>
</div>
<footer>
    <div class="container">
        <small>Copyright &copy; 2024 - Muhamad Ilyas Shihabudin.</small>
    </div>
</footer>
</body>
</html>
