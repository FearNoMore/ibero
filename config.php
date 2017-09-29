<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 09.01.2017
 * Time: 11:46
 */
define('DBHOST', 'localhost');// host
define('DBUSER', 'root');// имя пользователя
define('DBPASS', ''); // пароль
define('DB', 'iberoamerica'); //название БД

$connection = @mysqli_connect(DBHOST, DBUSER, DBPASS, DB) or die("Нет соединения с БД");
mysqli_set_charset($connection, 'utf8') or die('Не установлена кодировка');