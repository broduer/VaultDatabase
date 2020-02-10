<?php
if (!isset($_SESSION["role"]) && (!$_SESSION["role"] == "admin")) {
    header('Location: index.php');
    return;
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

    $id = $_GET["id"];

    $sql = "UPDATE blog_posts SET status = 1 WHERE id = '$id'";

    if ($mysqli_d->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli_d->error;
    }
    $mysqli_d->close();
} else {
    header('Location: index.php');
}
