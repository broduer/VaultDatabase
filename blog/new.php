<?php
require 'vendor/autoload.php';

if (!isset($_SESSION["role"]) && ($_SESSION["role"] == "player")) {
    header("location: https://vaultmc.net?page=home&alert=no-permission");
}
$title_err = $content_err = $ping_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    }
    if (empty(trim($_POST["content"]))) {
        $content_err = "Please enter content.";
    }
    if (in_array($_POST["ping"], $ping_sel)) {
        $ping_err = "Invalid ping selection";
    } else {
        $Parsedown = new Parsedown();

        $post_time = time();
        $post_author = MojangAPI::formatUuid(MojangAPI::getUuid($_SESSION["username"]));
        $post_title = htmlspecialchars($_POST["title"]);
        $post_md_content = $_POST["content"];
        $post_html_content = $Parsedown->text($_POST["content"]);

        if ($_POST["ping"] == "") {
            $ping = "";
        } else {
            $ping = "@" . $_POST["ping"];
        }

        $sql = "INSERT INTO blog_posts (timestamp, author, title, md_content, html_content) VALUES ('$post_time', '$post_author', '$post_title', '$post_md_content', '$post_html_content')";

        $post_id = "SELECT auto_increment FROM information_schema.TABLES WHERE TABLE_SCHEMA = \"VaultMC_Data\" AND TABLE_NAME = \"blog_posts\"";

        if ($mysqli_d->query($sql) === TRUE) {
            if ($result = $mysqli_d->query($post_id)) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $webhook_content =
                            $ping
                            . "\n"
                            . ":loudspeaker: New blog post **" . $post_title . "** authored by *" . MojangAPI::getUsername($post_author) . "*"
                            . "\n"
                            . "https://vaultmc.net/?blog=view&id=" . intval($row->auto_increment - 1);

                        $webhookurl = $announcements_webhook;
                        $json_data = array(
                            'content' => "$webhook_content",
                            'avatar_url' => "https://crafatar.com/avatars/" . $post_author
                        );
                        $make_json = json_encode($json_data);

                        $ch = curl_init($webhookurl);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $make_json);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $response = curl_exec($ch);

                        header('Location: https://vaultmc.net/?page=home&alert=blog-posted');
                    }
                }
            } else {
                echo "Error: " . $post_id . "<br>" . $mysqli_d->error;
            }
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
        <h2 class="text-center">New Blog Post</h2>
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
                <textarea name="content" id="content" placeholder="Content"></textarea>
                <span style="color:red"><?php echo $content_err; ?></span>
                <br>
                <div class="row">
                    <div class="col-md-9">
                        <input type="radio" name="ping" value="">
                        <label for="none">None</label>
                        <input type="radio" name="ping" value="here" checked>
                        <label for="here">Here</label>
                        <input type="radio" name="ping" value="everyone">
                        <label for="everyone">Everyone</label>
                    </div>
                    <span style="color:red"><?php echo $ping_err; ?></span>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-3"></div>
</div>
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
    var simplemde = new SimpleMDE({
        element: document.getElementById("content"),
        status: false,
    });
</script>