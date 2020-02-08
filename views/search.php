<div class="row">
    <div class="col-md-12">
        <h1 class="text-center">Search the Database</h1>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6" align="center" style="background-color: #303030; border-radius:10px; padding:10px;">
        <h3>Player & Clan Information</h3>
        <form action="?" method="get">
            <input type='hidden' name='view' value='search' />
            <div class="form-group">
                <label for="playername">Search for a player or clan below</label>
                <input type="text" class="form-control" id="playername" name="query" placeholder="Enter your query here.">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
    <div class="col-md-3"></div>
</div>
<br>
<div class="row">
    <?php
    if (empty($_GET['query'])) {
        $search = "";
    } else {
        $search = htmlspecialchars($_GET['query']);
    }
    $pdoQuery = "SELECT uuid, username, rank FROM players WHERE username LIKE ?";
    if (isset($_GET['order'])) {
        switch ($_GET['order']) {
            case "u-asc":
                $pdoQuery .= " ORDER BY username";
                $u_link = "u-desc";
                $u_icon = "fas fa-sort-up";
                $r_link = "r-asc";
                $r_icon = "fas fa-sort";
                break;
            case "u-desc":
                $pdoQuery .= " ORDER BY username DESC";
                $u_link = "u-asc";
                $u_icon = "fas fa-sort-down";
                $r_link = "r-asc";
                $r_icon = "fas fa-sort";
                break;
            case "r-asc":
                $pdoQuery .= " ORDER BY FIELD(rank,'Admin','Moderator','Trusted', 'Patreon', 'Member', 'Default')";
                $r_link = "r-desc";
                $r_icon = "fas fa-sort-up";
                $u_link = "u-asc";
                $u_icon = "fas fa-sort";
                break;
            case "r-desc":
                $pdoQuery .= " ORDER BY FIELD(rank,'Admin','Moderator','Trusted', 'Patreon', 'Member', 'Default') DESC";
                $r_link = "r-asc";
                $r_icon = "fas fa-sort-down";
                $u_link = "u-asc";
                $u_icon = "fas fa-sort";
                break;
            default:
                $pdoQuery .= " ORDER BY username";
                $u_link = "u-desc";
                $u_icon = "fas fa-sort-up";
                $r_link = "r-asc";
                $r_icon = "fas fa-sort";
        }
    } else {
        $pdoQuery .= " ORDER BY username";
        $u_link = "u-desc";
        $u_icon = "fas fa-sort-up";
        $r_link = "r-asc";
        $r_icon = "fas fa-sort";
    }
    ?>
    <div class="col-md-3"></div>
    <div class="col-md-3">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col"><a href="<?php echo stripUrlParam(currentUrl(), "order") . "&order=" . $u_link ?>">Players</a>
                        <i class="<?php echo $u_icon ?>">
                    </th>
                    <th scope="col"><a href="<?php echo stripUrlParam(currentUrl(), "order") . "&order=" . $r_link ?>">Rank</a>
                        <i class="<?php echo $r_icon ?>">
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $params = array("%$search%");
                $pdoResult = $pdo_d->prepare($pdoQuery);
                $pdoExec = $pdoResult->execute($params);

                if ($pdoExec) {
                    if ($search != null) {
                        echo "<h4>Usernames like: $search</h4>";
                    }
                    if ($pdoResult->rowCount() > 0) {
                        foreach ($pdoResult as $row) {
                            echo "<tr>";
                            echo "<td><img src='https://crafatar.com/avatars/" . $row['uuid'] . "?size=24&overlay'> <a href=https://database.vaultmc.net/?view=user&user=" . $row['uuid'] . ">" . $row['username'] . "</a></td>";
                            echo "<td>" . ucfirst($row['rank']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr align=\"center\">
                        <td colspan=\"2\"><i>No users to display<i></td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Clans</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($_GET['query'])) {
                    $search = "";
                } else {
                    $search = htmlspecialchars($_GET['query']);
                }
                $pdoQuery = "SELECT name FROM clans WHERE name LIKE ? AND system_clan <> 1 ORDER BY name";
                $params = array("%$search%");
                $pdoResult = $pdo_c->prepare($pdoQuery);
                $pdoExec = $pdoResult->execute($params);

                if ($pdoExec) {
                    if ($search != null) {
                        echo "<h4>Clans like: $search</h4>";
                    }
                    if ($pdoResult->rowCount() > 0) {
                        foreach ($pdoResult as $row) {
                            echo "<tr>";
                            echo "<td><a href=https://database.vaultmc.net/?view=clan&clan=" . $row['name'] . ">" . $row['name'] . "</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr align=\"center\">
                        <td><i>No clans to display<i></td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
    </div>
</div>