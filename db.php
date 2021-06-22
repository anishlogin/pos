<?php

session_name("anish_pos");
session_start();

const ROOT_URL = '/pos/';
const TIME_ZONE = 'Asia/Kolkata';

ini_set('max_execution_time', 300);

//error_reporting(1);

$conn = mysqli_connect("localhost", "root", "", "pos");

