<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 16.01.2017
 * Time: 12:42
 */
include ('functions.php');
$db = getConnect();

$gallery = isset($_GET['artgallery']) ? (int)$_GET['artgallery'] : 1;
if ($gallery < 1) $gallery = 1;
//var_dump($gallery);
include_once ('pagination_art.php');
//$dir = 'images/artgallery/small/';
//$bdir = 'images/artgallery/big/';
//$images = getImages($dir);
$dir = 'images/artgallery/';
$images = getArtgalleryImagesDB($gallery, $startPos, $perpage);
//print_arr($images);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Искусство</title>
    <link rel="stylesheet" href="css/gallery.css">
    <link rel="stylesheet" href="css/lightbox.css">
    <link href="https://fonts.googleapis.com/css?family=Jura" rel="stylesheet">
</head>
<body>
<div class="art_header">
    <div class="main_header">
        <div class="topnavmenu">
            <ul class="topnav">
                <li>
                    <a href="index.php">На главную</a>
                </li>
                <li>
                    <a href="#">День в истории</a>
                </li>
                <li>
                    <a href="#">Блог</a>
                </li>
            </ul>
        </div>
        <div style="clear: both"></div>
        <div class="welcome">
            <h1 id="main_link"><a href="index.php">Iberoamerica.com</a></h1>
        </div>
    </div>
</div>
<div class="wrap">
    <h1>Репродукции</h1>
    <div class="gallery">
        <?php if ($images) :?>
            <?php foreach ($images as $image): ?>
                <div class="item">
                    <div>
                        <a data-lightbox="lightbox" href="<?=$dir . $image['img']?>">
                            <img class="front" src="<?=$dir . $image['img']?>" alt="" height="200px" width="300px">
                            <span class="back"><?=$image['description'] ?></span>
                        </a>
                    </div>
                </div>
            <?php  endforeach; ?>
        <?php else: ?>
            <p>Данной галереи не существует</p>
        <?php endif; ?>
        <?php if ($countPages > 1):?>
            <div class="clear"></div>
            <div class="pagination"><?=$pagination ?></div>
        <?php endif ;?>
    </div>
</div>
<script src="js/lightbox-plus-jquery.js"></script>
</body>
</html>