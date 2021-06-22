<?php
function setTimeZone($conn){
    date_default_timezone_set(TIME_ZONE);

    $now = new DateTime();
    $mins = $now->getOffset() / 60;
    $sgn = ($mins < 0 ? -1 : 1);
    $mins = abs($mins);
    $hrs = floor($mins / 60);
    $mins -= $hrs * 60;
    $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
    mysqli_query($conn, "SET time_zone='$offset'");
}

function getConn($brn, $is_main = false) {
    global $clnt;
    return $clnt;
}
function protect_var($var)
{
    global $conn;
    return htmlspecialchars(mysqli_real_escape_string($conn, $var), ENT_QUOTES);
}

function getPostData($var, $default = '')
{
    if (isset($_POST[$var]) && !empty($_POST[$var])) {
        return protect_var($_POST[$var]);
    }
    return $default;
}

function getGetData($var, $default = '')
{
    if (isset($_GET[$var]) && !empty($_GET[$var])) {
        return protect_var($_GET[$var]);
    }
    return $default;
}

function getData($var, $default = '')
{
    if (isset($var) && !empty($var)) {
        return $var;
    }
    return $default;
}

function getArrayData($arr, $var, $default)
{
    if (isset($arr[$var])) {
        //if (arr)
        return $arr[$var];
    }
    return $default;
}

if (!function_exists('mysqli_begin_transaction')) {
    function mysqli_begin_transaction($link)
    {
        mysqli_query($link, 'START TRANSACTION');
    }
}

if (!function_exists('boolval')) {
    function boolval($var)
    {
        return (boolean)$var;
    }
}

function safe_var($var)
{
    return htmlspecialchars($var);
}

function selected($var1, $var2)
{
    return $var1 === $var2 ? 'selected' : '';
}

function checked($val, $check)
{
    return $val == $check ? 'checked' : '';
}

function selected_multi($var, $arr)
{
    if (array_search($var, $arr) !== false) {
        return 'selected';
    }
    return '';
}

function url($url = '')
{
    if ($url == '') return ROOT_URL;

    if ($url[0] == '/' && substr(ROOT_URL, strlen(ROOT_URL)) == '/')
        $url = substr($url, 1);
    else if ($url[0] != '/' && substr(ROOT_URL, strlen(ROOT_URL) - 1) != '/')
        $url = '/' . $url;
    return ROOT_URL . $url;
}

function tryLogin()
{
    if (isset($_SESSION['UserName']) || loginByCookie()) {
        return;
    }
    header('Location: index.php?src=' . urlencode($_SERVER["REQUEST_URI"]));
    exit();
}

function loginByCookie()
{
    global $clnt, $conn;

    if (isset($_COOKIE['user_login_data']) && $_COOKIE['user_login_data'] != '') {

        $encryptionMethod = "AES-256-CBC";
        $secretHash = "621e0fd52d6440822f9971dfc1c8454b9e5e93c670c8e80626fa89ddb036dc7d"; //key for encryption
        $initializationVector = "AccpolAccounting"; //16 bytes
        $key = 'anish'; //salt for password hash

        $login_data = getArrayData($_COOKIE, 'user_login_data', '');
        $decryptedText = openssl_decrypt($login_data, $encryptionMethod, $secretHash, 0, $initializationVector);
        $login = json_decode($decryptedText, true);

        $usercode = getArrayData($login, 'UserId', '');

        $sql = "select * from users where usr.UserCode = '$usercode'";
        //   $sql = "SELECT UserCode,BranchId,UserId,SmallPicture,DisplayName, Password FROM tbluseremployee WHERE UserCode='$usercode' and BranchId='$user_branch' and UserStatus='a'";

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $result = mysqli_fetch_array($result);

            $pass = $result['Password'];
            $pass = hash('sha256', $pass . $result['id'] . $key);
            if ($pass == $login['password']) {
                $_SESSION['UserId'] = $result['id'];
                $_SESSION['UserName'] = $result['name'];

                return true;
            }
        }
        setcookie('user_login_data', '', time() - 13600, '/');
        unset($_COOKIE);
    }
    return false;
}

function jsonEcho($err, $txt, $id = '', $value = '', $redirect = '')
{
    return json_encode(['err' => $err, 'txt' => $txt, 'redirect' => $redirect, 'id' => $id, 'value' => $value]);
}


function makeThumbnails($dir, $img)
{
    $thumbSize = 100;
    $thumb_prefix = "thumb";

    list($width, $height, $type) = getimagesize("$dir" . "$img");

    if ($width > $height) {
        $y = 0;
        $x = ($width - $height) / 2;
        $smallestSide = $height;
    } else {
        $x = 0;
        $y = ($height - $width) / 2;
        $smallestSide = $width;
    }

    $save_image = '';
    $create_image = '';
    if ($type == IMAGETYPE_GIF) {
        $save_image = "ImageGIF";
        $create_image = "ImageCreateFromGIF";
    }
    if ($type == IMAGETYPE_JPEG) {
        $save_image = "ImageJPEG";
        $create_image = "ImageCreateFromJPEG";
    }
    if ($type == IMAGETYPE_PNG) {
        $save_image = "ImagePNG";
        $create_image = "ImageCreateFromPNG";
    }
    if ($save_image) {
        $old_image = $create_image("$dir" . "$img");
        $thumb = imagecreatetruecolor($thumbSize, $thumbSize);
        imagecopyresampled($thumb, $old_image, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);
        $save_image($thumb, "$dir" . "$thumb_prefix" . '_' . "$img");
    }
}

function uploadImage($file, $dir, $default = 'default.png')
{

    $supported_mime = array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    );

    $maxsize = 5242880; //5 MB

    if (!isset($_FILES[$file]) || !file_exists($_FILES[$file]['tmp_name']) || !is_uploaded_file($_FILES[$file]['tmp_name'])) {
        return $default;
    }

    $customer_image = $_FILES[$file];
    $ext = pathinfo($customer_image['name'])['extension'];
    $new_name = md5(uniqid(time())) . '.' . $ext;

    if (!in_array($customer_image['type'], $supported_mime)) {
        echo jsonEcho(1, 'Image Format Not Supported. <br><b>Supported Formats: .jpeg, .jpg, .png, .gif</b>');
        exit(0);
    }

    if ($customer_image['size'] > $maxsize) {
        echo jsonEcho(1, 'Image Size Too Long. <br><b>Maximum Size: 5 MB</b>');
        exit(0);
    }

    if (!file_exists($dir)) {
        mkdir($dir);
    }

    move_uploaded_file($customer_image['tmp_name'], $dir . DIRECTORY_SEPARATOR . $new_name);
    makeThumbnails($dir . DIRECTORY_SEPARATOR, $new_name);

    return $new_name;
}

function sendMessage($contact_num,$message) {
    $username="accordex";
    $password="104400";
    $mobile_no=$contact_num;
    $sender="ACCRDX";
    $urll="http://www.smslane.com/vendorsms/pushsms.aspx?user=".urlencode($username)."&password=".urlencode($password)."&msisdn=".urlencode($mobile_no)."&sid=".urlencode($sender)."&msg=".urlencode($message)."&fl=".urlencode('0')."&gwid=".urlencode('2');
    //$response["message"] = "success";
    //echo json_encode($response);
    // Initialize session and set URL.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urll);
    //Set so curl_exec returns the result instead of outputting it.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Get the response and close the channel.
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

function sendmail($to, $subject, $template,$headers='') {
    $mail = new PHPMailer();

    $mail->IsSMTP();
    $mail->Host = "accordex.in";  /*SMTP server*/

    $mail->SMTPAuth = true;
//$mail->SMTPSecure = "ssl";
    $mail->Port = 587;
    $mail->Username = "support@accordex.in";  /*Username*/
    $mail->Password = "ChangeMe!@#";
    /**Password**/

    $mail->From = "test@accordex.in";    /*From address required*/
    $mail->FromName = $subject;
    $mail->AddAddress($to);
//$mail->AddReplyTo("mail@mail.com");

    $mail->IsHTML(true);

    $mail->Subject = $subject;

    $mail->Body = $template;
//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

    if (!$mail->Send()) {
        return "not sent";
        // echo "Mailer Error: " . $mail->ErrorInfo;
        // exit;
    }

    return "sent";
}