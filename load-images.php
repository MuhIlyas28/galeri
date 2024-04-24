<?php
// Sertakan file koneksi database
require_once 'db.php';

// Periksa apakah parameter category_id dikirimkan melalui metode GET
if(isset($_GET['category_id'])) {
    // Ambil nilai category_id dari parameter GET
    $category_id = $_GET['category_id'];

    // Query untuk mengambil gambar berdasarkan kategori yang dipilih
    $query = "SELECT * FROM tb_image WHERE image_status = 1 AND category_id = $category_id ORDER BY image_id DESC LIMIT 100";
    $result = mysqli_query($conn, $query);

    // Periksa apakah query berhasil dieksekusi
    if(mysqli_num_rows($result) > 0) {
        // Tampilkan gambar-gambar yang ditemukan
        while($row = mysqli_fetch_assoc($result)) {
            echo '<a href="tampilan-image.php?id=' . $row['image_id'] . '" class="image-link">';
            echo '<div class="col-4">';
            echo '<div class="image-container">';
            echo '<img src="foto/' . $row['image'] . '" alt="' . $row['image_name'] . '" />';
            echo '<div class="overlay">';
            echo '<div class="text">' . substr($row['image_name'], 0, 30) . '</div>';
            echo '</div>';
            echo '</div>';
            echo '<p class="admin">Nama User : ' . $row['admin_name'] . '</p>';
            echo '<p class="date">' . $row['date_created'] . '</p>';
            echo '</div>';
            echo '</a>';
        }
    } else {
        // Jika tidak ada gambar yang ditemukan, tampilkan pesan
        echo '<p>Foto tidak ada</p>';
    }
} else {
    // Jika parameter category_id tidak ditemukan, tampilkan pesan kesalahan
    echo 'Parameter category_id tidak ditemukan';
}
?>
