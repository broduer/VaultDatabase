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
                if ($result = $mysqli_d->query("SELECT id, timestamp, author, title, content FROM blog_posts ORDER BY timestamp DESC LIMIT 1")) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_object()) {
                ?>
                            <h4><?php echo $row->title ?></h4>
                            <p>Written By:</p>
                            <img src='https://crafatar.com/avatars/<?php echo $row->author ?>?size=24&overlay'>
                            <a href="../?view=user&user=<?php echo MojangAPI::getUsername($row->author) ?>">
                                <?php echo MojangAPI::getUsername($row->author) ?>
                            </a>
                            <br>
                            <i><?php echo secondsToDate($row->timestamp, $timezone, true) ?></i>
                            <p><?php echo $row->content ?></p>
                            <a href="../?blog=view&id=<?php echo $row->id?>">Read More...</a>
                <?php
                        }
                    } else {
                        echo "";
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
    </div>
</div>