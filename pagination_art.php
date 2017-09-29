<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 18.01.2017
 * Time: 20:36
 */
$perpage = 6;                                // Кол-во изображений на страницу
$countImg = countArtgalleryImages($gallery);                 // общее кол-во изображений
$countPages = ceil($countImg / $perpage);   //кол-во страниц
if (!$countPages) $countPages = 1;          // если число страниц равно
if (isset($_GET['page'])){                  //номер текущей страницы
    $page = (int)$_GET['page'];
    if ( $page < 1) $page = 1;
}else{
    $page = 1;
}
if ($page > $countPages) $page = $countPages;

$startPos = ($page - 1) * $perpage ;                               //Первая картинка на странице
//echo $startPos;
$endPos = ($startPos + $perpage);                                  //последняя картинка на странице
//echo $endPos;
if ($endPos > $countImg) $endPos = $countImg;                   //если последняя страница больше максимума картинок
//echo $endPos;

$pagination = pagination($page,$countPages);