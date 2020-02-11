<?php if (isset($_SESSION["timezone"])) {
    $timezone = $_SESSION["timezone"];
} else {
    $timezone = "null";
} ?>
<div class="row">
    <div class="col-md-12">
        <h2 class="text-center">VaultMC News</h2>
    </div>
</div>
<div align="center">
    <?php
    if (isset($_SESSION["role"]) && $_SESSION["role"] == "admin") {
    ?>
        <button type="submit" class="btn btn-primary" onclick="window.location.href = '?blog=new';">New Post</button>
    <?php
    }
    ?>
</div>
<br>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="row" style="background-color:#303030; border-radius:10px; padding:10px;">
            <div class="col-md-12">
                <?php
                if ($result = $mysqli_d->query("SELECT id, timestamp, author, title, html_content FROM blog_posts WHERE status <> 1 ORDER BY timestamp DESC")) {
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
                        <?php
                        }
                    } else { ?>
                        <div class="row">
                            <div class="col-md-12" align="center">
                                <i>There are no blog posts! Hopefully the Admins get their act together.</i>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
    </div>
</div>