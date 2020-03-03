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
    ?>
    <div class="col-md-3"></div>
    <div class="col-md-3">
        <table class="search">
            <thead>
                <tr>
                    <th scope="col">Players</a>
                        <i class="<?php echo $u_icon ?>">
                    </th>
                    <th scope="col">Rank</a>
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
                            echo "<td><img src='https://crafatar.com/avatars/" . $row['uuid'] . "?size=24&overlay'> <a href=https://vaultmc.net/?view=user&user=" . $row['uuid'] . ">" . $row['username'] . "</a></td>";
                            echo "<td>" . ucfirst($row['rank']) . "</td>";
                            echo "</tr>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <table class="search">
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
                            echo "<td><a href=https://vaultmc.net/?view=clan&clan=" . $row['name'] . ">" . $row['name'] . "</a></td>";
                            echo "</tr>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
    </div>
</div>