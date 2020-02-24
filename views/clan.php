<?php
$clan = htmlspecialchars($_GET['clan']); ?>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <h1 class="text-center">Clan Information</h1>
    </div>
    <div class="col-md-3">
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3" align="center">
        <div class="info-pfp">
            <h2><?php echo $clan ?></h2>
            <?php
            if ($result = $mysqli_c->query("SELECT description FROM clans WHERE name = '$clan'")) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_object();
                    echo $row->description;
                }
            }
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <h4>Members</h4>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Rank</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result = $mysqli_c->query("SELECT player, rank FROM playerClans WHERE clan = '$clan' ORDER BY FIELD(rank,'Owner','Admin','Member')")) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_object()) {
                            $username = MojangAPI::getUsername($row->player);
                            echo "<tr>";
                            echo "<td><img src='https://crafatar.com/avatars/" . $row->player . "?size=24&overlay'> <a href='?view=user&user=" . $row->player . "'>$username</a></td>";
                            echo "<td>" . ucfirst($row->rank) . "</td>";
                            echo "</tr>";
                        }
                    } else { ?>
                        <script>
                            window.location.replace("http://vaultmc.net/?search=");
                        </script>
                <?php }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <h4>Statistics</h4>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Level</th>
                    <th scope="col">Experience</th>
                    <th scope="col">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result = $mysqli_c->query("SELECT level, experience, balance FROM clans WHERE name = '$clan'")) {
                    $row = $result->fetch_object();
                    echo "<tr>";
                    echo "<td>" . $row->level . "/60</td>";
                    echo "<td>" . $row->experience . "</td>";
                    echo "<td>$" . $row->balance . "</td>";
                    echo "</tr>";
                } else { ?>
                    <script>
                        window.location.replace("http://vaultmc.net/?search=");
                    </script>
                <?php }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
    </div>
</div>
</div>