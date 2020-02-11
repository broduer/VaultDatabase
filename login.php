<?php
session_start();

require_once "config.php";
require('mojangAPI/mojang-api.class.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: https://database.vaultmc.net?page=home&alert=already-signed-in");
}
$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter username.";
  } else {
    $username = trim($_POST["username"]);
  }
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST["password"]);
  }
  if (empty($username_err) && empty($password_err)) {
    $sql = "SELECT uuid, username, role, password, timezone FROM web_accounts WHERE username = ?";
    if ($stmt = mysqli_prepare($mysqli_d, $sql)) {
      mysqli_stmt_bind_param($stmt, "s", $param_username);
      $param_username = $username;
      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
          mysqli_stmt_bind_result($stmt, $uuid, $username, $role, $hashed_password, $timezone);
          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hashed_password)) {
              $_SESSION["loggedin"] = true;
              $_SESSION["username"] = $username;
              $_SESSION["uuid"] = $uuid;
              if ($timezone == NULL) {
                $_SESSION["timezone"] = null;
              } else {
                $_SESSION["timezone"] = $timezone;
              }

              switch ($role) {
                case "admin":
                  $role = "admin";
                  break;

                case "moderator":
                  $role = "moderator";
                  break;

                default:
                  $role = "player";
                  break;
              }

              $_SESSION["role"] = $role;

              header('Location: https://database.vaultmc.net/?page=home&alert=signed-in');
            } else {
              $password_err = "The password you entered was not valid.";
            }
          }
        } else {
          $username_err = "No account found with that username.";
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
    }
    mysqli_stmt_close($stmt);
  }
  mysqli_close($mysqli_d);
}
?>

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
  <?php include 'includes/navbar.php'; ?>
  <div class="container-fluid">
    </br>
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center">Login</h1>
      </div>
    </div>

    </br>

    <div class="row">
      <div class="col-md-3">
      </div>

      <div class="col-md-6" align="center" style="background-color: #303030; border-radius: 10px; padding: 10px;">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <h3>Enter your credentials below</h3>
          <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            <span class="help-block"><?php echo $username_err; ?></span>
          </div>

          <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
          </div>

          <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
          </div>
        </form>
      </div>

      <div class="col-md-3">
      </div>

    </div>

  </div>
  <?php include 'includes/footer.php' ?>
</body>

</html>