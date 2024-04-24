<?php
session_start();
include 'db.php';

if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
    exit; // Keluar dari skrip setelah mengarahkan pengguna ke halaman login
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/tambah-image.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-LqIh+1LhpFZdF0h2xaLJwu2CtvLa8K37ifpyW5I15Lz+HvDzd2eXtdZvmfLSsB4LpULM5vysSMf3Z4RNo4IWXQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <!-- header -->
    <header class="header"
        style="background: linear-gradient(90deg, rgba(0, 194, 177, 1) 0%, rgba(0, 147, 162, 1) 50%, rgba(0, 94, 145, 1) 100%);">
        <div class="container header-content">
            <h1><a href="tambah-image.php" style="color: #fff;">My Awesome Gallery</a></h1>
            <div class="nav-links">
                <ul>
                    <li><a href="data-image.php" style="color: #fff;">Kembali</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- content -->
    <div class="section">
        <div class="container">
            <div class="form-group">
                <h3>Tambah Data Foto</h3>
                <div class="box">

                    <form action="" method="POST" enctype="multipart/form-data">
                        <!-- Pilihan Album -->
                        <select class="input-control" name="album" onchange="checkNewAlbum(this)" required>
                            <option value="">- Pilih Album -</option>
                            <option value="Foto Favorite">Foto Favorite</option> <!-- Sesuaikan nilai dengan kategori -->
                            <option value="Semua Foto">Semua Foto</option> <!-- Ubah nilai sesuai kebutuhan -->
                            <option value="new_album">Album Baru</option> <!-- Tambahkan opsi untuk album baru -->
                        </select>
                        <input type="hidden" name="nama_album" id="album_name" value="">

                        <!-- Input untuk menambahkan album baru -->
                        <div id="new_album_input" style="display: none;">
                            <input type="text" name="new_album_name" class="input-control" placeholder="Nama Album Baru" required>
                        </div>

                        <!-- Pilihan Kategori Foto -->
                        <select class="input-control" name="kategori" onchange="setAlbumName()" required>
                            <option>-Pilih Kategori Foto-</option>
                            <?php   
                            $result = mysqli_query($conn,"select * from tb_category");   
                            $jsArray = "var prdName = new Array();\n";   
                            while ($row = mysqli_fetch_array($result)) {  
                                echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';  
                                $jsArray .= "prdName['" . $row['category_id'] . "'] = '" . addslashes($row['category_name']) . "';\n";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="nama_kategori" id="prd_name">

                        <!-- Informasi Admin -->
                        <input type="hidden" name="adminid" value="<?php echo $_SESSION['a_global']['admin_id'] ?>">
                        <input type="text" name="namaadmin" class="input-control"
                            value="<?php echo $_SESSION['a_global']['username'] ?>" readonly="readonly">

                        <!-- Informasi Foto -->
                        <input type="text" name="nama" class="input-control" placeholder="Nama Foto" required>
                        <textarea class="input-control" name="deskripsi" placeholder="Deskripsi"></textarea><br />
                        <input type="file" name="gambar" class="input-control" required>
                        <select class="input-control" name="status">
                            <option value="">--Pilih--</option>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                        <input type="submit" name="submit" value="Submit" class="btn"
                            style="background: linear-gradient(90deg, rgba(0, 194, 177, 1) 0%, rgba(0, 147, 162, 1) 50%, rgba(0, 94, 145, 1) 100%);">
                    </form>
                    <?php
                        if(isset($_POST['submit'])){
                            
                            // Menampung inputan dari form
                            $album      = $_POST['album'];
                            $nama_album = $_POST['nama_album'];
                            $kategori   = $_POST['kategori'];
                            $nama_ka    = $_POST['nama_kategori'];
                            $adminid    = $_POST['adminid'];
                            $namaadmin  = $_POST['namaadmin'];
                            $nama       = $_POST['nama'];
                            $deskripsi  = $_POST['deskripsi'];
                            $status     = $_POST['status'];

                            // Jika album baru ditambahkan, ambil nama album dari input tambahan
                            if ($album === 'new_album') {
                                $new_album_name = $_POST['new_album_name'];
                                $album = $new_album_name;
                            }
                            
                            // Menampung data file yang diupload
                            $filename   = $_FILES['gambar']['name'];
                            $tmp_name   = $_FILES['gambar']['tmp_name'];
                            $type1      = explode('.', $filename);
                            $type2      = $type1[1];
                            $newname    = 'foto'.time().'.'.$type2; 
                            
                            // Proses upload file sekaligus insert ke database
                            move_uploaded_file($tmp_name, './foto/'.$newname);
                            
                            $insert = mysqli_query($conn, "INSERT INTO tb_image (category_id, category_name, admin_id, admin_name, image_name, image_description, image, image_status, album)
                                VALUES ('$kategori', '$nama_ka', '$adminid', '$namaadmin', '$nama', '$deskripsi', '$newname', '$status', '$album')");
                                                
                            if($insert){
                                echo '<script>alert("Tambah Foto berhasil")</script>';
                                if(isset($_GET['source']) && $_GET['source'] == 'data-image') {
                                    echo '<script>window.location="data-image.php"</script>'; // Arahkan kembali ke data-image.php jika source adalah data-image
                                } else {
                                    echo '<script>window.location="admin-gallery.php"</script>'; 
                                }
                                exit; // Keluar dari skrip setelah redirect
                            } else {
                                echo '<script>alert("Gagal menambahkan foto. '.mysqli_error($conn).'")</script>'; // Tampilkan pesan kesalahan
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <small>Copyright &copy; 2024 - Muhamad Ilyas Shihabudin.</small>
        </div>
    </footer>
    <script>
        CKEDITOR.replace('deskripsi');  

        // Fungsi untuk menetapkan nama album berdasarkan pilihan kategori
        function setAlbumName() {
            var selectedCategory = document.querySelector('select[name="kategori"]').value;
            var albumInput = document.getElementById('album_name');
            albumInput.value = selectedCategory == 'my_favorite' ? 'My Favorite Foto' : 'All Foto';

            // Juga set nilai untuk input tersembunyi nama_kategori
            var categoryInput = document.getElementById('prd_name');
            categoryInput.value = prdName[selectedCategory];
        }

        // Fungsi untuk menampilkan input tambahan jika opsi Album Baru dipilih
        function checkNewAlbum(select) {
            var newAlbumInput = document.getElementById('new_album_input');
            if (select.value === 'new_album') {
                newAlbumInput.style.display = 'block';
            } else {
                newAlbumInput.style.display = 'none';
            }
        }
    </script>
    <script type="text/javascript">
        <?php echo $jsArray; ?>
    </script>
</body>
</html>
