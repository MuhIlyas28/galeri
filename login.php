<?php
session_start();
if(isset($_SESSION['status_login']) && $_SESSION['status_login'] == true){
    if($_SESSION['a_global']['role'] == 'user'){
        header('Location: dashboard.php');
        exit(); // Pastikan keluar dari skrip setelah mengalihkan
    } else {
        header('Location: admin-kategori.php');
        exit(); // Pastikan keluar dari skrip setelah mengalihkan
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | Web Galeri Foto</title>
<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body id="bg-login">
    <div class="box-login">
        <h2>Login</h2>
        <form action="" method="POST">
            <input type="text" name="user" placeholder="Username" class="input-control" required> <!-- tambahkan required -->
            <input type="password" name="pass" placeholder="Password" class="input-control" required> <!-- tambahkan required -->
            <input type="submit" name="submit" value="Login" class="btn">
        </form>
        <?php
        if(isset($_POST['submit'])){
            // Cek apakah kedua input telah diisi
            if(empty($_POST['user']) || empty($_POST['pass'])){
                echo '<p style="color:red;">Silakan isi kedua kolom username dan password.</p>';
            } else {
                include 'db.php';

                // Validasi input
                $user = mysqli_real_escape_string($conn, $_POST['user']);
                $pass = mysqli_real_escape_string($conn, $_POST['pass']);

              // Query untuk mencari pengguna dengan username dan password yang cocok
                $query = mysqli_prepare($conn, "SELECT admin_id, username, role FROM tb_admin WHERE username = ? AND password = MD5(?)");
                mysqli_stmt_bind_param($query, "ss", $user, $pass);
                mysqli_stmt_execute($query);

                // Bind result
                mysqli_stmt_bind_result($query, $admin_id, $username, $role);

                // Store result
                mysqli_stmt_store_result($query);

                if(mysqli_stmt_num_rows($query) > 0){
                    mysqli_stmt_fetch($query);

                    // Simpan informasi admin dalam sesi
                    $_SESSION['status_login'] = true;
                    $_SESSION['a_global'] = [
                        'admin_id' => $admin_id,
                        'username' => $username,
                        'role' => $role
                    ];

                    // Simpan ID pengguna dalam sesi
                    $_SESSION['id'] = $admin_id;

                    // Alihkan ke halaman admin
                    if($role == 'user'){
                        header('Location: dashboard.php');
                        exit(); // Pastikan keluar dari skrip setelah mengalihkan
                    }else{
                        header('Location: admin-kategori.php');
                        exit(); // Pastikan keluar dari skrip setelah mengalihkan
                    }
                } else {
                    echo '<p style="color:red;">Username atau password Anda salah.</p>';
                }
            }
        }
        ?><br>
        <p>Belum punya akun? Daftar <a style="color:#00C;" href="registrasi.php">DISINI</a></p>
        <p>Atau kembali ke halaman utama <a style="color:#00C;" href="index.php">Kembali</a></p>
    </div>
</body>
</html>
