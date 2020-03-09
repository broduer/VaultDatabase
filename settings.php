<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: https://vaultmc.net?page=home&alert=not-signed-in");
  exit;
}

require_once "config.php";
include 'functions.php';
require 'mojangAPI/mojang-api.class.php';

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";

$username = $_SESSION["username"];
$full_uuid = MojangAPI::formatUuid(MojangAPI::getUuid($username));

$schem_folder = "/srv/vaultmc/plugins/VaultLoader/components/VaultCore/schems";

$result = $mysqli_d->query("SELECT timezone FROM web_accounts WHERE username = '$username'");
$row = $result->fetch_object();
$timezone = $row->timezone;
if ($_SERVER["REQUEST_METHOD"] == "GET") {

  if (isset($_GET['delete'])) {
    if (!file_exists($schem_folder . "/" . $full_uuid . "/" . $_GET['delete'])) {
      $errors[] = "That file doesn't exists";
    } else {
      unlink($schem_folder . "/" . $full_uuid . "/" . $_GET['delete']);
?>
      <script>
        alert("Schematic has been deleted!")
      </script>
    <?php
    }
  }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //upload schem
  if (isset($_FILES['schem'])) {
    $errors = array();
    $file_name = $_FILES['schem']['name'];
    $file_size = $_FILES['schem']['size'];
    $file_tmp = $_FILES['schem']['tmp_name'];
    $file_type = $_FILES['schem']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['schem']['name'])));

    $extensions = array("schem", "schematic");

    if (in_array($file_ext, $extensions) === false) {
      $errors[] = "Please choose a .schem or .schematic file.";
    }

    if ($file_size > 2097152) {
      $errors[] = "File size must be under 2 MB";
    }

    if (file_exists($schem_folder . "/" . $full_uuid . "/" . $file_name)) {
      $errors[] = "That file already exists";
    }

    if (empty($errors) == true) {
      move_uploaded_file($file_tmp, $schem_folder . "/" . $full_uuid . "/" . $file_name);
    ?>
      <script>
        alert("Schematic has been uploaded!")
      </script>
      <?php
    } else {
      print_r($errors);
    }
  }
  // reset password
  if (isset($_POST["new_password"])) {
    if (empty(trim($_POST["new_password"]))) {
      $new_password_err = "Please enter the new password.";
    } elseif (strlen(trim($_POST["new_password"])) < 6) {
      $new_password_err = "Password must have at least 6 characters.";
    } else {
      $new_password = trim($_POST["new_password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
      $confirm_password_err = "Please confirm the password.";
    } else {
      $confirm_password = trim($_POST["confirm_password"]);
      if (empty($new_password_err) && ($new_password != $confirm_password)) {
        $confirm_password_err = "Password did not match.";
      }
    }

    if (empty($new_password_err) && empty($confirm_password_err)) {
      $sql = "UPDATE web_accounts SET password = ? WHERE username = ?";

      if ($stmt = mysqli_prepare($mysqli_d, $sql)) {

        $param_password = password_hash($new_password, PASSWORD_DEFAULT);
        $username = $_SESSION["username"];
        mysqli_stmt_bind_param($stmt, "ss", $param_password, $username);

        if (mysqli_stmt_execute($stmt)) {
          session_destroy();
          header("location: login.php");
          exit();
        } else {
          echo "Oops! Something went wrong. Please try again later.";
        }
      }
      mysqli_stmt_close($stmt);
    }
    mysqli_close($mysqli_d);
  }
  //timezone code
  if (isset($_POST["timezone"])) {
    $new_timezone = $_POST["timezone"];
    if (in_array($new_timezone, timezoneListSimple())) {
      $sql = "UPDATE web_accounts SET timezone = '$new_timezone' WHERE username = '$username'";

      if ($stmt = mysqli_prepare($mysqli_d, $sql)) {

        if (mysqli_stmt_execute($stmt)) { ?>
          <script>
            alert('Updated your timezone.');
          </script>
<?php
          $_SESSION["timezone"] = $new_timezone;
        } else {
          echo "Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
      }
    } else {
      echo "That timezone is invalid.";
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
  <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.4.1/darkly/bootstrap.min.css" rel="stylesheet" integrity="sha384-rCA2D+D9QXuP2TomtQwd+uP50EHjpafN+wruul0sXZzX/Da7Txn4tB9aLMZV4DZm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="css/datatables.css" />
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
  <link href="css/styles.css" rel="stylesheet">
  <title>VaultMC - Database</title>
  <script>
    $(document).ready(function() {
      $('table.stats').DataTable({
        "order": [],
        "searching": false,
        "paging": false,
        "bInfo": false
      });
    });
  </script>
</head>

<body>
  <?php
  include 'includes/navbar.php';
  ?>
  <div class="container-fluid">

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
              <?php foreach (listTimezones() as $t) { ?>
                <option value="<?php echo $t['zone']; ?>" <?php if ($t['zone'] == $timezone) echo "selected" ?>>
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

    <br>

    <div class="row">
      <div class="col-md-12">
        <h1 class="text-center">Schematics</h1>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="col-md-2">
      </div>
      <div class="col-md-8" align="center" style="background-color: #303030; border-radius: 10px; padding: 10px;">
        <br>
        <div class="row">
          <div class="col-md-5">
            <h4>Upload Schematic</h4>
            <br>
            <form action="" method="POST" enctype="multipart/form-data">
              <input type="file" name="schem" />
              <input type="submit" value="Upload" class="btn btn-primary" />
            </form>
            <br>
          </div>
          <div class="col-md-7 table-responsive">
            <h4>Your Schematics</h4>
            <table class="stats">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Uploaded</th>
                  <th>Size</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $iterator = new \FilesystemIterator($schem_folder . "/" . $full_uuid);
                $isDirEmpty = !$iterator->valid();

                if ($isDirEmpty) {
                  echo "<tr align=\"center\"><td colspan=\"4\"><i>You have no Schematics.</i></td></tr>";
                } else {
                  foreach (new DirectoryIterator($schem_folder . "/" . $full_uuid) as $fileInfo) {
                    // check if its a hidden file
                    if ($fileInfo->isDot()) {
                      continue;
                    }
                    echo "<tr>";
                    echo "<td><a href=\"https://www.vaultmc.net/schems/" . $full_uuid . "/" . $fileInfo->getFilename() . "\">" . $fileInfo->getFilename() . "</a></td>";
                    echo "<td>" . secondsToDate($fileInfo->getMTime(), $timezone, true) . "</td>";
                    echo "<td>" . readableFilesize($fileInfo->getSize()) . "</td>";
                    echo "<td><a href=\"?delete=" . $fileInfo->getFilename() . "\">Delete</a></td>";
                    echo "</tr>";
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <br>
      </div>
      <div class="col-md-2">
      </div>
    </div>
    <?php include 'includes/footer.php' ?>
</body>

</html>