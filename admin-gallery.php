<?php
session_start(); 
require_once 'db.php';

// Mengecek apakah pengguna sudah login, jika tidak, arahkan ke halaman login
if(!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true){
    header("Location: login.php");
    exit; // Pastikan untuk menambahkan exit setelah pengalihan header
}

// Menangani proses penghapusan galeri foto
if(isset($_GET['hapus_id'])){
    $image_id = $_GET['hapus_id'];
    $delete_image = mysqli_query($conn, "DELETE FROM tb_image WHERE image_id = '$image_id'");
    
    if($delete_image) {
        // Set notifikasi berhasil
        $_SESSION['hapus_sukses_image'] = true;
    } else {
        // Set notifikasi gagal
        $_SESSION['hapus_sukses_image'] = false;
    }

    // Redirect back to the same page with the same pagination parameter
    header("Location: admin-gallery.php?page=" . $_GET['page']);
    exit;
}

// Mengatur pagination
$results_per_page = 10;
$sql = "SELECT COUNT(*) AS total FROM tb_image";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_pages = ceil($row["total"] / $results_per_page);

// Menyimpan nilai halaman saat ini dalam session
if (!isset($_GET['page']) && !isset($_SESSION['current_page'])) {
    $page = 1;
    $_SESSION['current_page'] = $page;
} else {
    $page = isset($_GET['page']) ? $_GET['page'] : $_SESSION['current_page'];
}

$this_page_first_result = ($page-1) * $results_per_page;

$kontak = mysqli_query($conn, "SELECT admin_telp, admin_email, admin_address FROM tb_admin WHERE admin_id = 2");
$a = mysqli_fetch_object($kontak);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel - Gallery Foto</title>
<link rel="stylesheet" type="text/css" href="css/admin-gallery.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <header>
        <div class="container">
            <h1><a href="admin-gallery.php">Halaman Admin - Gallery Foto</a></h1>
            <nav>
                <ul>
                    <li><a href="admin-kategori.php">Kategori</a></li>
                    <li><a href="keluar.php">Logout</a></li>
                
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="section-gallery">
        <div class="container">
            <h3>Data Gallery Foto</h3>
            <div class="box-gallery">
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Album</th>
                            <th>Kategori</th>
                            <th>Nama Pengupload</th>
                            <th>Nama Foto</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = ($page - 1) * $results_per_page + 1;
                        if(isset($_SESSION['a_global']) && $_SESSION['a_global'] !== null){
                            $foto = mysqli_query($conn, "SELECT * FROM tb_image LIMIT $this_page_first_result, $results_per_page");
                            if (mysqli_num_rows($foto) > 0) {
                                while ($row = mysqli_fetch_array($foto)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $row['album'] ?></td>
                                    <td><?php echo $row['category_name'] ?></td>
                                    <td><?php echo $row['admin_name'] ?></td>
                                    <td><?php echo $row['image_name'] ?></td>
                                    <td><?php echo $row['image_description'] ?></td>
                                    <td><a href="foto/<?php echo $row['image'] ?>" target="_blank"><img src="foto/<?php echo $row['image'] ?>" width="100px"></a></td>
                                    <td><?php echo ($row['image_status'] == 0) ? 'Tidak Aktif' : 'Aktif'; ?></td>
                                    <td class="aksi">
                                        <div class="aksi-buttons">
                                            <a href="edit-image.php?id=<?php echo $row['image_id'] ?>&page=<?php echo $page ?>" class="btn edit">Edit</a>
                                            <a href="admin-gallery.php?hapus_id=<?php echo $row['image_id'] ?>&page=<?php echo $page ?>" class="btn hapus" onclick="return confirm('Yakin Ingin Hapus ?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="8">Tidak ada data</td>
                                </tr>
                        <?php }}else{ ?>
                                <tr>
                                    <td colspan="8">Sesi tidak diinisialisasi atau tidak ada</td>
                                </tr>
                        <?php } ?>
                        </tbody>
                </table>
                <div class="pagination">
                    <?php if($page > 1): ?>
                        <a href="admin-gallery.php?page=<?php echo ($page-1); ?>" class="prev"><i class="fas fa-chevron-left"></i></a>
                    <?php endif; ?>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="admin-gallery.php?page=<?php echo $i; ?>" <?php if($page==$i) echo 'class="active"'; ?>><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <?php if($page < $total_pages): ?>
                        <a href="admin-gallery.php?page=<?php echo ($page+1); ?>" class="next"><i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Muhamad Ilyas Shihabudin.</small>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>

<?php
if(isset($_SESSION['hapus_sukses_image']) && $_SESSION['hapus_sukses_image'] == true){
    echo '<script>alert("Foto berhasil dihapus");</script>';
    unset($_SESSION['hapus_sukses_image']); 
}
?>
