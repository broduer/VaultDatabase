<?php
if (!isset($_SESSION)) {
  session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<nav class="navbar navbar-expand-lg py-0 navbar-dark bg-dark">
  <a class="navbar-brand" href="https://vaultmc.net">
    <img alt="VaultMC Logo" height="70" src="https://vaultmc.net/img/vaultmc-logo.png">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarColor02">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="../?page=home">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../?view=search&query=">Search</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../?page=statistics">Statistics</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Info
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="../?page=rules">Rules</a>
          <a class="dropdown-item" href="../?page=help">Help</a>
          <a class="dropdown-item" href="../?page=staff">Staff</a>
        </div>
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
        <a class="nav-link" href="../?view=user&user=<?php echo $_SESSION["uuid"] ?>">
          <img src='https://crafatar.com/avatars/<?php echo $_SESSION["uuid"] ?>?size=24&overlay'>
          <?php echo $_SESSION["username"] ?>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../logout.php">Logout</a>
      </li>
    <?php } ?>
    </ul>
  </div>
</nav>