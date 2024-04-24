<?php
session_start();
include 'db.php';
if ($_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Foto</title>
    <link rel="stylesheet" type="text/css" href="css/tambah-kategori.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-LqIh+1LhpFZdF0h2xaLJwu2CtvLa8K37ifpyW5I15Lz+HvDzd2eXtdZvmfLSsB4LpULM5vysSMf3Z4RNo4IWXQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <!-- header -->
    <header class="header"
        style="background: linear-gradient(90deg, rgba(0, 194, 177, 1) 0%, rgba(0, 147, 162, 1) 50%, rgba(0, 94, 145, 1) 100%);">
        <div class="container header-content">
            <h1><a href="admin-kategori.php" style="color: #fff;">My Awesome Gallery</a></h1>
            <div class="nav-links">
                <ul>
                    <li><a href="admin-kategori.php" style="color: #fff;">Kembali</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- content -->
    <div class="section">
        <div class="container">
            <div class="form-group">
                <h3>Tambah Data Kategori Foto</h3>
                <div class="box">

                    <form action="" method="POST" enctype="multipart/form-data">

                        <input type="text" name="nama_kategori" class="input-control" placeholder="Nama Kategori"
                            required>
                        <input type="file" name="image" class="input-control" required>

                        <input type="submit" name="submit" value="Submit" class="btn"
                            style="background: linear-gradient(90deg, rgba(0, 194, 177, 1) 0%, rgba(0, 147, 162, 1) 50%, rgba(0, 94, 145, 1) 100%);">
                    </form>
                    <?php
                        if(isset($_POST['submit'])){
                            
                            // menampung inputan dari form
                            $nama_kategori = $_POST['nama_kategori'];
                            // menampung data file yang diupload
                            $filename = $_FILES['image']['name'];
                            $tmp_name = $_FILES['image']['tmp_name'];
                            $type1 = explode('.', $filename);
                            $type2 = $type1[1];

                            $newname = 'foto'.time().'.'.$type2; 
                            
                            // menampung data format file yang diizinkan
                            $tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');
                            
                            // validasi format file
                            if(!in_array($type2, $tipe_diizinkan)){
                                // jika format file tidak ada di dalam tipe diizinkan
                                echo '<script>alert("Format file tidak diizinkan")</script>';
                            }else{
                                // jika format file sesuai dengan yang ada di dalam array tipe diizinkan
                                // proses upload file sekaligus insert ke database
                                move_uploaded_file($tmp_name, './img/'.$newname);
                                
                                $insert = mysqli_query($conn, 'insert into tb_category(category_name, image) values ("'.$nama_kategori.'", "'.$newname.'")');        
                                if($insert){
                                    echo '<script>alert("Berhasil menambahkan kategori"); window.location="admin-kategori.php";</script>';
                                    // echo '<script>window.location="admin.php"</script>';
                                }else{
                                    echo 'gagal'.mysqli_error($conn);
                                }
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
</body>

</html>
