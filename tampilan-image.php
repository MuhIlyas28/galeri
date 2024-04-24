<?php
include 'db.php';

// Mendapatkan informasi kontak admin
$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);

// Mendapatkan informasi detail gambar
$produk = mysqli_query($conn, "SELECT tb_image.*, tb_category.category_name FROM tb_image INNER JOIN tb_category ON tb_image.category_id = tb_category.category_id WHERE tb_image.image_id = '" . $_GET['id'] . "'");
$p = mysqli_fetch_object($produk);

// Mengambil jumlah komentar
$result = mysqli_query($conn, "SELECT COUNT(*) AS total_comments FROM tb_image_comments WHERE image_id = '" . $_GET['id'] . "'");
$total_comments = mysqli_fetch_assoc($result)['total_comments'];

// Query untuk mendapatkan jumlah like
$result_likes = mysqli_query($conn, "SELECT likes FROM tb_image WHERE image_id = '" . $_GET['id'] . "'");
$likes = mysqli_fetch_assoc($result_likes)['likes'];

// Inisialisasi variabel untuk menentukan apakah pengguna sudah login
$userLoggedIn = false;

// Pengecekan apakah pengguna sudah login
if(isset($_SESSION['user_id'])) {
    $userLoggedIn = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/detail-image.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        function toggleComments() {
            var commentsContainer = document.getElementById('comments-container');
            commentsContainer.classList.toggle('show');
        }

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

    <!-- product detail -->
    <div class="section">
        <div class="container">
            <h3>Detail Foto</h3>
            <div class="box">
                <div class="col-2">
                    <img src="foto/<?php echo $p->image ?>" width="100%" />
                </div>
                <div class="col-2">
                    <h3><?php echo $p->image_name ?><br />Album : <?php echo $p->album  ?><br />Kategori : <?php echo $p->category_name  ?></h3>
                    <h4>Nama User : <?php echo $p->admin_name ?><br />
                        Upload Pada Tanggal : <?php echo $p->date_created  ?></h4>
                    <p>Deskripsi :<br />
                        <?php echo $p->image_description ?>
                    </p>

                    <!-- Menampilkan jumlah like -->
                    <p>Jumlah Like: <?php echo $likes; ?></p>

                    <!-- Menampilkan jumlah komentar -->
                    <p>Jumlah Komentar: <?php echo $total_comments; ?></p>

                    <!-- Tombol yang memunculkan daftar komentar -->
                    <button onclick="toggleComments()" class="comment-button"><i class="fas fa-comments"></i> Tampilkan Komentar</button>

                    <!-- Daftar komentar (awalnya disembunyikan) -->
                    <div id="comments-container" class="comments-container">
                        <?php
                        // Query untuk mendapatkan daftar komentar
                        $query_comments = mysqli_query($conn, "SELECT * FROM tb_image_comments WHERE image_id = '" . $_GET['id'] . "'");
                        while ($comment = mysqli_fetch_assoc($query_comments)) {
                        ?>
                            <div class="comment">
                                <p><?php echo $comment['comment']; ?></p>
                                <p class="comment-date"><i class="far fa-clock"></i> <?php echo date('d M Y H:i', strtotime($comment['created_at'])); ?></p>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <p>Silakan <a href="login.php">login</a> untuk memberikan like atau komentar.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Muhamad Ilyas Shihabudin.</small>
        </div>
    </footer>
</body>

</html>
