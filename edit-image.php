<?php
    session_start();
    include 'db.php';
    if($_SESSION['status_login'] != true){
        echo '<script>window.location="login.php"</script>';
    }
    
    $produk = mysqli_query($conn, "SELECT * FROM  tb_image WHERE image_id = '".$_GET['id']."'");
    if(mysqli_num_rows($produk) == 0){
        echo '<script>window.location="admin-gallery.php"</script>';
    }
    $p = mysqli_fetch_object($produk);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/edit-image.css">
    <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
</head>
<body>
    <!-- header -->
    <header>
    <div class="container">
        <h1><a href="<?php echo ($_SESSION['a_global']['role'] == 'admin') ? 'admin-gallery.php' : 'data-image.php'; ?>">My Awesome Gallery</a></h1>
        <ul>
            <li><a href="<?php echo ($_SESSION['a_global']['role'] == 'admin') ? 'admin-gallery.php' : 'data-image.php'; ?>">Kembali</a></li>
        </ul>
    </div>
   </header>    
    <!-- content -->
    <div class="section">
        <div class="container">
            <h3>Edit Data Foto</h3>
            <div class="box">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="text" name="album" class="input-control" placeholder="Album" value="<?php echo $p->album ?>" required>
                    <input type="text" name="kategori" class="input-control" placeholder="Kategori Foto" value="<?php echo $p->category_name ?>" readonly="readonly">
                    <input type="text" name="namauser" class="input-control" placeholder="Nama User" value="<?php echo $p->admin_name ?>" readonly="readonly">
                    <input type="text" name="nama" class="input-control" placeholder="Nama Foto" value="<?php echo $p->image_name ?>" required>
                    <img src="foto/<?php echo $p->image ?>" width="100px" />
                    <input type="hidden" name="foto" value="<?php echo $p->image ?>" />
                    <input type="file" name="gambar" class="input-control">
                    <textarea class="input-control" name="deskripsi" placeholder="Deskripsi"><?php echo $p->image_description ?></textarea><br />
                    <select class="input-control" name="status">
                        <option value="">--Pilih--</option>
                        <option value="1" <?php echo ($p->image_status == 1)? 'selected':''; ?>>Aktif</option>
                        <option value="0"<?php echo ($p->image_status == 0)? 'selected':''; ?>>Tidak Aktif</option> 
                    </select>
                    <input type="submit" name="submit" value="Submit" class="btn">
                </form>
                <?php
                    if(isset($_POST['submit'])){
                        
                        // data inputan dari form
                        $album      = $_POST['album'];
                        $kategori  = $_POST['kategori'];
                        $user      = $_POST['namauser'];
                        $nama      = $_POST['nama'];
                        $deskripsi = $_POST['deskripsi'];
                        $status    = $_POST['status'];
                        $foto      = $_POST['foto'];
                        
                        // data gambar yang baru 
                        $filename = $_FILES['gambar']['name'];
                        $tmp_name = $_FILES['gambar']['tmp_name'];
                           
                        //jika admin ganti gambar
                        if($filename != ''){
                            
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
                            unlink('./foto/'.$foto); 
                            move_uploaded_file($tmp_name, './foto/'.$newname);
                            $namagambar = $newname;  
                          }
                        
                        }else{
                           // jika admin tidak ganti gambar
                           $namagambar = $foto;
                           
                        }
                        
                        //query update data produk
                        $update = mysqli_query($conn, "UPDATE tb_image SET
                        album               = '".$album."',
                        category_name       = '".$kategori."',
                        admin_name          = '".$user."',
                        image_name          = '".$nama."',
                        image_description   = '".$deskripsi."',
                        image               = '".$namagambar."',
                        image_status        = '".$status."'
                        WHERE image_id      = '".$p->image_id."' ");
                                                   if($update){
                                                    echo '<script>alert("Ubah data berhasil")</script>';
                                                    if($_SESSION['a_global']['role'] == 'admin') {
                                                        echo '<script>window.location="admin-gallery.php"</script>'; // Admin dialihkan ke halaman admin-gallery.php
                                                    } else {
                                                        echo '<script>window.location="data-image.php"</script>'; // Pengguna biasa dialihkan ke halaman data-image.php
                                                    }
                                                } else {
                                                    echo 'gagal'.mysqli_error($conn);
                                                }                                                
                      }
                   ?>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Muhamad Ilyas Shihabudin.</small>
        </div>
    </footer>
    <script>
            CKEDITOR.replace( 'deskripsi' );
    </script>
</body>
</html>
