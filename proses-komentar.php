<?php
include 'db.php';

if (isset($_POST['submit'])) {
    $image_id = $_POST['image_id'];
    $comment = $_POST['comment'];

    // Insert comment into database
    $sql = "INSERT INTO tb_image_comments (image_id, comment) VALUES ('$image_id', '$comment')";
    if (mysqli_query($conn, $sql)) {
        header("Location: detail-image.php?id=$image_id"); // Redirect back to detail page after submitting comment
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    header("Location: detail-image.php"); // Redirect back to detail page if form not submitted
    exit();
}
?>
