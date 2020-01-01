<link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/darkly/bootstrap.min.css" rel="stylesheet" integrity="sha384-rCA2D+D9QXuP2TomtQwd+uP50EHjpafN+wruul0sXZzX/Da7Txn4tB9aLMZV4DZm" crossorigin="anonymous">
<?php
if (!isset($_SESSION)){
  session_start();
}
error_reporting(E_ALL); ini_set('display_errors', 1);
?>
<nav class="navbar navbar-expand-lg py-0 navbar-dark bg-dark">
  <a class="navbar-brand" href="https://www.vaultmc.net">
    <img alt="VaultMC Logo" height="70" src="https://www.vaultmc.net/img/vaultmc-logo.png">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarColor02">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="../">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../?search=">Search</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../statistics.php">Statistics</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../help.php">Help</a>
      </li>
      <?php
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
      ?>
    </ul>
      <ul class="nav navbar-nav navbar-right">
      <li class="nav-item">
         <a class="nav-link" href="../login.php">Login</a>
      </li>
      <li class="nav-item">
         <a class="nav-link" href="../register.php">Register</a>
      </li>
    </ul>

      <?php
         } else {
      ?>
      <li class="nav-item">
         <a class="nav-link" href="../settings.php">Settings</a>
      </li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="nav-item">
         <a class="nav-link" href="../?user=<?php echo $_SESSION["username"]?>">
           <img src='https://crafatar.com/avatars/<?php echo $_SESSION["uuid"]?>?size=24&overlay'>
           <?php echo $_SESSION["username"]?>
         </a>
      </li>
      <li class="nav-item">
         <a class="nav-link" href="../logout.php">Logout</a>
      </li>
      <?php } ?>
    </ul>
  </div>
</nav>
