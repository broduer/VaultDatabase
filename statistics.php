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
      <div class="container-fluid">
         <?php
            error_reporting(E_ALL); ini_set('display_errors', 1);
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
         <div class="row">
            <div class="col-md-12">
               <h1 class="text-center">Statistics</h1>
            </div>
         </div>
         </br>
         <div class="row" align="center">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
               <div class="row" style="background-color:#DEE2E6; border-radius:10px; padding:10px;">
                  <div class="col-md-12">
                     <h3>Check out some cool statistics about VaultMC!</h3>
                     <p>Find some neat statistics generated from the player data we collect here!</p>
                  </div>
               </div>
            </div>
            <div class="col-md-3">
            </div>
         </div>
       </br>
         <div class="row" align="center">
           <div class="col-md-3">
           </div>
           <div class="col-md-3">
             <h4>Latest Player to Join</h4>
                   <?php
                   if ($result = $mysqli_d->query("SELECT uuid, username, lastseen FROM players ORDER BY lastseen DESC LIMIT 1")) {
                       if ($result->num_rows > 0) {
                           while ($row = $result->fetch_object()) {
                             echo "</br>";
                             echo "<img src='https://crafatar.com/avatars/" . $row->uuid . "?size=24&overlay'> <a href='https://database.vaultmc.net/?user=" . $row->username . "'>$row->username</a>";
                             echo "</br>";
                             secondsToDate($row->lastseen/1000, $timezone);
                           }
                       } else {
                           echo "No Data";
                       }
                   }
                   ?>
           </div>
           <div class="col-md-3">
             <h4>Newest Player</h4>
             <?php
             if ($result = $mysqli_d->query("SELECT uuid, username, firstseen FROM players ORDER BY firstseen DESC LIMIT 1")) {
                 if ($result->num_rows > 0) {
                     while ($row = $result->fetch_object()) {
                       echo "</br>";
                       echo "<img src='https://crafatar.com/avatars/" . $row->uuid . "?size=24&overlay'> <a href='https://database.vaultmc.net/?user=" . $row->username . "'>$row->username</a>";
                       echo "</br>";
                       secondsToDate($row->firstseen/1000, $timezone);
                     }
                 } else {
                     echo "No Data";
                 }
             }
             ?>
           </div>
           <div class="col-md-3">
           </div>
           <div class="col-md-1">
           </div>
         </div>
         <br>
         <div class="row" align="center">
           <div class="col-md-3">
           </div>
           <div class="col-md-3">
             <h4>Player with most Playtime</h4>
             <?php
             if ($result = $mysqli_d->query("SELECT uuid, username, playtime FROM players ORDER BY playtime DESC LIMIT 1")) {
                 if ($result->num_rows > 0) {
                     while ($row = $result->fetch_object()) {
                       echo "</br>";
                       echo "<img src='https://crafatar.com/avatars/" . $row->uuid . "?size=24&overlay'> <a href='https://database.vaultmc.net/?user=" . $row->username . "'>$row->username</a>";
                       echo "</br>";
                       echo secondsToTime($row->playtime / 20);
                     }
                 } else {
                     echo "No Data";
                 }
             }
             ?>
           </div>
           <div class="col-md-3">
             <h4>Server total Playtime</h4>
                 <?php
                 if ($result = $mysqli_d->query("SELECT SUM(playtime) AS playtime_sum FROM players")) {
                     if ($result->num_rows > 0) {
                         while ($row = $result->fetch_object()) {
                           echo "</br>";
                           echo secondsToLongTime($row->playtime_sum);
                         }
                     } else {
                         echo "No Data";
                     }
                 }
                 ?>
           </div>
           <div class="col-md-3">
           </div>
         </div>
         <br>
         <div class="row" align="center">
           <div class="col-md-3">
           </div>
           <div class="col-md-3">
             <h4>Clan with most Members</h4>
               <?php
               if ($result = $mysqli_c->query("SELECT COUNT(clan) AS members, clan FROM playerClans GROUP BY clan ORDER BY COUNT(*) DESC LIMIT 1")) {
                   if ($result->num_rows > 0) {
                       while ($row = $result->fetch_object()) {
                         if ($row->clan == NULL) {
                           echo "<i>Currently all equal</i>";
                         } else {
                           echo "<a href='https://database.vaultmc.net/?clan=" . $row->clan . "'>$row->clan</a>";
                           echo "</br>";
                           echo "Currently has " . $row->members . " members.";
                         }
                       }
                   } else {
                       echo "No Data";
                   }
               }
               ?>
           </div>
           <div class="col-md-3">
             <h4>Clan with highest Level</h4>
             <?php
             if ($result = $mysqli_c->query("SELECT name, level, experience FROM clans ORDER BY experience DESC LIMIT 1")) {
                 if ($result->num_rows > 0) {
                     while ($row = $result->fetch_object()) {
                       echo "<a href='https://database.vaultmc.net/?clan=" . $row->name . "'>$row->name</a>";
                       echo "</br>";
                       echo "Level " . $row->level . ", " .$row->experience . " xp";
                     }
                 } else {
                     echo "No Data";
                 }
             }
             ?>
           </div>
           <div class="col-md-3">
           </div>
         </div>
      </div>
      <?php include 'includes/footer.php' ?>
   </body>
</html>
