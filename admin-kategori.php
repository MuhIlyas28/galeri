<?php
session_start(); 
require_once 'db.php';

// Mengecek apakah pengguna sudah login, jika tidak, arahkan ke halaman login
if(!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true){
    header("Location: login.php");
    exit; // Pastikan untuk menambahkan exit setelah pengalihan header
}

// Menangani proses penghapusan kategori
if(isset($_GET['id'])){
    $category_id = $_GET['id'];
    
    // Periksa apakah kategori memiliki gambar terkait
    $check_images = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tb_image WHERE category_id = '$category_id'");
    $images_count = mysqli_fetch_assoc($check_images)['total'];
    
    if($images_count > 0) {
        // Kategori memiliki gambar terkait, tampilkan pesan kesalahan
        $_SESSION['hapus_sukses'] = false;
        $_SESSION['hapus_error'] = "Kategori tidak dapat dihapus karena memiliki gambar terkait.";
    } else {
        // Kategori tidak memiliki gambar terkait, lanjutkan dengan penghapusan
        $delete_category = mysqli_query($conn, "DELETE FROM tb_category WHERE category_id = '$category_id'");
        
        if($delete_category) {
            // Hapus kategori berhasil, hapus juga gambar-gambar terkait (jika ada)
            $delete_images = mysqli_query($conn, "DELETE FROM tb_image WHERE category_id = '$category_id'");
            
            // Set notifikasi berhasil
            $_SESSION['hapus_sukses'] = true;
        } else {
            // Set notifikasi gagal
            $_SESSION['hapus_sukses'] = false;
        }
    }
}

$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - Kategori</title>
<link rel="stylesheet" type="text/css" href="css/admin-kategori.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <div class="container">
            <h1><a href="admin.php">Halaman Admin - Kategori</a></h1>
            <nav>
                <ul>
                    <li><a href="admin-gallery.php">Gallery</a></li>
                    <li><a href="keluar.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="section">
    <div class="container">
        <h3>Data Kategori</h3>
        <div class="box">
            <p><a href="tambah-kategori.php" class="btn tambah">Tambah Data</a></p>
            <?php
            // Tampilkan pesan kesalahan jika ada
            if(isset($_SESSION['hapus_error'])) {
                echo '<p style="color: red;">' . $_SESSION['hapus_error'] . '</p>';
                unset($_SESSION['hapus_error']);
            }
            ?>
            <table border="1" cellspacing="0" class="table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $kategori = mysqli_query($conn, "SELECT * FROM tb_category ORDER BY category_id DESC");
                    if(mysqli_num_rows($kategori) > 0){
                        $no = 1;
                        while($k = mysqli_fetch_array($kategori)){
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $k['category_name'] ?></td>
                            <td>
                                <a href="edit-kategori.php?id=<?php echo $k['category_id'] ?>" class="btn edit">Edit</a>
                                <a href="admin-kategori.php?id=<?php echo $k['category_id'] ?>" class="btn hapus" onclick="return confirm('Yakin Ingin Hapus ?')">Hapus</a>
                            </td>
                        </tr>
                    <?php }}else{ ?>
                        <tr>
                            <td colspan="3">Tidak ada data</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<footer>
    <div class="container">
        <small>Copyright &copy; 2024 - Muhamad Ilyas Shihabudin.</small>
    </div>
</footer>

<?php
// Tampilkan notifikasi kategori berhasil dihapus
if(isset($_SESSION['hapus_sukses']) && $_SESSION['hapus_sukses'] === true) {
    echo '<script>alert("Kategori berhasil dihapus");</script>';
    unset($_SESSION['hapus_sukses']);
    echo '<script>window.location.href = "admin-kategori.php";</script>';
    exit; // Pastikan untuk keluar setelah pengalihan
}
?>
    
</body>
</html>
