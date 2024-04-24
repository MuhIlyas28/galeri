<?php
require_once 'db.php';

// Mendapatkan daftar album yang tersedia
$query_albums = "SELECT DISTINCT(album) FROM tb_image";
$result_albums = mysqli_query($conn, $query_albums);

// Ambil satu foto dari setiap album
$query_one_photo_per_album = "SELECT * FROM tb_image WHERE album IN (SELECT DISTINCT(album) FROM tb_image) GROUP BY album ORDER BY image_id DESC";
$result_one_photo_per_album = mysqli_query($conn, $query_one_photo_per_album);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WEB Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="css/index.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<header>
    <div class="container">
        <h1><a href="index.php">My Awesome Gallery</a></h1>
        <nav>
            <ul>
                <li><a href="index.php">Beranda</a></li>
                <li><a href="galeri.php">Album Foto</a></li>
                <li><a href="registrasi.php">Registrasi</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </div>
</header>
<div class="search">
    <div class="container">
        <form action="galeri.php">
            <input type="text" name="search" placeholder="Cari Foto" />
            <button type="submit" name="cari"><i class="fas fa-search"></i></button>
        </form>
    </div>
</div>
<div class="section">
    <div class="container">
        <div class="box">
            <!-- Tampilkan daftar album dan satu foto dari setiap album -->
            <?php while ($row_album = mysqli_fetch_assoc($result_albums)) : ?>
                <div class="albums">
                    <h3><?php echo $row_album['album']; ?></h3>
                    <?php
                    $album_name = $row_album['album'];
                    $query_one_photo = "SELECT * FROM tb_image WHERE album = '$album_name' AND image_status = 1 ORDER BY image_id DESC LIMIT 1";
                    $result_one_photo = mysqli_query($conn, $query_one_photo);
                    if ($result_one_photo && mysqli_num_rows($result_one_photo) > 0) {
                        $row = mysqli_fetch_assoc($result_one_photo);
                    ?>
                        <div class="photos">
                            <div class="image-container">
                                <a href="detail-album.php?album=<?php echo urlencode($row['album']); ?>">
                                    <img src="foto/<?php echo $row['image']; ?>" alt="<?php echo $row['image_name']; ?>" />
                                </a>
                            </div>
                        </div>
                    <?php } else {
                        echo '<p>Tidak ada foto dalam album ini</p>';
                    } ?>
                </div>
            <?php endwhile; ?>
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
