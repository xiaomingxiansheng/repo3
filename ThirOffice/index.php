<?php

require_once "impl/util.config.php";

$lang_file="impl/lang.".$_COOKIE["lang"].".php";
if( !isset($_COOKIE["lang"]) || !file_exists($lang_file) ) {
    setcookie("lang", "en", time()+Config::$cookie_expire_time);
    $_COOKIE["lang"] = "en";
}

require_once "impl/lang.".$_COOKIE["lang"].".php";
require_once "impl/view.util.head.php";

$view_file="impl/view.".$_GET["v"].".php";

if( file_exists($view_file) != TRUE ){
    global $view_file;
    $view_file="impl/view.home.php";
}

require_once $view_file;

require_once "impl/view.util.tail.php";

?>
