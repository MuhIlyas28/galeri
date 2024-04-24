<?php
require_once 'db.php';
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);

// Query untuk mengambil semua foto yang baru ditambahkan
$query = "SELECT * FROM tb_image WHERE image_status = 1 ORDER BY image_id DESC LIMIT 100";
$result = mysqli_query($conn, $query);
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
        <h3>Kategori</h3>
        <div class="categories">
            <?php
            $kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
            if(mysqli_num_rows($kategori) > 0){
                while($k = mysqli_fetch_array($kategori)){
                    ?>
                    <div class="category" id="kategori_<?php echo $k['category_id']; ?>">
                        <img src="img/<?php echo strlen($k['image']) > 0 ? $k['image'] : 'default_image.png'; ?>" alt="<?php echo $k['category_name'] ?>" />
                        <p><?php echo $k['category_name'] ?></p>
                    </div>
                <?php }}else{ ?>
                <p>Kategori tidak ada</p>
            <?php } ?>
        </div>
    </div>
</div>

<div class="container" id="foto-container">
    <h3>Foto Terbaru</h3>
    <div class="loading">
        <i class="fas fa-spinner fa-spin"></i>
    </div>
    <div class="box" id="foto-box">
        <!-- Konten foto dari semua foto yang baru ditambahkan -->
        <?php
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<a href="tampilan-image.php?id=' . $row['image_id'] . '" class="image-link">';
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
            // Jika tidak ada gambar yang ditemukan, tampilkan pesan
            echo '<p>Foto tidak ada</p>';
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
    $(document).ready(function(){
        $('.category').click(function(e){
            e.preventDefault();
            var category_id = $(this).attr('id').split('_')[1];
            $('.loading').show(); // Tampilkan indikator loading
            $('#foto-box').empty(); // Kosongkan konten foto sebelum memuat data baru
            $.ajax({
                type: 'GET',
                url: 'load-images.php', // Ganti dengan nama file PHP yang akan memuat gambar berdasarkan kategori
                data: { category_id: category_id },
                success: function(response){
                    $('#foto-box').html(response);
                    $('.loading').hide(); // Sembunyikan indikator loading setelah selesai memuat
                }
            });
        });
    });
</script>
</body>
</html>
