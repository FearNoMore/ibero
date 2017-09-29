<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 14.01.2017
 * Time: 14:35
 */
session_start();
include_once 'functions.php';
$db = getConnect();

if (!empty($_SESSION) AND isset($_SESSION['token'])) {
    header('Location: index.php');
}

if (empty($_POST)) {
    getView('auth');
}
else {
    $login = escape($_POST['login']);
    $pass = md5($_POST['password']);

    $count = mysqli_query($db, 'SELECT `id` FROM `users` WHERE `login` = "' . $login . '" AND `pass` = "' . $pass . '";');
    if (mysqli_num_rows($count) == 0) {
        getView('auth', array('text' => 'Такого пользователя не существует'));
    }
    else if (mysqli_num_rows($count) == 1) {
        $id = mysqli_fetch_assoc($count);
        $session = $_COOKIE['PHPSESSID'];
        $token = getHash();
        $expire = time() + 300;
        mysqli_query($db, '
				INSERT INTO `connect` SET
				`session` = "' . $session . '",
				`token` = "' . $token . '",
				`id_user` = ' . $id['id'] . ',
				`expire` = ' . $expire . ';
		');

        $_SESSION['token'] = $token;
        print_r($id) . "<br>";
        header('Location: index.php');
    }
}
?>
