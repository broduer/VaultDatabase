<?php if (isset($_SESSION["timezone"])) {
    $timezone = $_SESSION["timezone"];
} else {
    $timezone = "null";
} ?>
<?php

$comment_err = $reply_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["comment"]) && empty(trim($_POST["comment"]))) {
        $comment_err = "Please enter a comment!";
    }
    if (isset($_POST["reply"]) && empty(trim($_POST["reply"]))) {
        $reply_err = "Please enter a reply!";
    }
    if (isset($_POST["comment"]) && !empty(trim($_POST["comment"]))) {

        $comment_post = $_GET["id"];
        $comment_time = time();
        $comment_author = MojangAPI::formatUuid(MojangAPI::getUuid($_SESSION["username"]));
        $comment_content = htmlspecialchars($_POST["comment"]);

        $sql = "INSERT INTO blog_comments (post_id, timestamp, author, content) VALUES ('$comment_post', '$comment_time', '$comment_author', '$comment_content')";

        if ($mysqli_d->query($sql) === TRUE) {
            header('Location: ' . currentUrl() . '&alert=comment-posted');
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli_d->error;
        }
    }
    if (isset($_POST["reply"]) && !empty(trim($_POST["reply"]))) {

        $reply_post = $_GET["id"];
        $replied_id = $_POST["reply_id"];
        $reply_time = time();
        $reply_author = MojangAPI::formatUuid(MojangAPI::getUuid($_SESSION["username"]));
        $reply_content = htmlspecialchars($_POST["reply"]);

        $sql = "INSERT INTO blog_comments (post_id, replied_id, timestamp, author, content) VALUES ('$reply_post', '$replied_id', '$reply_time', '$reply_author', '$reply_content')";

        if ($mysqli_d->query($sql) === TRUE) {
            header('Location: ' . currentUrl() . '&alert=reply-posted');
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli_d->error;
        }
    }
}
?>
<div class="row">
    <div class="col-md-12">
        <h2 class="text-center">View Blog Post</h2>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="row" style="background-color:#303030; border-radius:10px; padding:10px;">
            <div class="col-md-12">
                <?php
                if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

                    if ($result = $mysqli_d->query("SELECT id, timestamp, author, title, html_content FROM blog_posts WHERE id = " . $_GET["id"] . "")) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_object()) {
                ?>
                                <div class="row">
                                    <div class="col-md-9">
                                        <h3><?php echo $row->title ?></h3>
                                    </div>
                                    <div class="col-md-3">
                                        <img src='https://crafatar.com/avatars/<?php echo $row->author ?>?size=24&overlay'>
                                        <a href="../?view=user&user=<?php echo $row->author ?>">
                                            <?php echo MojangAPI::getUsername($row->author) ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <i><?php echo secondsToDate($row->timestamp, $timezone, true) ?></i>
                                    </div>
                                    <div class="col-md-2">
                                        <?php
                                        if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
                                        ?>
                                            <div align="right">
                                                <i class="fas fa-edit"></i><a href="?blog=edit&id=<?php echo $row->id ?>">Edit</a>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-2">
                                        <?php
                                        if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
                                        ?>
                                            <div align="left">
                                                <i class="fas fa-trash-alt"></i><a href="?blog=delete&id=<?php echo $row->id ?>">Delete</a>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <hr>
                                <p><?php echo htmlspecialchars_decode(stripslashes($row->html_content)) ?></p>
                                <hr>
                    <?php
                            }
                        } else {
                            header('Location: https://vaultmc.net/?page=home&alert=blog-invalid-id');
                        }
                    }
                    ?>
                    <h3>Comments</h3>
                    <?php
                    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === TRUE) {
                    ?>
                        <form action="" method="post">
                            <div class="row">
                                <div class="form-group col-md-9">
                                    <textarea name="comment" placeholder="Add a comment" style="min-width: 100%"></textarea>
                                    <span style="color:red"><?php echo $comment_err; ?></span>
                                    <br>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Comment</button>
                                </div>
                            </div>
                        </form>
                    <?php
                    }
                    ?>
                    <?php if ($result = $mysqli_d->query("SELECT id, timestamp, author, content FROM blog_comments WHERE post_id = " . htmlspecialchars($_GET["id"]) . " AND replied_id IS NULL ORDER BY timestamp DESC")) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_object()) {
                    ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-8">
                                        <img src='https://crafatar.com/avatars/<?php echo $row->author ?>?size=24&overlay'>
                                        <a href="../?view=user&user=<?php echo $row->author ?>">
                                            <?php echo MojangAPI::getUsername($row->author) ?>
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <i><?php echo secondsToDate($row->timestamp, $timezone, true) ?></i>
                                    </div>
                                </div>
                                <p><?php echo $row->content ?></p>
                                <?php
                                if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === TRUE) {
                                ?>
                                    <form action="" method="post">
                                        <div class="row">
                                            <div class="col-md-1">
                                            </div>
                                            <div class="form-group col-md-8">
                                                <input type="hidden" name="reply_id" value="<?php echo $row->id ?>">
                                                <textarea name="reply" placeholder="Add a reply" style="min-width: 100%"></textarea>
                                                <span style="color:red"><?php echo $reply_err; ?></span>
                                                <br>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary">Reply</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php } ?>
                                <?php if ($replies = $mysqli_d->query("SELECT id, replied_id, timestamp, author, content FROM blog_comments WHERE replied_id = " . $row->id . " ORDER BY timestamp DESC")) {
                                    if ($replies->num_rows > 0) {
                                        while ($reply = $replies->fetch_object()) {
                                ?>
                                            <div class="row">
                                                <div class="col-md-1">
                                                </div>
                                                <div class="col-md-7">
                                                    <img src='https://crafatar.com/avatars/<?php echo $reply->author ?>?size=24&overlay'>
                                                    <a href="../?view=user&user=<?php echo $reply->author ?>">
                                                        <?php echo MojangAPI::getUsername($reply->author) ?>
                                                    </a>
                                                    <p><?php echo $reply->content ?></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <i><?php echo secondsToDate($reply->timestamp, $timezone, true) ?></i>
                                                </div>
                                            </div>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            <?php
                            }
                        } else {
                            ?>
                            <i>No Comments.</i>
                <?php
                        }
                    }
                } else {
                    header('Location: https://vaultmc.net/?page=home&alert=blog-invalid-id');
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
    </div>
</div>