<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 14.01.2017
 * Time: 14:36
 */
session_start();
include_once 'functions.php';
$db = getConnect();

if (!empty($_SESSION) AND isset($_SESSION['token'])) {
    header('Location: index.php');
}
if (empty($_POST)) {
    getView('reg');
}
else {
    if ($_POST['password'] == $_POST['repeat_password']) {
        $login = escape($_POST['login']);
        $loginPattern = '/^[a-zA-z][a-z0-9_-]{5,30}$/i';
        if (preg_match($loginPattern,$login)){
             $login = escape($login);
        }else{
            exit('Логин может содержать латинские и русские буквы,а также цифры'.'<br><br><a href="reg.php">Попробовать еще</a>');
        }
        $email = escape($_POST['email']);
        $emailPattern = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i';
        if (preg_match($emailPattern,$email)){
            $email = escape($email);
            $emailPattern = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i';
        }else{
            exit('Электронный адрес должен быть вида <strong style="color: red">example@mail.ru</strong>'.'<br><br><a href="reg.php">Попробовать еще</a>');
        }
        $pass = md5($_POST['password']);
        $name = escape($_POST['name']);
        $surname = escape($_POST['surname']);

        $count = mysqli_query($db, 'SELECT `id` FROM `users` WHERE `login` = "' . $login . '";');
        if (mysqli_num_rows($count) == 0) {
            mysqli_query($db, 'INSERT INTO `users` SET 
				`login` = "' . $login . '",
				`email` = "' . $email . '",
			 	`pass` = "' . $pass . '",
			 	`name` = "' . $name . '",
			 	`surname` = "' . $surname . '";');
            $id = mysqli_insert_id($db);
            $session = $_COOKIE['PHPSESSID'];
            $token = getHash();
            $expire = time() + 300;
            mysqli_query($db, '
				INSERT INTO `connect` SET
				`session` = "' . $session . '",
				`token` = "' . $token . '",
				`id_user` = ' . $id . ',
				`expire` = ' . $expire . ';
			');

            $_SESSION['token'] = $token;
            header('Location: index.php');
        }
        else {
            getView('reg', array('text' => 'Такой логин уже используется'));
        }
    }
    else {
        getView('reg', array('text' => 'Пароли должны совпадать'));
    }
}
/*print_arr($_POST);*/