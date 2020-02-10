<?php
require 'vendor/autoload.php';

if (!isset($_SESSION["role"]) && (!$_SESSION["role"] == "Admin")) {
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: index.php');
    }
}
$title_err = $content_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    }

    if (empty(trim($_POST["content"]))) {
        $content_err = "Please enter content.";
    } else {
        $Parsedown = new Parsedown();

        $post_time = time();
        $post_author = MojangAPI::formatUuid(MojangAPI::getUuid($_SESSION["username"]));
        $post_title = htmlspecialchars($_POST["title"]);
        $post_md_content = $_POST["content"];
        $post_html_content = $Parsedown->text($_POST["content"]);

        $sql = "INSERT INTO blog_posts (timestamp, author, title, md_content, html_content) VALUES ('$post_time', '$post_author', '$post_title', '$post_md_content', '$post_html_content')";

        if ($mysqli_d->query($sql) === TRUE) {
            header('Location: index.php');
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli_d->error;
        }

        $mysqli_d->close();
    }
}


?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

<div class="row">
    <div class="col-md-12">
        <h2 class="text-center">New Blog Article</h2>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6" style="background-color: #303030; border-radius:10px; padding:10px;">
        <form action="" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                <span style="color:red"><?php echo $title_err; ?></span>
                <br>
                <textarea name="content" id="content"></textarea>
                <span style="color:red"><?php echo $content_err; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>
    </div>
    <div class="col-md-3"></div>
</div>
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
    var simplemde = new SimpleMDE({
        element: document.getElementById("content"),
    });
</script>