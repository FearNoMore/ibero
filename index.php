<?php
/**
 * Created by PhpStorm.
 * User: Lonedrow
 * Date: 09.01.2017
 * Time: 11:52
 */
/*include 'config.php';*/
session_start();
include_once 'functions.php';
$db = getConnect();
$user = checkUser();
if ($user) {
    getView('index', array('name' => $user['name'], 'auth' => true));
}
else {
    getView('index', array('auth' => false));
}
?>

<div class="wrapper">
    <div class="content">
        <div class="footer">
            <?php include_once "footer.php";?>
        </div>
    </div>
</div>
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/slick.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>