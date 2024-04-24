<?php
session_start();
include 'db.php';
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] != true) {
    echo '<script>window.location="login.php"</script>';
    exit; // tambahkan perintah exit untuk menghentikan eksekusi kode selanjutnya jika status login tidak benar
}

// Periksa jika ada notifikasi yang diset
if(isset($_SESSION['notif'])){
    $notif_status = $_SESSION['notif']['status'];
    $notif_message = $_SESSION['notif']['message'];
    
    // Hapus notifikasi dari session
    unset($_SESSION['notif']);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEB Galeri Foto</title>
    <link rel="stylesheet" type="text/css" href="css/data-image.css">
    <link rel="stylesheet" type="text/css" href="css/notif.css"> <!-- Tambahkan file notif.css -->
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
            <!-- Tampilkan notifikasi di sini, di bawah header -->
            <div id="notif" class="notif <?php echo isset($notif_status) ? 'show' : ''; ?>"><?php echo isset($notif_message) ? $notif_message : ''; ?></div>
            <h3>Data Galeri Foto</h3>
            <div class="box">
                <p><a href="tambah-image.php?source=data-image" class="btn tambah">Tambah Data</a></p>
                <table border="1" cellspacing="0" class="table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Album</th>
                            <th>Kategori</th>
                            <th>Nama User</th>
                            <th>Nama Foto</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if(isset($_SESSION['a_global']) && $_SESSION['a_global'] !== null){
                            $admin_id = $_SESSION['a_global']['admin_id'];
                            $foto = mysqli_query($conn, "SELECT * FROM tb_image WHERE admin_id = '$admin_id'");
                            if (mysqli_num_rows($foto) > 0) {
                                while ($row = mysqli_fetch_array($foto)) {
                        ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $row['album']; ?></td>
                                    <td><?php echo $row['category_name'] ?></td>
                                    <td><?php echo $row['admin_name'] ?></td>
                                    <td><?php echo $row['image_name'] ?></td>
                                    <td><?php echo $row['image_description'] ?></td>
                                    <td><a href="foto/<?php echo $row['image'] ?>" target="_blank"><img src="foto/<?php echo $row['image'] ?>" width="100px"></a></td>
                                    <td><?php echo ($row['image_status'] == 0) ? 'Tidak Aktif' : 'Aktif'; ?></td>
                                    <td class="aksi">
                                        <div class="aksi-buttons">
                                            <a href="edit-image.php?id=<?php echo $row['image_id'] ?>" class="btn edit">Edit</a>
                                            <a href="proses-hapus.php?idp=<?php echo $row['image_id'] ?>" class="btn hapus" onclick="return confirm('Yakin Ingin Hapus ?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="9">Tidak ada data</td> <!-- Sesuaikan jumlah kolom dengan penambahan kolom Album -->
                                </tr>
                        <?php }}else{ ?>
                                <tr>
                                    <td colspan="9">Sesi tidak diinisialisasi atau tidak ada</td> <!-- Sesuaikan jumlah kolom dengan penambahan kolom Album -->
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <div class="container">
            <small>Copyright &copy; 2024 - Muhamad Ilyas Shihabudin.</small>
        </div>
    </footer>

    <!-- JavaScript untuk mengatur notifikasi -->
    <script>
        // Tampilkan notifikasi
        window.onload = function() {
            var notif = document.getElementById('notif');
            if (notif.classList.contains('show')) {
                notif.style.display = 'block';
                setTimeout(function() {
                    notif.style.display = 'none';
                }, 3000); // Atur waktu notifikasi akan menghilang setelah 3 detik
            }
        };
    </script>
</body>

</html>