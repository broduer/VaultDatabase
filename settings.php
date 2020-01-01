<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: https://database.vaultmc.net");
    exit;
}

require_once "config.php";
include 'functions.php';

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

$username = $_SESSION["username"];

$result = $mysqli_d->query("SELECT timezone FROM players WHERE username = '$username'");
$row = $result->fetch_object();
$timezone = $row->timezone;

if($_SERVER["REQUEST_METHOD"] == "POST"){

if(isset($_POST["new_password"])) {

    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have at least 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    if(empty($new_password_err) && empty($confirm_password_err)){
        $sql = "UPDATE players SET password = ? WHERE username = ?";

        if($stmt = mysqli_prepare($mysqli_d, $sql)){

            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $username = $_SESSION["username"];
            mysqli_stmt_bind_param($stmt, "ss", $param_password, $username);

            if(mysqli_stmt_execute($stmt)){
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($mysqli_d);
  }
  //timezone code
  if(isset($_POST["timezone"])) {
    $new_timezone = $_POST["timezone"];
    $sql = "UPDATE players SET timezone = '$new_timezone' WHERE username = '$username'";

    if ($stmt = mysqli_prepare($mysqli_d, $sql)) {

        if (mysqli_stmt_execute($stmt)){ ?>
            <script>alert('Updated your timezone.');</script>

        <?php
        $_SESSION["timezone"] = $new_timezone;
      }
        else {
            echo "Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
  }
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
  <div class="container-fluid">
    <?php include 'includes/navbar.php'; ?>

  <br>

    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center">Reset Password</h1>
      </div>
    </div>

  <br>

    <div class="row">
      <div class="col-md-3">
      </div>

      <div class="col-md-6" align="center" style="background-color: #303030; border-radius: 10px; padding: 10px;">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
                <span class="help-block"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
      </div>
      <div class="col-md-3">
      </div>
    </div>

    <br>

    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center">Change Timezone</h1>
      </div>
    </div>

  <br>

    <div class="row">
      <div class="col-md-3">
      </div>
      <div class="col-md-6" align="center" style="background-color: #303030; border-radius: 10px; padding: 10px;">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
              <select name="timezone" class="form-control">
                <option value="">Select a time zone</option>
                <?php foreach(tz_list() as $t) { ?>
                    <option value="<?php echo $t['zone']; ?>" <?php if ($t['zone'] == $timezone) echo "selected"?>>
                    <?php echo $t['diff_from_GMT'] . ' - ' . $t['zone']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
      <div class="col-md-3">
      </div>
    </div>
  </div>
<?php include 'includes/footer.php' ?>
  </body>
</html>
