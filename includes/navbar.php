<link href="css/bootstrap.min.css" rel="stylesheet">
<?php
if (!isset($_SESSION)){
  session_start();
}
error_reporting(E_ALL); ini_set('display_errors', 1);
?>
<div class="row" style="margin-top: 30px;" align="center">
   <div class="col-md-1" style="margin-top: -30px;">
      <a href="https://www.vaultmc.net"><img alt="VaultMC Logo" src="https://www.vaultmc.net/img/vaultmc-logo.png"></a>
   </div>
   <div class="col-md-1">
      <a href="../">Home</a>
   </div>
   <div class="col-md-1">
     <a href="../?search=">Search</a>
   </div>
   <div class="col-md-1">
     <a href="../statistics.php">Statistics</a>
   </div>
   <div class="col-md-1">
      <a href="../help.php">Help</a>
   </div>
      <?php
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      ?>
      <div class="col-md-5">
      </div>
      <div class="col-md-1">
      <a href="login.php">Login</a>
      </div>
      <div class="col-md-1">
      <a href="register.php">Register</a>
      </div>
      <?php
         } else {
      ?>
      <div class="col-md-1">
         <a href="../settings.php">Settings</a>
      </div>
      <div class="col-md-3">
      </div>
      <div class="col-md-2" align="right">
         <img src='https://crafatar.com/avatars/<?php echo $_SESSION["uuid"]?>?size=24&overlay'> <a href="../?user=<?php echo $_SESSION["username"]?>"><?php echo $_SESSION["username"]?></a>
      </div>
      <div class="col-md-1">
         <a href="../logout.php">Logout</a>
      </div>
      <?php } ?>
</div>
