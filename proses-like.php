<?php
include 'db.php';

if (isset($_POST['submit'])) {
    $image_id = $_POST['image_id'];

    // Lakukan penanganan logika like di sini
    // Misalnya, Anda bisa menambahkan jumlah like pada database

    // Contoh: Tambahkan satu like pada gambar dengan id yang sesuai
    $sql = "UPDATE tb_image SET likes = likes + 1 WHERE image_id = '$image_id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: detail-image.php?id=$image_id"); // Redirect kembali ke halaman detail setelah like
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    header("Location: detail-image.php"); // Redirect kembali ke halaman detail jika tombol like tidak ditekan
    exit();
}
?>
