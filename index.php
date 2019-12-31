<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="https://www.vaultmc.net/favicon.ico" type="image/png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>VaultMC - Database</title>
</head>
<body>

<style>
    div.info {
        position: -webkit-sticky;
        position: sticky;
        top: 17px;
        background-color: #DEE2E6;
        border-radius: 10px;
    }
</style>

<div class="container-fluid">
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require('mojangAPI/mojang-api.class.php');
    include('config.php');
    include 'includes/navbar.php';
    include 'functions.php';
    ?>
    <?php if (isset($_SESSION["timezone"])) {
        $timezone = $_SESSION["timezone"];
    } else {
        $timezone = "null";
    } ?>
    <br/>
    <!-- search -->
    <?php if (isset($_GET["search"])) { ?>
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">Search for a Player</h1>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6" align="center" style="background-color:#DEE2E6; border-radius:10px; padding:10px;">
                <h3>Player & Clan Information</h3>
                <form action="?" method="get">
                    <div class="form-group">
                        <label for="playername">Search for a player or clan below</label>
                        <input type="text" class="form-control" id="playername" name="search"
                               placeholder="Enter your query here.">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Players</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $search = "";
                    try {
                        $pdoConnect = new PDO("mysql:host=localhost;dbname=VaultMC_Data", "tadhg", "Stjames123b!");
                    } catch (PDOException $exc) {
                        echo $exc->getMessage();
                        exit();
                    }
                    $search = htmlspecialchars($_GET['search']);
                    $pdoQuery = "SELECT uuid, username FROM players WHERE username LIKE ? ORDER BY username";
                    $params = array("%$search%");
                    $pdoResult = $pdoConnect->prepare($pdoQuery);
                    $pdoExec = $pdoResult->execute($params);

                    if ($pdoExec) {
                        if ($search != null) {
                            echo "<h4>Usernames like: $search</h4>";
                        }
                        if ($pdoResult->rowCount() > 0) {
                            foreach ($pdoResult as $row) {
                                echo "<tr>";
                                echo "<td><img src='https://crafatar.com/avatars/" . $row['uuid'] . "?size=24&overlay'> <a href=https://database.vaultmc.net/?user=" . $row['username'] . ">" . $row['username'] . "</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo 'No users to display!';
                        }
                    } else {
                        echo 'ERROR Data Not Inserted';
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
                    $search = "";
                    try {
                        $pdoConnect = new PDO("mysql:host=localhost;dbname=VaultMC_Clans", "tadhg", "Stjames123b!");
                    } catch (PDOException $exc) {
                        echo $exc->getMessage();
                        exit();
                    }
                    $search = htmlspecialchars($_GET['search']);
                    $pdoQuery = "SELECT name FROM clans WHERE name LIKE ? ORDER BY name";
                    $params = array("%$search%");
                    $pdoResult = $pdoConnect->prepare($pdoQuery);
                    $pdoExec = $pdoResult->execute($params);

                    if ($pdoExec) {
                        if ($search != null) {
                            echo "<h4>Clans like: $search</h4>";
                        }
                        if ($pdoResult->rowCount() > 0) {
                            foreach ($pdoResult as $row) {
                                echo "<tr>";
                                echo "<td><a href=https://database.vaultmc.net/?clan=" . $row['name'] . ">" . $row['name'] . "</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo 'No clans to display!';
                        }
                    } else {
                        echo 'ERROR Data Not Inserted';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-3">
            </div>
        </div>
        <!-- user -->
    <?php } else if (isset($_GET["user"])) { ?>
        <?php
        $username = htmlspecialchars($_GET['user']);
        $uuid = MojangAPI::getUuid($username);
    if ($uuid == null || $username == "CONSOLE") { ?>
        <script>
            window.location.replace("http://database.vaultmc.net/?search=");
        </script>
    <?php }
    $full_uuid = MojangAPI::formatUuid($uuid);
    ?>
        <br/>
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-9">
                <h1 class="text-center">User Information</h1>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-md-3" align="center">
                <div class="info">
                    <h2><?php echo $username ?></h2>
                    <img alt="<?php echo $username ?>"
                         src=" https://crafatar.com/renders/body/<?php echo $uuid ?>?overlay" style="padding:10px"/>
                </div>
            </div>
            <div class="col-md-9">
                <div class="bs-example">
                    <div class="accordion" id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingFour">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                                            data-target="#collapseStats">General
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseStats" class="collapse show" aria-labelledby="headingStats"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <?php
                                    if ($result = $mysqli_d->query("SELECT firstseen, lastseen, playtime, rank, ip, token FROM players WHERE uuid = '$full_uuid'")) {
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_object();

                                        $pt_ticks = $row->playtime;
                                        $pt_secs = $pt_ticks / 20;
                                        $current_time = time();
                                        $ls_since = $current_time - $row->lastseen/1000;
                                        ?>
                                        <h4>UUID:</h4>
                                        <p><?php echo $full_uuid ?></p>
                                        <h4>First Seen: </h4>
                                        <p><?php secondsToDate($row->firstseen/1000, $timezone); ?></p>
                                        <h4>Last Seen: </h4>
                                        <p><?php secondsToDate($row->lastseen/1000, $timezone); ?>
                                            (<?php echo secondsToTime($ls_since) ?> ago)</p>
                                        <h4>Playtime: </h4>
                                        <p><?php echo secondsToTime($pt_secs); ?></p>
                                        <h4>Rank: </h4>
                                        <p><?php echo ucfirst($row->rank); ?></p>
                                  <?php if (isset($_SESSION["loggedin"]) && ($_SESSION["role"] == "admin")) { ?>
                                        <hr>
                                        <?php if ($row->token != null) { ?>
                                        <h4>Token: </h4>
                                        <p><?php echo $row->token ?></p>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php } else { ?>
                                        <script>
                                            window.location.replace("http://database.vaultmc.net/?search=");
                                        </script>
                                    <?php } ?>
                                    <?php if (isset($_SESSION["loggedin"]) && ($_SESSION["role"] == "admin") && ($result->num_rows > 0)) { ?>
                                        <p>
                                        <h4>IP: </h4>
                                        <a href="https://ipapi.co/<?php echo $row->ip ?>/"
                                           target="_blank"><?php echo $row->ip ?></a>
                                        </p>
                                        <h4>Possible Alts: </h4>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th>Username</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if ($result = $mysqli_d->query("SELECT uuid, username FROM players WHERE ip = '$row->ip' AND username != '$username' ORDER BY username ASC")) {
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_object()) {
                                                        echo "<tr>";
                                                        echo "<td><img src='https://crafatar.com/avatars/" . $row->uuid . "?size=24&overlay'> <a href='?user=" . $row->username . "'>$row->username</a></td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr align=\"center\"><td>No users share this IP</td></tr>";
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                      <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                            data-target="#collapsePunish">Punishments
                                    </button>
                                </h2>
                            </div>
                            <div id="collapsePunish" class="collapse" aria-labelledby="headingPunish"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <h4>Bans</h4>
                                    <table class="table table-bordered table-hover">
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
                                        // get the records from the database
                                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, status FROM bans WHERE uuid = '$full_uuid'")) {
                                            // display records if there are records to display
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_object()) {
                                                    $actoruuid = MojangAPI::getUuid($row->actor);

                                                    $status = null;

                                                    if ($row->status) {
                                                        $status = "<span class=\"badge badge-danger\">Banned</span>";
                                                    } else {
                                                        $status = "<span class=\"badge badge-success\">Pardoned</span>";
                                                    }

                                                    // set up a row for each record
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . secondsToDate($row->executionTime/1000) . "</td>";
                                                    echo "<td>" . $status . "</td>";
                                                    echo "</tr>";
                                                }
                                            } // if there are no records in the database, display an alert message
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"4\">No Bans</td>";
                                                echo "</tr>";
                                            }
                                        } // show an error if there is an issue with the database query
                                        else {
                                            echo "Error: " . $mysqli_p->error;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <br/>
                                    <h4>Tempbans</h4>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Issuer</th>
                                            <th>Reason</th>
                                            <th>Date</th>
                                            <th>Expiry</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // get the records from the database
                                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, expiry, status FROM tempbans WHERE uuid = '$full_uuid' ORDER BY executionTime DESC")) {
                                            // display records if there are records to display
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_object()) {
                                                    $actoruuid = MojangAPI::getUuid($row->actor);

                                                    $milli = $row->executionTime;
                                                    $seconds = $milli / 1000;
                                                    $date = date("M jS Y", $seconds);

                                                    $expiry = $milli + $row->expiry;
                                                    $seconds = $expiry / 1000;
                                                    $expiry = date("M jS Y", $seconds);

                                                    if (!$row->status) {
                                                        $status = "<span class=\"badge badge-success\">Expired</span>";
                                                    } else {
                                                        $status = "<span class=\"badge badge-danger\">Banned</span>";
                                                    }

                                                    // set up a row for each record
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . $date . "</td>";
                                                    echo "<td>" . $expiry . "</td>";
                                                    echo "<td>" . $status . "</td>";
                                                    echo "</tr>";
                                                }
                                            } // if there are no records in the database, display an alert message
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"5\">No Bans</td>";
                                                echo "</tr>";
                                            }
                                        } // show an error if there is an issue with the database query
                                        else {
                                            echo "Error: " . $mysqli_p->error;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <br/>
                                    <h4>Mutes</h4>
                                    <table class="table table-bordered table-hover">
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
                                        // get the records from the database
                                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, status FROM mutes WHERE uuid = '$full_uuid'")) {
                                            // display records if there are records to display
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_object()) {
                                                    $actoruuid = MojangAPI::getUuid($row->actor);

                                                    $milli = $row->executionTime;
                                                    $seconds = $milli / 1000;
                                                    $date = date("M jS Y", $seconds);

                                                    $status = null;

                                                    if ($row->status) {
                                                        $status = "<span class=\"badge badge-danger\">Muted</span>";
                                                    } else {
                                                        $status = "<span class=\"badge badge-success\">Pardoned</span>";
                                                    }

                                                    // set up a row for each record
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . $date . "</td>";
                                                    echo "<td>" . $status . "</td>";
                                                    echo "</tr>";
                                                }
                                            } // if there are no records in the database, display an alert message
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"4\">No Bans</td>";
                                                echo "</tr>";
                                            }
                                        } // show an error if there is an issue with the database query
                                        else {
                                            echo "Error: " . $mysqli_p->error;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <br/>
                                    <h4>Tempmutes</h4>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Issuer</th>
                                            <th>Reason</th>
                                            <th>Date</th>
                                            <th>Expiry</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // get the records from the database
                                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, expiry, status FROM tempmutes WHERE uuid = '$full_uuid' ORDER BY executionTime DESC")) {
                                            // display records if there are records to display
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_object()) {
                                                    $actoruuid = MojangAPI::getUuid($row->actor);

                                                    $milli = $row->executionTime;
                                                    $seconds = $milli / 1000;
                                                    $date = date("M jS Y", $seconds);

                                                    $expiry = $milli + $row->expiry;
                                                    $seconds = $expiry / 1000;
                                                    $expiry = date("M jS Y", $seconds);

                                                    if (!$row->status) {
                                                        $status = "<span class=\"badge badge-success\">Expired</span>";
                                                    } else {
                                                        $status = "<span class=\"badge badge-danger\">Banned</span>";
                                                    }

                                                    // set up a row for each record
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . $date . "</td>";
                                                    echo "<td>" . $expiry . "</td>";
                                                    echo "<td>" . $status . "</td>";
                                                    echo "</tr>";
                                                }
                                            } // if there are no records in the database, display an alert message
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"5\">No Mutes</td>";
                                                echo "</tr>";
                                            }
                                        } // show an error if there is an issue with the database query
                                        else {
                                            echo "Error: " . $mysqli_p->error;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <br/>
                                    <h4>Kicks</h4>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th>Issuer</th>
                                            <th>Reason</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // get the records from the database
                                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime FROM kicks WHERE uuid = '$full_uuid' ORDER BY executionTime DESC")) {
                                            // display records if there are records to display
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_object()) {
                                                    $actoruuid = MojangAPI::getUuid($row->actor);

                                                    $milli = $row->executionTime;
                                                    $seconds = $milli / 1000;
                                                    $date = date("M jS Y", $seconds);

                                                    // set up a row for each record
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . $date . "</td>";
                                                    echo "</tr>";
                                                }
                                            } // if there are no records in the database, display an alert message
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"4\">No Kicks</td>";
                                                echo "</tr>";
                                            }
                                        } // show an error if there is an issue with the database query
                                        else {
                                            echo "Error: " . $mysqli_p->error;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse"
                                            data-target="#collapseClans">Clans
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseClans" class="collapse" aria-labelledby="headingClans"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    <?php
                                    // get the records from the database
                                    if ($result = $mysqli_c->query("SELECT clan, rank FROM playerClans WHERE player = '$full_uuid'")) {
                                        // display records if there are records to display
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_object();
                                            ?>
                                            <h4>Clan:</h4>
                                            <p>
                                                <a href="../?clan=<?php echo htmlspecialchars($row->clan) ?>"><?php echo htmlspecialchars($row->clan) ?></a>
                                            </p>
                                            <h4>Rank: </h4>
                                            <p><?php echo ucfirst($row->rank) ?></p>
                                            <?php
                                        } else { ?>
                                            <p>This player is not in a clan!</p>
                                            <?php
                                        }
                                    } else {
                                        echo "Error: " . $mysqli_d->error;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- clan -->
    <?php } else if (isset($_GET["clan"])) {
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
    <br/>
    <div class="row">
        <div class="col-md-3" align="center">
            <div class="info">
                <h2><?php echo $clan ?></h2>
                <?php
                if ($result = $mysqli_c->query("SELECT description FROM clans WHERE name = '$clan'")) {
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_object();
                        ?>
                        <p><?php echo $row->description; ?></p>
                        <?php
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
                if ($result = $mysqli_c->query("SELECT player, rank FROM playerClans WHERE clan = '$clan' ORDER BY rank ASC")) {
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_object()) {

                            $username = MojangAPI::getUsername($row->player);
                            echo "<tr>";
                            echo "<td><img src='https://crafatar.com/avatars/" . $row->player . "?size=24&overlay'> <a href='?user=" . $username . "'>$username</a></td>";
                            echo "<td>" . ucfirst($row->rank) . "</td>";
                            echo "</tr>";
                        }
                    } else { ?>
                        <script>
                            window.location.replace("http://database.vaultmc.net/?search=");
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
                        window.location.replace("http://database.vaultmc.net/?search=");
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
<!-- homepage -->
<?php } else if (empty($_GET)) { ?>
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">VaultMC Database</h1>
        </div>
    </div>
    <br/>
    <div class="row" align="center">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <div class="row" style="background-color:#DEE2E6; border-radius:10px; padding:10px;">
                <div class="col-md-6">
                    <h3>Player & Clan Information</h3>
                    <form role="form" action="?" method="get">
                        <div class="form-group">
                            <label for="playername">Search for a player or clan below</label>
                            <input type="text" class="form-control" name="search" placeholder="Enter your query here.">
                        </div>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <h3>About the VaultMC Database</h3>
                    <p>This custom interface helps you to view information about players on VaultMC. VaultClans plugin
                        by yangyang200, VaultCore plugin & WebUI by Aberdeener.</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
        </div>
    </div>
    <!-- if they modify $_GET -->
<?php } else {
    header('Location: https://database.vaultmc.net');
    exit;
} ?>
</div>
<?php include 'includes/footer.php' ?>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
