<?php
session_start();
include 'db.php';

// Periksa apakah pengguna datang dari halaman login.php
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
if(strpos($referer, 'login.php') !== false) {
    // Pengguna datang dari halaman login.php, arahkan ke bagian "Ubah password"
    header('Location: profil.php#ubah-password');
    exit();
}

// Cek apakah pengguna sudah login
if(!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true){
    echo '<script>window.location="login.php"</script>';
    exit(); // Pastikan keluar dari skrip setelah mengalihkan
}

// Ambil data user dari database menggunakan ID pengguna dari sesi
$user_id = isset($_SESSION['a_global']['admin_id']) ? $_SESSION['a_global']['admin_id'] : ''; 
$query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE admin_id = '$user_id'");
$row = mysqli_fetch_assoc($query);

// Ambil data dari hasil query
$nama = isset($row['admin_name']) ? $row['admin_name'] : '';
$username = isset($row['username']) ? $row['username'] : '';
$telpon = isset($row['admin_telp']) ? $row['admin_telp'] : '';
$email = isset($row['admin_email']) ? $row['admin_email'] : '';
$alamat = isset($row['admin_address']) ? $row['admin_address'] : '';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>WEB Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="css/profil.css">
</head>

<body>
    <!-- header -->
    <header>
        <div class="container">
            <h1><a href="dashboard.php">My Awesome Gallery</a></h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="profil.php">Profil</a></li>
                    <li><a href="data-image.php">Data Foto</a></li>
                    <li><a href="Keluar.php">Keluar</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- content -->
    <div class="section">
        <div class="container">
            <div class="profile-info">
                <h3>Profil</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="text" name="nama" placeholder="Nama Lengkap" class="input-control" value="<?php echo $nama ?>" required>
                        <input type="text" name="user" placeholder="Username" class="input-control" value="<?php echo $username ?>" required>
                        <input type="text" name="hp" placeholder="No Hp" class="input-control" value="<?php echo $telpon ?>" required>
                        <input type="email" name="email" placeholder="Email" class="input-control" value="<?php echo $email ?>" required>
                        <input type="text" name="alamat" placeholder="Alamat" class="input-control" value="<?php echo $alamat ?>" required>
                        <input type="submit" name="submit" value="Ubah Profil" class="btn">
                    </form>
                    <?php
                        if(isset($_POST['submit'])){
                            
                            $nama   = $_POST['nama'];
                            $user   = $_POST['user'];
                            $hp     = $_POST['hp'];
                            $email  = $_POST['email'];
                            $alamat = $_POST['alamat'];
                            
                            $update = mysqli_query($conn, "UPDATE tb_admin SET 
                                          admin_name = '".$nama."',
                                          username = '".$user."',
                                          admin_telp = '".$hp."',
                                          admin_email = '".$email."',
                                          admin_address = '".$alamat."'
                                          WHERE admin_id = '".$_SESSION['a_global']['admin_id']."'");
                            if($update){
                                echo '<script>alert("Ubah data berhasil")</script>';
                                echo '<script>window.location="profil.php"</script>';
                            }else{
                                echo 'gagal '.mysqli_error($conn);
                            }
                            
                        }  
                    ?>
                </div>
            </div>
            
            <div class="change-password">
                <h3>Ubah password</h3>
                <div class="box">
                    <form action="" method="POST">
                        <input type="password" name="pass1" placeholder="Password Baru" class="input-control" required>
                        <input type="password" name="pass2" placeholder="Konfirmasi Password Baru" class="input-control" required>
                        <input type="submit" name="ubah_password" value="Ubah Password" class="btn">
                    </form>
                    <?php
                        if(isset($_POST['ubah_password'])){
                            
                            $pass1   = $_POST['pass1'];
                            $pass2   = $_POST['pass2'];
                            
                            if($pass2 != $pass1){
                                echo '<script>alert("Konfirmasi Password Baru tidak sesuai")</script>';
                            }else{
                                $u_pass = mysqli_query($conn, "UPDATE tb_admin SET 
                                          password = '".$pass1."'
                                          WHERE admin_id = '".$_SESSION['a_global']['admin_id']."'");
                                if($u_pass){
                                    echo '<script>alert("Ubah data berhasil")</script>';
                                    echo '<script>window.location="profil.php"</script>';
                                }else{
                                    echo 'gagal '.mysqli_error($conn);
                                }
                            }
                            
                        }  
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- footer -->
    <footer>
        <div class="container">
            <small>&copy; 2024 - Muhamad Ilyas Shihabudin.</small>
        </div>
    </footer>

    <!-- Tautan untuk mengarahkan ke bagian "Ubah password" -->
    <a id="ubah-password"></a>
</body>
</html>
