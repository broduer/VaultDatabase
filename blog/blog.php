<?php if (isset($_SESSION["timezone"])) {
    $timezone = $_SESSION["timezone"];
} else {
    $timezone = "null";
} ?>
<style>
    span.nobreak {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <h2 class="text-center">VaultMC News</h2>
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
                if ($result = $mysqli_d->query("SELECT id, timestamp, author, title, html_content FROM blog_posts ORDER BY timestamp DESC")) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_object()) {
                ?>
                            <div class="row">
                                <div class="col-md-9">
                                    <h4><?php echo $row->title ?></h4>
                                </div>
                                <div class="col-md-3">
                                    <img src='https://crafatar.com/avatars/<?php echo $row->author ?>?size=24&overlay'>
                                    <a href="../?view=user&user=<?php echo $row->author ?>">
                                        <?php echo MojangAPI::getUsername($row->author) ?>
                                    </a>
                                </div>
                            </div>
                            <i><?php echo secondsToDate($row->timestamp, $timezone, true) ?></i>
                            <hr>
                            <p><?php echo htmlspecialchars_decode(stripslashes($row->html_content)) ?></p>
                <?php
                        }
                    } else {
                        echo "<i>There are no blog posts! Hopefully the Admins get their act together.</i>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
    </div>
</div>