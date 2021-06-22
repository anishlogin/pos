<?php
include 'db.php';
include 'functions.php';

if (isset($_POST['login'])) {
    $username = getPostData('username');
    $pass = getPostData('pass');

    // $user_branch = $user_branch == '' ? $_SESSION['Branch'] : $user_branch;

    $sql = "SELECT * FROM users WHERE name='$username' and password='$pass'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $result = mysqli_fetch_array($result);

        $_SESSION['UserName'] = $_SESSION['username'] = $result['name'];
        $_SESSION['UserId'] = $result['id'];

        if (isset($_POST['rememberme'])) {
            $encryptionMethod = "AES-256-CBC";
            $secretHash = "621e0fd52d6440822f9971dfc1c8454b9e5e93c670c8e80626fa89ddb036dc7d"; //key for encryption
            $initializationVector = "AccpolAccounting"; //16 bytes & padding \0
            $key = 'anish'; //salt for password hash

            $json = [];
            $json['username'] = $_SESSION['username'];
            $json['password'] = hash('sha256', $pass . $_SESSION['UserId'] . $_SESSION['username'] . $key);
            $encryptedText = openssl_encrypt(json_encode($json), $encryptionMethod, $secretHash, 0, $initializationVector);
            setcookie('user_login_data', $encryptedText, time() + (86400 * 30), '/'); // 30 days
        }

        echo json_encode(['err' => 0]);
        exit();
    } else {
        echo json_encode(['err' => 1]);
        exit();
    }
}