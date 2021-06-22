<?php
session_name("anish_pos");
session_start();
/**
 * Created by PhpStorm.
 * User: Accordex PC
 * Date: 24-03-2018
 * Time: 05:14 PM
 */
include_once __DIR__ . '/class/mysql/mysql.php';
include_once __DIR__ . '/class/app/ClassApp.php';
const TIME_ZONE = 'Asia/Kolkata';

date_default_timezone_set(TIME_ZONE);
$config = [
    "hostname"  => "localhost",
    "database"  => "pos",
    "username"  => "root",
    "password"  => "",
    "exit_on_error" => false,
    "echo_on_error" => true,
];
$db = mysql::getInstance($config);

header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

