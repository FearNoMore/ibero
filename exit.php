<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 15.01.2017
 * Time: 11:36
 */
session_start();
include_once 'functions.php';

$db = getConnect();

if (!(empty($_SESSION) OR !isset($_SESSION['token']))) {
    $session = $_COOKIE['PHPSESSID'];
    mysqli_query($db, 'DELETE FROM `connect` WHERE `session` = "' . $session . '";');
    unset($_SESSION['token']);
}
header('Location: index.php');