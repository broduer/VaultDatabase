<?php
session_start();

require_once "config.php";
include 'functions.php';
include 'navbar.php';
require('mojangAPI/mojang-api.class.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  } else {
    header('Location: index.php');
  }
}

$token = $password = $confirm_password = "";
$token_err = $password_err = $confirm_password_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["token"]))) {
    $token_err = "Please enter a token.";
  } else {
    $token = htmlspecialchars($_POST["token"]);

    if ($result = $mysqli_d->query("SELECT username FROM players WHERE token = '$token'")) {
      if ($result->num_rows == 0) {
        $token_err = "That token is invalid.";
      } else {
        while ($row = $result->fetch_object()) {
          $username = $row->username;
        }
      }
    } else {
      echo "Error: " . $mysqli_d->error;
    }
  }
  // Validate password
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter a password.";
  } elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "Password must have atleast 6 characters.";
  } else {
    $password = trim($_POST["password"]);
  }
  // Validate confirm password
  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "Please confirm password.";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "Password did not match.";
    }
  }
  // Check input errors before inserting in database
  if (empty($token_err) && empty($password_err) && empty($confirm_password_err)) {

    $param_password = password_hash($password, PASSWORD_DEFAULT);
    $timezone = $_POST["timezone"];

    $sql = "UPDATE web_accounts SET username = '$username', password = '$param_password', timezone = '$timezone' WHERE token = '$token'";

    if ($stmt = mysqli_prepare($mysqli_d, $sql)) {

      if (mysqli_stmt_execute($stmt)) {
        header("location: login.php");
      } else {
        echo "Something went wrong. Please try again later.";
      }
      mysqli_stmt_close($stmt);
    }
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
  <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/darkly/bootstrap.min.css" rel="stylesheet" integrity="sha384-rCA2D+D9QXuP2TomtQwd+uP50EHjpafN+wruul0sXZzX/Da7Txn4tB9aLMZV4DZm" crossorigin="anonymous">
  <title>VaultMC - Database</title>
</head>

<body>

  <div class="container-fluid">
    <?php include 'includes/navbar.php'; ?>
    </br>
    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center">Sign Up</h1>
      </div>
    </div>

    </br>

    <div class="row">
      <div class="col-md-3">
      </div>

      <div class="col-md-6" align="center" style="background-color: #303030; border-radius: 10px; padding: 10px;">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group <?php echo (!empty($token_err)) ? 'has-error' : ''; ?>">
            <label>Token</label>
            <input type="text" name="token" class="form-control" value="<?php echo $token; ?>">
            <span class="help-block" style="color:red"><?php echo $token_err; ?></span>
          </div>
          <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
            <span class="help-block" style="color:red"><?php echo $password_err; ?></span>
          </div>
          <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
            <span class="help-block" style="color:red"><?php echo $confirm_password_err; ?></span>
          </div>
          <div class="form-group">
            <label>Select a time zone</label>
            <select name="timezone" id="timezone" class="form-control">
              <option value="">Select a time zone</option>
              <?php foreach (listTimezones() as $t) { ?>
                <option value="<?php echo $t['zone']; ?>">
                  <?php echo $t['diff_from_GMT'] . ' - ' . $t['zone']; ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
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