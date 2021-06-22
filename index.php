<?php
include 'db.php';
include 'functions.php';

$screen = 'itemlist.php';
$viewBranch = 0;

if (isset($_SESSION['UserName']) || loginByCookie()) {
    header('Location:' . $screen);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" data-textdirection="LTR" class="loading">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="ACCORDEX">
    <title>Login - Admin</title>

    <link rel="shortcut icon" type="image/png" href="assets/images/favicon.ico">

    <!-- BEGIN EXTERNAL CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/fonts/icomoon.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/app.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/vertical-overlay-menu.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/select/select2.min.css">
    <!-- END EXTERNAL CSS-->
    <style type="text/css">
        .card-header {
            border-bottom: 1px solid #EEE;
            background: #fff03f;
            color: #fff;
        }

        .btn-success {
            color: black;
            background-color: #fff03f;
            border-color: #fff03f;
        }
        .btn-success.focus, .btn-success:focus, .btn-success:hover {
            color: black;
            background-color: #fff03f;
            border-color: #fff03f;
        }

        .p-1 {
            padding: 0 1rem !important;
        }
        .p-1 img{
        width: 100%;
        }
        .line-on-side span {
            background: #FFF;
            padding: 5px 23px;
            border-radius: 6px;
            box-shadow: 0 0 25px 0 #1b736f;
        }

    </style>
</head>
<body data-open="click" data-menu="vertical-menu" data-col="1-column"
      class="vertical-layout vertical-menu 1-column  blank-page blank-page">
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1  box-shadow-2 p-0">
                    <div class="card border-grey border-lighten-3 m-0">
                        <div class="card-header no-border">
                            <div class="card-title text-xs-center">
                                <div class="p-1">
                                    <img src="assets/images/logo.png" alt="logo" width="" height="270">
                                    <!-- <h1>Anish Stores</h1> -->
                                </div>
                            </div>
                            <h6 class="card-subtitle line-on-side text-muted text-xs-center font-small-3 pt-2"><span>Login</span>
                            </h6>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <form class="form-horizontal form-simple login-form" method="POST">
                                    <fieldset class="form-group position-relative has-icon-left mb-0">
                                        <input type="text" class="form-control form-control-lg input-lg" id="user-name"
                                               name="username" placeholder="Enter UserCode" required>
                                        <div class="form-control-position">
                                            <i class="icon-head"></i>
                                        </div>
                                    </fieldset>
                                    <br/>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <input type="password" class="form-control form-control-lg input-lg"
                                               id="user-password" name="pass" placeholder="Enter Password" required>
                                        <div class="form-control-position">
                                            <i class="icon-key3"></i>
                                        </div>
                                    </fieldset>
                                    <fieldset class="form-group row">
                                        <div class="text-md-center error-invalid hidden" style="color:#FF0000"><span
                                                    class='error'>Invalid User Code or Password</span></div>
                                    </fieldset>
                                    <fieldset class="form-group row">

                                        <div class="col-md-6 col-xs-12 text-xs-center text-md-left">
                                            <fieldset>
                                                <input type="checkbox" id="remember-me" class="chk-remember"
                                                       name="rememberme">
                                                <label for="remember-me"> Remember Me</label>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6 col-xs-12 text-xs-center text-md-right"><a
                                                    href="password-forget.php" class="card-link">Forgot Password?</a>
                                        </div>
                                    </fieldset>
                                    <button type="submit" name="Login" class="btn btn-success btn-lg btn-block"><i
                                                class="icon-unlock2"></i> Login
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
<!-- ////////////////////////////////////////////////////////////////////////////-->

<!-- BEGIN JAVASCRIPT-->
<script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="assets/js/tether.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/select2.custom.js"></script>
<!-- END JAVASCRIPT -->

<script>
    $('.login-form').submit(function (e) {
        e.preventDefault();
        var data = $('.login-form').serialize();
        data += '&login=';
        $.post('login.php', data, function (data) {
            data = JSON.parse(data);
            if (data['err'] === 0) {
                <?php
                    echo "location.href = '{$screen}';";
                ?>
            }
            else {
                $('.error-invalid').removeClass('hidden');
            }
        })
    });
    $('.select2-icons').select2({minimumResultsForSearch: 10});
</script>

</body>
</html>
