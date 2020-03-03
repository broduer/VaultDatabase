<?php
require 'vendor/autoload.php';
$uuid = htmlspecialchars($_GET['user']);
$full_uuid = MojangAPI::formatUuid($uuid);
$username = MojangAPI::getUsername($full_uuid);
if ($uuid == null || $username == "CONSOLE") { ?>
    <script>
        window.location.replace("http://vaultmc.net/?page=home&alert=invalid-user");
    </script>
<?php }
$full_uuid = MojangAPI::formatUuid($uuid);

if (isset($_SESSION["loggedin"])) {
    if ($_SESSION["role"] == "admin") {
        $admin = true;
    } else if ($_SESSION["role"] == "moderator") {
        $mod = true;
    }
}

?>
<br>
<div class="row">
    <div class="col-md-12">
        <h1 class="text-center">User Information</h1>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3" align="center">
        <div class="info-pfp">
            <h3><?php echo $username ?></h3>
            <img alt="<?php echo $username ?>" src=" https://crafatar.com/renders/body/<?php echo $uuid ?>?overlay" style="padding:10px" />
            <?php
            if ($result = $mysqli_d->query("SELECT discord_id FROM players WHERE uuid = '$full_uuid'")) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {

                        if (!$row->discord_id == 0) {
                            $client = new GuzzleHttp\Client();
                            $res = $client->get('https://discordapp.com/api/v6/users/' . $row->discord_id, [
                                'headers' =>  [
                                    'Authorization' => 'Bot ' . $bot_token
                                ]
                            ]);
                            $obj = json_decode($res->getBody());
                            // clean this up soon!!
                            echo "<h5>
                            <span style=\"font-size: 1em; background-color:#7289DA;\">
                            <i class=\"fab fa-discord\" aria-hidden=\"true\"></i>
                            " . $obj->username . "#" . $obj->discriminator . "</span></h5>";
                        } else {
                            echo "";
                        }
                    }
                } else {
                    echo "";
                }
            }
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <div class="info">
            <br>
            <?php
            if ($result = $mysqli_d->query("SELECT COUNT(*) AS logged_in_count FROM sessions WHERE uuid = '$full_uuid'")) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $logged_in_count = $row->logged_in_count;
                    }
                } else {
                    $logged_in_count = "An Error has occured. Please contact an Administrator.";
                }
            }
            ?>
            <h4>Times Logged In <span class="badge badge-secondary"><?php echo $logged_in_count ?></span></h4>
            <br>
            <?php
            if ($result = $mysqli_d->query("SELECT AVG(duration) AS average_duration FROM sessions WHERE uuid = '$full_uuid'")) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        $average_duration = secondsToTime($row->average_duration / 1000);
                    }
                } else {
                    $average_duration = "An Error has occured. Please contact an Administrator.";
                }
            }
            if ($average_duration == null) {
                $average_duration = "<i>This player has not joined  <br>since sessions were implemented.</i>";
            }
            ?>
            <h4>Average Session Length <span class="badge badge-secondary"><?php echo $average_duration ?></span></h4>
            <br>
            <?php
            if ($result = $mysqli_d->query("SELECT rank FROM players WHERE uuid = '$full_uuid'")) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {
                        switch ($row->rank) {
                            case "admin":
                                $is_staff = true;
                                break;
                            case "moderator":
                                $is_staff = true;
                                break;
                            default:
                                $is_staff = false;
                                break;
                        }
                    }
                } else {
                    echo "<tr align=\"center\">
                            <td colspan=\"2\"><i>No Rank Data</i></td>
                            </tr>";
                }
            }
            ?>
            <h4>Is Staff <?php echo (($is_staff) ? "<span class=\"badge badge-success\">Yes</span>" : "<span class=\"badge badge-danger\">No</span>") ?></h4>
            <br>
            <?php
            if ($result = $mysqli_d->query("SELECT timezone FROM web_accounts WHERE uuid = '$full_uuid'")) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_object()) {

                        if (!$row->timezone == 0) {
                            $web_account = true;
                        } else {
                            // used when they have a token but not web account
                            $web_account = false;
                        }
                    }
                } else {
                    // this will be used when they havent made a token, or web account
                    $web_account = false;
                }
            }
            ?>
            <h4>Has Web Account <?php echo (($web_account) ? "<span class=\"badge badge-success\">Yes</span>" : "<span class=\"badge badge-danger\">No</span>") ?></h4>
        </div>
    </div>

    <div class="col-md-6">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-general-tab" data-toggle="tab" href="#nav-general" role="tab" aria-controls="nav-general" aria-selected="true">General</a>
                <a class="nav-item nav-link" id="nav-punishments-tab" data-toggle="tab" href="#nav-punishments" role="tab" aria-controls="nav-punishments" aria-selected="false">Punishments</a>
                <a class="nav-item nav-link" id="nav-clans-tab" data-toggle="tab" href="#nav-clans" role="tab" aria-controls="nav-clans" aria-selected="false">Clans</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-general" role="tabpanel" aria-labelledby="nav-general-tab">
                <br>
                <?php
                if ($result = $mysqli_d->query("SELECT firstseen, lastseen, playtime, rank, ip, token FROM players WHERE uuid = '$full_uuid'")) {
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_object();

                        $ls_since = time() - $row->lastseen / 1000;
                ?>
                        <h4>UUID:</h4>
                        <p><?php echo $full_uuid ?></p>
                        <h4>First Seen: </h4>
                        <p><?php echo secondsToDate($row->firstseen / 1000, $timezone, true); ?></p>
                        <h4>Last Seen: </h4>
                        <p><?php echo secondsToDate($row->lastseen / 1000, $timezone, true); ?>
                            (<?php echo secondsToTime($ls_since) ?> ago)</p>
                        <h4>Playtime: </h4>
                        <p><?php echo secondsToTime($row->playtime / 20); ?></p>
                        <h4>Rank: </h4>
                        <p><?php echo ucfirst($row->rank); ?></p>
                        <?php if (isset($admin) || (isset($_SESSION["loggedin"]) && $_SESSION["uuid"] == $full_uuid)) { ?>
                            <hr>
                            <?php if ($row->token != null) { ?>
                                <h4>Token: </h4>
                                <p><?php echo $row->token ?></p>
                        <?php }
                        }
                    } else { ?>
                        <script>
                            window.location.replace("http://vaultmc.net/?page=home&alert=invalid-user");
                        </script>
                    <?php }
                    if ((isset($admin) || isset($mod)) && $result->num_rows > 0) { ?>
                        <h4>Latest IP: </h4>
                        <?php echo "<a href='https://ipapi.co/" . $row->ip . "' target=\"_blank\">$row->ip</a>" ?>
                        <br>
                        <br>
                        <h4>Possible Alts: </h4>
                        <i>Based off their latest IP address.</i>
                        <table class="stats">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Last Seen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result = $mysqli_d->query("SELECT uuid, username, lastseen FROM players WHERE ip = '$row->ip' AND uuid != '$uuid' ORDER BY username ASC")) {
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_object()) {
                                            echo "<tr>";
                                            echo "<td><img src='https://crafatar.com/avatars/" . $row->uuid . "?size=24&overlay'> <a href='?view=user&user=" . $row->uuid . "'>$row->username</a></td>";
                                            echo "<td>" . secondsToDate($row->lastseen / 1000, $timezone, true) . "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <br>
                        <h4>IP History: </h4>
                        <table class="stats">
                            <thead>
                                <tr>
                                    <th>IP</th>
                                    <th>First Used</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result = $mysqli_d->query("SELECT DISTINCT ip, ANY_VALUE(start_time) AS start_time FROM sessions WHERE uuid = '$full_uuid' GROUP BY ip ORDER BY start_time DESC")) {
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_object()) {
                                            echo "<tr>";
                                            echo "<td><a href='https://ipapi.co/" . $row->ip . "' target=\"_blank\">$row->ip</a></td>";
                                            echo "<td>" . secondsToDate($row->start_time / 1000, $timezone, true) . "</td>";
                                            echo "</tr>";
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="tab-pane fade" id="nav-punishments" role="tabpanel" aria-labelledby="nav-punishments-tab">
                <br>
                <h4>Kicks</h4>
                <table class="stats">
                    <thead>
                        <tr>
                            <th>Issuer</th>
                            <th>Reason</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime FROM kicks WHERE uuid = '$full_uuid' ORDER BY executionTime DESC")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_object()) {
                                    $actor = MojangAPI::getUsername($row->actor);

                                    echo "<tr>";
                                    echo "<td><img src='https://crafatar.com/avatars/" . $row->actor . "?size=24&overlay'> <a href='?view=user&user=" . $row->actor . "'>$actor</a></td>";
                                    echo "<td>" . $row->reason . "</td>";
                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                    echo "</tr>";
                                }
                            } 
                        } else {
                            echo "Error: " . $mysqli_p->error;
                        }
                        ?>
                    </tbody>
                </table>
                <br>
                <h4>Bans</h4>
                <table class="stats">
                    <thead>
                        <tr>
                            <th>Issuer</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, status FROM bans WHERE uuid = '$full_uuid'")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_object()) {
                                    $actor = MojangAPI::getUsername($row->actor);
                                    $status = null;

                                    if ($row->status) {
                                        $status = "<span class=\"badge badge-danger\">Banned</span>";
                                    } else {
                                        $status = "<span class=\"badge badge-success\">Pardoned</span>";
                                    }
                                    echo "<tr>";
                                    echo "<td><img src='https://crafatar.com/avatars/" . $row->actor . "?size=24&overlay'> <a href='?view=user&user=" . $row->actor . "'>$actor</a></td>";
                                    echo "<td>" . $row->reason . "</td>";
                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                    echo "<td>" . $status . "</td>";
                                    echo "</tr>";
                                }
                            } 
                        } else {
                            echo "Error: " . $mysqli_p->error;
                        }
                        ?>
                    </tbody>
                </table>
                <br>
                <h4>Tempbans</h4>
                <table class="stats">
                    <thead>
                        <tr>
                            <th>Issuer</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Expiry</th>
                            <th>Length</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, expiry FROM tempbans WHERE uuid = '$full_uuid' ORDER BY executionTime DESC")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_object()) {
                                    $actor = MojangAPI::getUsername($row->actor);

                                    if (time() - $row->executionTime > 0) {
                                        $status = "<span class=\"badge badge-success\">Not Banned</span>";
                                    } else {
                                        $status = "<span class=\"badge badge-danger\">Banned</span>";
                                    }
                                    echo "<tr>";
                                    echo "<td><img src='https://crafatar.com/avatars/" . $row->actor . "?size=24&overlay'> <a href='?view=user&user=" . $row->actor . "'>$actor</a></td>";
                                    echo "<td>" . $row->reason . "</td>";
                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                    echo "<td>" . secondsToDate($row->expiry, $timezone, true) . "</td>";
                                    echo "<td>" . secondsToTime($row->expiry - $row->executionTime) . "</td>";
                                    echo "<td>" . $status . "</td>";
                                    echo "</tr>";
                                }
                            }
                        } else {
                            echo "Error: " . $mysqli_p->error;
                        }
                        ?>
                    </tbody>
                </table>
                <br>
                <h4>Mutes</h4>
                <table class="stats">
                    <thead>
                        <tr>
                            <th>Issuer</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, status FROM mutes WHERE uuid = '$full_uuid'")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_object()) {
                                    $actor = MojangAPI::getUsername($row->actor);
                                    $status = null;

                                    if ($row->status) {
                                        $status = "<span class=\"badge badge-success\">Pardoned</span>";
                                    } else {
                                        $status = "<span class=\"badge badge-danger\">Muted</span>";
                                    }
                                    echo "<tr>";
                                    echo "<td><img src='https://crafatar.com/avatars/" . $row->actor . "?size=24&overlay'> <a href='?view=user&user=" . $row->actor . "'>$actor</a></td>";
                                    echo "<td>" . $row->reason . "</td>";
                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                    echo "<td>" . $status . "</td>";
                                    echo "</tr>";
                                }
                            } 
                        } else {
                            echo "Error: " . $mysqli_p->error;
                        }
                        ?>
                    </tbody>
                </table>
                <br>
                <h4>Tempmutes</h4>
                <table class="stats">
                    <thead>
                        <tr>
                            <th>Issuer</th>
                            <th>Reason</th>
                            <th>Date</th>
                            <th>Expiry</th>
                            <th>Length</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, expiry FROM tempmutes WHERE uuid = '$full_uuid' ORDER BY executionTime DESC")) {
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_object()) {
                                    $actor = MojangAPI::getUsername($row->actor);

                                    if (time() - $row->executionTime > 0) {
                                        $status = "<span class=\"badge badge-success\">Not Muted</span>";
                                    } else {
                                        $status = "<span class=\"badge badge-danger\">Muted</span>";
                                    }
                                    echo "<tr>";
                                    echo "<td><img src='https://crafatar.com/avatars/" . $row->actor . "?size=24&overlay'> <a href='?view=user&user=" . $row->actor . "'>$actor</a></td>";
                                    echo "<td>" . $row->reason . "</td>";
                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                    echo "<td>" . secondsToDate($row->expiry, $timezone, true) . "</td>";
                                    echo "<td>" . secondsToTime($row->expiry - $row->executionTime) . "</td>";
                                    echo "<td>" . $status . "</td>";
                                    echo "</tr>";
                                }
                            }
                        } else {
                            echo "Error: " . $mysqli_p->error;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="nav-clans" role="tabpanel" aria-labelledby="nav-clans-tab">
                <br>
                <?php
                if ($result = $mysqli_c->query("SELECT clan, rank FROM playerClans WHERE player = '$full_uuid'")) {
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_object();
                ?>
                        <h4>Clan:</h4>
                        <p>
                            <a href="../?view=clan&clan=<?php echo htmlspecialchars($row->clan) ?>"><?php echo htmlspecialchars($row->clan) ?></a>
                        </p>
                        <h4>Rank: </h4>
                        <p><?php echo ucfirst($row->rank) ?></p>
                <?php
                    } else {
                        echo "This player is not in a clan.";
                    }
                }
                ?>
            </div>
        </div>
    </div>