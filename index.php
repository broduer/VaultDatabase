<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="https://www.vaultmc.net/favicon.ico" type="image/png">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/darkly/bootstrap.min.css" rel="stylesheet" integrity="sha384-rCA2D+D9QXuP2TomtQwd+uP50EHjpafN+wruul0sXZzX/Da7Txn4tB9aLMZV4DZm" crossorigin="anonymous">
    <title>VaultMC - Database</title>
</head>
<body>

<style>
    div.info {
        position: -webkit-sticky;
        position: sticky;
        top: 17px;
        background-color: #303030;
        border-radius: 10px;
    }
</style>

<div class="container-fluid">
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require 'mojangAPI/mojang-api.class.php';
    include 'config.php';
    include 'includes/navbar.php';
    include 'functions.php';
    ?>
    <?php if (isset($_SESSION["timezone"])) {
        $timezone = $_SESSION["timezone"];
    } else {
        $timezone = "null";
    } ?>
    <br>
    <!-- search -->
    <?php if (isset($_GET["search"])) { ?>
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
        <br>
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
                    $search = htmlspecialchars($_GET['search']);
                    $pdoQuery = "SELECT uuid, username FROM players WHERE username LIKE ? ORDER BY username";
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
                    $search = htmlspecialchars($_GET['search']);
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
        <br>
        <div class="row">
          <div class="col-md-12">
            <h1 class="text-center">User Information</h1>
          </div>
        </div>
        <br>
        <div class="row">
              <div class="col-md-3" align="center">
                <div class="info">
                    <h2><?php echo $username ?></h2>
                    <img alt="<?php echo $username ?>"
                         src=" https://crafatar.com/renders/body/<?php echo $uuid ?>?overlay" style="padding:10px"/>
                       </div>
              </div>
              <div class="col-md-3">
                    <h2>will be used for more info</h2>
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

                                        $ls_since = time() - $row->lastseen/1000;
                                        ?>
                                        <h4>UUID:</h4>
                                        <p><?php echo $full_uuid ?></p>
                                        <h4>First Seen: </h4>
                                        <p><?php echo secondsToDate($row->firstseen/1000, $timezone, true); ?></p>
                                        <h4>Last Seen: </h4>
                                        <p><?php echo secondsToDate($row->lastseen/1000, $timezone, true); ?>
                                            (<?php echo secondsToTime($ls_since) ?> ago)</p>
                                        <h4>Playtime: </h4>
                                        <p><?php echo secondsToTime($row->playtime/20); ?></p>
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
                                    <?php if (isset($_SESSION["loggedin"]) && (($_SESSION["role"] == "admin") || ($_SESSION["role"] == "moderator")) && ($result->num_rows > 0)) { ?>
                                   <h4>Possible Alts: </h4>
                                   <i>Based off their latest IP address.</i>
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
                                        <h4>IP History: </h4>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                              <th>IP</th>
                                              <th>Last used</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                           <?php
                                           if ($result = $mysqli_d->query("SELECT DISTINCT ip, ANY_VALUE(start_time) AS start_time FROM sessions WHERE uuid = '$full_uuid' GROUP BY ip ORDER BY start_time DESC")) {
                                               if ($result->num_rows > 0) {
                                                   while ($row = $result->fetch_object()) {
                                                       echo "<tr>";
                                                       echo "<td><a href='https://ipapi.co/" . $row->ip . "'>$row->ip</a></td>";
                                                       echo "<td>" . secondsToDate($row->start_time/1000, $timezone, true) . "</td>";
                                                       echo "</tr>";
                                                   }
                                               } else {
                                                   echo "No Data";
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
                                      if ($result = $mysqli_p->query("SELECT actor, reason, executionTime FROM kicks WHERE uuid = '$full_uuid' ORDER BY executionTime DESC")) {
                                          if ($result->num_rows > 0) {
                                              while ($row = $result->fetch_object()) {
                                                  $actoruuid = MojangAPI::getUuid($row->actor);

                                                  echo "<tr>";
                                                  echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                  echo "<td>" . $row->reason . "</td>";
                                                  echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                                  echo "</tr>";
                                              }
                                          }
                                          else {
                                              echo "<tr>";
                                              echo "<td align=\"center\" colspan=\"4\">No Kicks</td>";
                                              echo "</tr>";
                                          }
                                      }
                                      else {
                                          echo "Error: " . $mysqli_p->error;
                                      }
                                      ?>
                                      </tbody>
                                  </table>
                                  <br>
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
                                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, status FROM bans WHERE uuid = '$full_uuid'")) {
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_object()) {
                                                    $actoruuid = MojangAPI::getUuid($row->actor);
                                                    $status = null;

                                                    if ($row->status) {
                                                        $status = "<span class=\"badge badge-danger\">Banned</span>";
                                                    } else {
                                                        $status = "<span class=\"badge badge-success\">Pardoned</span>";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                                    echo "<td>" . $status . "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"4\">No Bans</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        else {
                                            echo "Error: " . $mysqli_p->error;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <br>
                                    <h4>Tempbans</h4>
                                    <table class="table table-bordered table-hover">
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
                                                    $actoruuid = MojangAPI::getUuid($row->actor);

                                                    if (time() - $row->executionTime > 0) {
                                                        $status = "<span class=\"badge badge-success\">Not Banned</span>";
                                                    } else {
                                                        $status = "<span class=\"badge badge-danger\">Banned</span>";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                                    echo "<td>" . secondsToDate($row->expiry, $timezone, true) . "</td>";
                                                    echo "<td>" . secondsToTime($row->expiry-$row->executionTime) . "</td>";
                                                    echo "<td>" . $status . "</td>";
                                                    echo "</tr>";
                                                }
                                              }
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"6\">No Bans</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        else {
                                            echo "Error: " . $mysqli_p->error;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <br>
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
                                        if ($result = $mysqli_p->query("SELECT actor, reason, executionTime, status FROM mutes WHERE uuid = '$full_uuid'")) {
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_object()) {
                                                    $actoruuid = MojangAPI::getUuid($row->actor);
                                                    $status = null;

                                                    if ($row->status) {
                                                        $status = "<span class=\"badge badge-success\">Pardoned</span>";
                                                    } else {
                                                        $status = "<span class=\"badge badge-danger\">Muted</span>";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                                    echo "<td>" . $status . "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"4\">No Bans</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        else {
                                            echo "Error: " . $mysqli_p->error;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <br>
                                    <h4>Tempmutes</h4>
                                    <table class="table table-bordered table-hover">
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
                                                    $actoruuid = MojangAPI::getUuid($row->actor);

                                                    if (time() - $row->executionTime > 0) {
                                                        $status = "<span class=\"badge badge-success\">Not Muted</span>";
                                                    } else {
                                                        $status = "<span class=\"badge badge-danger\">Muted</span>";
                                                    }
                                                    echo "<tr>";
                                                    echo "<td><img src='https://crafatar.com/avatars/" . $actoruuid . "?size=24&overlay'> <a href='?user=" . $row->actor . "'>$row->actor</a></td>";
                                                    echo "<td>" . $row->reason . "</td>";
                                                    echo "<td>" . secondsToDate($row->executionTime, $timezone, true) . "</td>";
                                                    echo "<td>" . secondsToDate($row->expiry, $timezone, true) . "</td>";
                                                    echo "<td>" . secondsToTime($row->expiry-$row->executionTime) . "</td>";
                                                    echo "<td>" . $status . "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            else {
                                                echo "<tr>";
                                                echo "<td align=\"center\" colspan=\"6\">No Mutes</td>";
                                                echo "</tr>";
                                            }
                                        }
                                        else {
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
    <br>
    <div class="row">
        <div class="col-md-3" align="center">
            <div class="info">
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
    <br>
    <div class="row" align="center">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <div class="row" style="background-color:#303030; border-radius:10px; padding:10px;">
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
