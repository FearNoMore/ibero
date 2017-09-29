<?php
include_once 'functions.php';
$db = getConnect();

if (isset($_POST['password'])) {
    if ($_POST['password'] == $_POST['repeat_password']) {
        $id = (int)$arr['id'];
        $pass = md5($arr['password']);

        mysqli_query($db, "UPDATE `users` SET `pass` = '" . $pass . "' 
								WHERE `id` = $id;");
        header('Location: auth.php');
    }
}elseif (isset($_GET['login'])) {
    $login = escape($_GET['login']);
    $info = mysqli_query($db, "SELECT `id` FROM `users` 
								WHERE `login` = '" . $login . "';");
    if (mysqli_num_rows($info) == 1) {
        $code = getHash(16);
        mysqli_query($db, 'UPDATE `users` SET `code` = "' . $code . '"
							WHERE `login` = "' . $login . '";');
        file_put_contents($login . '.txt', 'http://localhost/iberoamericana/rebuildpass.php?code=' .$code);
    }else{
        getView('startrebuild', array('text' => 'Нет пользователя с таким логин'));
    }
}elseif(!isset($_GET['code'])){
    getView('startrebuild');
}else{
    $code = escape($_GET['code']);
    $info = mysqli_query($db, "SELECT `id` FROM `users` 
								WHERE `code` = '" . $code . "';");

    if (mysqli_num_rows($info) == 1) {
        $id = mysqli_fetch_assoc($info);
        getView('newpass', array('id' => $id['id']));
    }else{
        getView('startrebuild', array('text' => 'Код не действителен,попробуйте еще раз'));
    }
}

?>