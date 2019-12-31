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
            include 'includes/navbar.php'
            ?>
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
             <h4>First Player to Join</h4>
               <p>SELECT username FROM players WHERE MIN(firstseen)</p>
           </div>
           <div class="col-md-3">
             <h4>Latest Player to Join</h4>
               <p>SELECT username FROM players WHERE MAX(firstseen) // SELECT COUNT(uuid) FROM players</p>
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
               <p>SELECT username FROM players WHERE MAX(playtime)</p>
           </div>
           <div class="col-md-3">
             <h4>Server total Playtime</h4>
               <p>SELECT SUM(playtime) FROM players</p>
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
               <p>hmmmm</p>
           </div>
           <div class="col-md-3">
             <h4>Clan with most XP</h4>
               <p>SELECT name FROM clans WHERE MAX(experience)</p>
           </div>
           <div class="col-md-3">
           </div>
         </div>
      </div>
      <?php include 'includes/footer.php' ?>
   </body>
</html>
