<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 21.01.2017
 * Time: 17:22
 */
include_once ("../functions.php");
include_once ("../cfg.php");
/*$parentUrl = "http://localhost/iberoamericana/countries";
$parentLength = strlen($parentUrl)+1;*/
$pageUrl = request_url();
$urlLength = strlen($pageUrl);
$pageUrl= substr($pageUrl,$parentLength, -4);
include_once ("../header.php");
?>

