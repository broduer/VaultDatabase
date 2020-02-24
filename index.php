<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="https://www.vaultmc.net/favicon.ico" type="image/png">
    <link href="css/bootstrap-dark.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <title>VaultMC - Home</title>
</head>

<body>

    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    require 'mojangAPI/mojang-api.class.php';
    include 'config.php';
    include 'functions.php';
    include 'includes/navbar.php';
    ?>

    <div class="container-fluid">

        <br>

        <?php if (isset($_SESSION["timezone"])) {
            $timezone = $_SESSION["timezone"];
        } else {
            $timezone = "null";
        }

        if (isset($_GET["alert"])) {
            switch ($_GET["alert"]) {
                case "no-permission":
                    $alert_type = "danger";
                    $alert_message = "You do not have permission to view this page.";
                    break;
                case "not-signed-in":
                    $alert_type = "danger";
                    $alert_message = "You need to be signed in to view this page";
                    break;
                case "signed-in":
                    $alert_type = "success";
                    $alert_message = "You have been signed in.";
                    break;
                case "signed-out":
                    $alert_type = "success";
                    $alert_message = "You have been signed out.";
                    break;
                case "invalid-user":
                    $alert_type = "danger";
                    $alert_message = "That user does not exist.";
                    break;
                case "already-signed-in":
                    $alert_type = "warning";
                    $alert_message = "You are already signed in.";
                    break;
                case "blog-posted":
                    $alert_type = "success";
                    $alert_message = "Blog post successfully posted.";
                    break;
                case "blog-edited":
                    $alert_type = "success";
                    $alert_message = "Blog post successfully edited.";
                    break;
                case "blog-deleted":
                    $alert_type = "success";
                    $alert_message = "Blog post successfully deleted.";
                    break;
                case "blog-invalid-id":
                    $alert_type = "danger";
                    $alert_message = "Invalid blog ID";
                    break;
                case "comment-posted":
                    $alert_type = "success";
                    $alert_message = "Comment posted successfully.";
                    break;
                case "reply-posted":
                    $alert_type = "success";
                    $alert_message = "Reply posted successfully.";
                    break;
            }
        ?>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="alert alert-<?php echo $alert_type ?> alert-dismissible fade show" role="alert">
                        <?php echo $alert_message ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        <?php
        }
        ?>

        <?php
        $pages = array(
            'home',
            'help',
            'login',
            'logout',
            'register',
            'settings',
            'statistics'
        );
        $views = array(
            'search',
            'user',
            'clan'
        );
        $blog = array(
            'edit',
            'new',
            'delete',
            'view'
        );
        if (isset($_GET['page']) && in_array($_GET['page'], $pages)) {
            include("pages/" . $_GET['page'] . '.php');
        } else if (isset($_GET['view']) && in_array($_GET['view'], $views)) {
            include("views/" . $_GET['view'] . '.php');
        } else if (isset($_GET['blog']) && in_array($_GET['blog'], $blog)) {
            include("blog/" . $_GET['blog'] . '.php');
        } else {
            header('Location: https://vaultmc.net/?page=home');
            exit;
        }
        ?>
    </div>

    <?php include 'includes/footer.php' ?>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/4ff9aa1ee1.js" crossorigin="anonymous"></script>

</body>

</html>