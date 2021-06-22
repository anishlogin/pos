<?php
session_name("anish_pos");
@session_start();
session_destroy();
unset($_SESSION);
setcookie('user_login_data', '', time() - 13600, '/');
unset($_COOKIE);
header("Location: index.php");
exit();