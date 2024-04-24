<?php
include 'db.php';

if(isset($_GET['idp'])){
    $image_id = $_GET['idp'];
    $foto = mysqli_query($conn, "SELECT image, admin_id FROM tb_image WHERE image_id = '$image_id'");
    $p = mysqli_fetch_object($foto);
    
    if($p){
        // Hapus file gambar dari server
        $image_path = './foto/'.$p->image;
        if(file_exists($image_path)) {
            unlink($image_path);
        }
        
        // Hapus data dari database
        $delete = mysqli_query($conn, "DELETE FROM tb_image WHERE image_id = '$image_id'");
        
        if($delete) {
            // Set notifikasi berhasil
            session_start();
            $_SESSION['hapus_sukses_image'] = true;
            // Notifikasi berhasil dihapus
            $_SESSION['pesan'] = "Foto berhasil dihapus.";
        } else {
            // Set notifikasi gagal
            session_start();
            $_SESSION['hapus_sukses_image'] = false;
            // Notifikasi gagal dihapus
            $_SESSION['pesan'] = "Gagal menghapus foto.";
        }
    }
}

// Kembali ke halaman yang sesuai setelah proses hapus
if(isset($_SERVER["HTTP_REFERER"])) {
    header("Location: " . $_SERVER["HTTP_REFERER"]); // Kembali ke halaman sebelumnya
} else {
    header("Location: index.php"); // Kembali ke halaman utama jika tidak ada referer
}
exit;
?>
