<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 09.01.2017
 * Time: 12:07
 */

/**
                 * Распечатка массива в удобной форме
 **/
function print_arr($array){
    echo "<pre>" . print_r($array, true) . "</pre>";
}


/*****************Подключение к БД***************************/

function getConnect() {
    $db = mysqli_connect('localhost', 'root', '', 'iberoamerica'); //запрос к бд. параметры ('host', 'user', 'pass','db')
    mysqli_set_charset($db, 'utf8');
    return $db;
}

/*Подключение к шаблону html*/
function getView($file, $arr = array()) {
    $file = $file . '.html';
    include "view/$file";
}

/*********************Функция для защиты ввода******************/

function escape($str) {
    global $db;
    return mysqli_real_escape_string($db, $str);
}

/*       Ф-я для генерирования хэша пароля */
function getHash($size = 32) {
    $str = "abcdefghijklmnopqrstuvwxyz0123456789";
    $hash = "";
    for ($i = 0; $i < $size; $i++) {
        $hash.= $str[rand(0, 35)];
    }
    return $hash;
}
/****проверка регистрации пользователя**/

function checkUser(){
    global $db;
    if (!empty($_SESSION) AND isset($_SESSION['token'])) {
        $session = $_COOKIE['PHPSESSID'];
        $token = $_SESSION['token'];

        $info = mysqli_query($db, 'SELECT * FROM `connect` LEFT JOIN `users` ON `users`.`id` = `id_user` WHERE `session` = "' . $session . '";');

        if (mysqli_num_rows($info) == 1) {
            $user = mysqli_fetch_assoc($info);
            if ($user['token'] != $token) {
                mysqli_query($db, 'DELETE FROM `connect` WHERE `session` = "' . $session . '";');
                unset($_SESSION['token']);
                $user = false;
            }
            else if ($user['expire'] < time()) {
                $token = getHash();
                $_SESSION['token'] = $token;
                mysqli_query($db, 'UPDATE `connect` SET `token` = "' . $token . '", `expire` = ' . (time() + 300) . ';');
            }
        }
        else {
            unset($_SESSION['token']);
            $user = false;
        }
    }
    else {
        $user = false;
    }
    return $user;
}

/*****************************Функция для вывода картинок из каталога*/
function getImages($dir){                           //проходимся по папке small
    $files = scandir($dir);
    $pattern = '/\.(jpe?g|bmp|png|gif)$/';
    foreach ($files as $key => $file){             //проверка по шаблону,чтобы прпустить только файлы с расширением jpg?jpeg,bmp,png
        if(!preg_match($pattern, $file)){
            unset($files[$key]);                    //удаляем из массива не подходящие шаблону элементы
        }
    }
    $files = array_merge($files);
    return $files;
}

/*
                                    * Функции для работы с БД
 * */

/*********************************Функция для вывода картинок из БД*******************/

    function getImagesDB($gallery,$startPos, $perpage){
        global $db;
        $query = "SELECT `id`, `img` , `description` FROM `images` WHERE `gallery_id` = $gallery
                  ORDER BY `id` DESC
                  LIMIT $startPos, $perpage";
        $res = mysqli_query($db, $query);
        $images = array();
        while ($row = mysqli_fetch_assoc($res)){
            $images[$row['id']] = $row;
        }
        return $images;
    }

/************************************************Количество картинок в галерее*******************/

function countImages($gallery){
    global $db;
    $query = "SELECT COUNT(*) FROM `images` WHERE `gallery_id` = $gallery";
    $res = mysqli_query($db, $query);
    $row = mysqli_fetch_row($res);
    return $row[0];
}
/*******************************Функция для вывода картинок для artgallery из БД*/
function getArtgalleryImagesDB($gallery,$startPos, $perpage){
    global $db;
    $query = "SELECT `id`, `img` , `description` FROM `artgallery` WHERE `gallery_id` = $gallery
                  ORDER BY `id` ASC
                  LIMIT $startPos, $perpage";
    $res = mysqli_query($db, $query);
    $images = array();
    while ($row = mysqli_fetch_assoc($res)){
        $images[$row['id']] = $row;
    }
    return $images;
}

/*Количество картинок в артгалерее*/
function countArtgalleryImages($gallery){
    global $db;
    $query = "SELECT COUNT(*) FROM `artgallery` WHERE `gallery_id` = $gallery";
    $res = mysqli_query($db, $query);
    $row = mysqli_fetch_row($res);
    return $row[0];
}

/***************************Вывод статей из БД***********************************************/
function getMainPageArticles(){
    global $db;
    $query = "SELECT `id`,`title`, `text`, `date` FROM `articles`
              ORDER BY `date` DESC
              LIMIT 3";
    $res = mysqli_query($db, $query);
    $articles = array();
    while ($row = mysqli_fetch_assoc($res)){
        $articles[$row['id']] = $row;
    }
    return $articles;
}



/****************************Постраничная навигация*****************/
function pagination($page, $count_pages, $modrew = false){
    // << < 3 4 5 6 7 > >>
    $back = null; // ссылка НАЗАД
    $forward = null; // ссылка ВПЕРЕД
    $startpage = null; // ссылка В НАЧАЛО
    $endpage = null; // ссылка В КОНЕЦ
    $page2left = null; // вторая страница слева
    $page1left = null; // первая страница слева
    $page2right = null; // вторая страница справа
    $page1right = null; // первая страница справа

    $uri = "?";
    if(!$modrew){
        // если есть параметры в запросе
        if( $_SERVER['QUERY_STRING'] ){
            foreach ($_GET as $key => $value) {
                if( $key != 'page' ) $uri .= "{$key}=$value&amp;";
            }
        }
    }else{
        $url = $_SERVER['REQUEST_URI'];
        $url = explode("?", $url);
        if(isset($url[1]) && $url[1] != ''){
            $params = explode("&", $url[1]);
            foreach($params as $param){
                if(!preg_match("#page=#", $param)) $uri .= "{$param}&amp;";
            }
        }
    }

    if( $page > 1 ){
        $back = "<li><a class='nav-link' href='{$uri}page=" .($page-1). "'>Назад</a></li>";
    }
    if( $page < $count_pages ){
        $forward = "<li><a class='nav-link' href='{$uri}page=" .($page+1). "'>Вперед</a></li>";
    }
    if( $page > 3 ){
        $startpage = "<li><a class='nav-link' href='{$uri}page=1'>В начало</a></li>";
    }
    if( $page < ($count_pages - 2) ){
        $endpage = "<li><a class='nav-link' href='{$uri}page={$count_pages}'>В конец</a></li>";
    }
    if( $page - 2 > 0 ){
        $page2left = "<li><a class='nav-link' href='{$uri}page=" .($page-2). "'>" .($page-2). "</a></li>";
    }
    if( $page - 1 > 0 ){
        $page1left = "<li><a class='nav-link' href='{$uri}page=" .($page-1). "'>" .($page-1). "</a></li>";
    }
    if( $page + 1 <= $count_pages ){
        $page1right = "<li><a class='nav-link' href='{$uri}page=" .($page+1). "'>" .($page+1). "</a></li>";
    }
    if( $page + 2 <= $count_pages ){
        $page2right = "<li><a class='nav-link' href='{$uri}page=" .($page+2). "'>" .($page+2). "</a></li>";
    }

    return $startpage.$back.$page2left.$page1left.'<li class="active-page">'.$page.'</li>'.$page1right.$page2right.$forward.$endpage;
}
/*************функция текущего URL страницы**********/
function request_url()
{
    $result = ''; // Пока результат пуст
    $default_port = 80; // Порт по-умолчанию

    // Проверка на тип соединения(защищенное или нет)
    if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS']=='on')) {
        // если защищеном,то
        $result .= 'https://';
        //переназначим значение порта по-умолчанию
        $default_port = 443;
    } else {
        // Обычное соединение, обычный протокол
        $result .= 'http://';
    }
    // Имя сервера, напр. site.com или www.site.com
    $result .= $_SERVER['SERVER_NAME'];

    // А порт у нас по-умолчанию?
    if ($_SERVER['SERVER_PORT'] != $default_port) {
        // Если нет, то добавим порт в URL
        $result .= ':'.$_SERVER['SERVER_PORT'];
    }
    // Последняя часть запроса (путь и GET-параметры).
    $result .= $_SERVER['REQUEST_URI'];
    // Уфф, вроде получилось!
    return $result;
}