<br />
<footer>
  <div class="container" align="center">
    <figure>
      <img src="https://www.vaultmc.net/img/vaultmc-logo.png" alt="VaultMC Logo">
    </figure>
    <?php if (!isset($_SESSION["timezone"])) { ?>
      <i>Timezone: [Default] America/Vancouver</i>
  <?php } else { ?>
    <i>Timezone: <?php echo $_SESSION["timezone"]; ?></i>
  <?php } ?>
    <p>Copyright &copy; <?php echo date("Y"); ?> VaultMC. Crafted with <span style="color: #e25555;">&#9829;</span>️ by <a href="https://tadhgboyle.dev">Aberdeener</a>.</p>
  </div>
</footer>
