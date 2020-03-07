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
        <div class="form-group">
            <label for="searchBox">Search for a player or clan below</label>
            <input type="text" class="form-control" id="searchBox" name="searchBox" placeholder="Enter your query here.">
        </div>
    </div>
    <div class="col-md-3"></div>
</div>
<br>
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-3">
        <table class="search" id="playerTable">
            <thead>
                <tr>
                    <th scope="col">Players</a>
                    </th>
                    <th scope="col">Rank</a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result = $mysqli_d->query("SELECT uuid, username, rank FROM players ORDER BY username DESC")) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_object()) {
                            echo "<tr>";
                            echo "<td><img src='https://crafatar.com/avatars/" . $row->uuid . "?size=24&overlay'> <a href=https://vaultmc.net/?view=user&user=" . $row->uuid . ">" . $row->username . "</a></td>";
                            echo "<td>" . ucfirst($row->rank) . "</td>";
                            echo "</tr>";
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-3">
        <table class="search" id="clansTable">
            <thead>
                <tr>
                    <th scope="col">Clans</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result = $mysqli_c->query("SELECT name FROM clans WHERE system_clan <> 1 ORDER BY name")) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_object()) {
                            echo "<tr>";
                            echo "<td><a href=https://vaultmc.net/?view=clan&clan=" . $row->name . ">" . $row->name . "</a></td>";
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
<script>
    $(document).ready(function() {
        $("#playerTable").DataTable({
            "bInfo": false,
            "retreive": true,
            "dom": 't',
        });
        $("#clansTable").DataTable({
            "bInfo": false,
            "retreive": true,
            "dom": 't',
        });
    });
    $('#searchBox').keyup(function() {
        $("#playerTable").DataTable().search($(this).val()).draw();
        $("#clansTable").DataTable().search($(this).val()).draw();
    })
</script>