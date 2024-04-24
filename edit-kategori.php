<?php
session_start();
include 'db.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}

// Periksa apakah parameter 'id' tersedia di URL
if(isset($_GET['id'])) {
    $kategori = mysqli_query($conn, "SELECT * FROM tb_category WHERE category_id = '".$_GET['id']."'");

    // Periksa apakah kategori ditemukan
    if(mysqli_num_rows($kategori) == 0){
        echo '<script>window.location="index.php"</script>';
    } else {
        $k = mysqli_fetch_object($kategori);
    }
} else {
    // Jika parameter 'id' tidak tersedia, alihkan pengguna atau tampilkan pesan kesalahan
    echo '<script>alert("Parameter ID tidak ditemukan")</script>';
    echo '<script>window.location="index.php"</script>';
    exit; // Keluar dari skrip
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Kategori</title>
    <link rel="stylesheet" type="text/css" href="css/edit-kategori.css">
</head>
<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="admin-kategori.php">My Awesome Gallery</a></h1>
            <nav>
                <ul>
                    <li><a href="admin-kategori.php">Kembali</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- content -->
    <div class="section">
        <div class="container">
            <h3>Edit Data Kategori</h3>
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="text" name="nama_kategori" class="input-control" placeholder="Nama Kategori" value="<?php echo $k->category_name ?>" required>
                    <!-- Tambahkan input untuk mengunggah gambar -->
                    <input type="file" name="gambar_kategori" class="input-control" accept="image/*">
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                    if(isset($_POST['submit'])){
                        $nama_kategori = $_POST['nama_kategori'];

                        // Proses pengunggahan gambar
                        if(isset($_FILES['gambar_kategori']) && $_FILES['gambar_kategori']['error'] === UPLOAD_ERR_OK) {
                            $fileTmpPath = $_FILES['gambar_kategori']['tmp_name'];
                            $fileName = $_FILES['gambar_kategori']['name'];
                            $fileSize = $_FILES['gambar_kategori']['size'];
                            $fileType = $_FILES['gambar_kategori']['type'];
                            $fileNameCmps = explode(".", $fileName);
                            $fileExtension = strtolower(end($fileNameCmps));

                            // Menghasilkan nama unik untuk file
                            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                            $uploadFileDir = 'img/'; // Menyimpan gambar di dalam folder "img"
                            $dest_path = $uploadFileDir . $newFileName;

                            // Memindahkan file yang diunggah ke direktori upload
                            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                                // Proses update kategori dengan nama dan gambar baru
                                $update = mysqli_query($conn, "UPDATE tb_category SET 
                                                            category_name = '".$nama_kategori."',
                                                            image = '".$newFileName."'
                                                            WHERE category_id = '".$k->category_id."' ");
                                if($update){
                                    echo '<script>alert("Ubah data berhasil"); window.location="admin-kategori.php"</script>';
                                } else {
                                    echo 'gagal'.mysqli_error($conn);
                                }
                            } else {
                                echo 'Terjadi kesalahan saat mengunggah gambar.';
                            }
                        } else {
                            // Jika tidak ada gambar yang diunggah, hanya update nama kategori
                            $update = mysqli_query($conn, "UPDATE tb_category SET 
                                                        category_name = '".$nama_kategori."'
                                                        WHERE category_id = '".$k->category_id."' ");
                            if($update){
                                echo '<script>alert("Ubah data berhasil"); window.location="admin-kategori.php"</script>';
                            } else {
                                echo 'gagal'.mysqli_error($conn);
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Web Muhamad Ilyas Shihabudin.</small>
        </div>
    </footer>
</body>
</html>
