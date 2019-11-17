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
         </br>
         <div class="row">
            <div class="col-md-12">
               <h1 class="text-center">Help</h1>
            </div>
         </div>
         </br>
         <div class="row" align="center">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
               <div class="row" style="background-color:#DEE2E6; border-radius:10px; padding:10px;">
                  <div class="col-md-12">
                     <h3>Have questions about this service?</h3>
                     <p>This page is intended to help with any questions you may have. Can't find an answer? Ask any of our friendly staff on Discord or in-game!</p>
                  </div>
               </div>
            </div>
            <div class="col-md-3">
            </div>
         </div>
         </br>
         </br>
         <div class="row" align="center">
           <div class="col-md-1">
           </div>
           <div class="col-md-3">
             <h4>What data do you collect?</h3>
               <p>Each time you log and out of VaultMC we save some of your data. This includes (and is not limited to) your Minecraft UUID, Username, IP, In-Game Rank, Firstseen, Lastseen and your Playtime. We use this data to assist with your in-game experience, along with ensuring the safety of our server from things such as bot attacks.</p>
           </div>
           <div class="col-md-4">
             <h4>How do I register?</h3>
               <p>To register for this database service (includes some fun perks!) you will need your VaultMC token. If you do not have a token run <kbd>/token</kbd> in-game. (Do not lose this token, as it will connect you to our Discord server, this website and more to come soon!) Once you have your token register <a href="https://database.vaultmc.net/register.php">here</a>.</p>
           </div>
           <div class="col-md-3">
             <h4>Where do you get my skin image from?</h3>
               <p>We get all of our player skin and head renders from the <a href="https://crafatar.com/">Crafatar</a> API service.</p>
           </div>
           <div class="col-md-1">
           </div>
         </div>
         <br>
         <div class="row" align="center">
           <div class="col-md-1">
           </div>
           <div class="col-md-3">
             <h4>Why should I register?</h3>
               <p>Great question! If you register on this website, you can customize your timezone while viewing player statistics, edit your clan description and manage the players in your clan (if you are the owner). More to come soon!</p>
           </div>
           <div class="col-md-4">
             <h4>How do I register?</h3>
               <p>To register for this database service (includes some fun perks!) you will need your VaultMC token. If you do not have a token run <kbd>/token</kbd> in-game. (Do not lose this token, as it will connect you to our Discord server, this website and more to come soon!) Once you have your token register <a href="https://database.vaultmc.net/register.php">here</a>.</p>
           </div>
           <div class="col-md-3">
             <h4>Where do you get my skin image from?</h3>
               <p>We get all of our player skin and head renders from the <a href="https://crafatar.com/">Crafatar</a> API service.</p>
           </div>
           <div class="col-md-1">
           </div>
         </div>
         </br>
         </br>
      </div>
      <?php include 'includes/footer.php' ?>
   </body>
</html>
