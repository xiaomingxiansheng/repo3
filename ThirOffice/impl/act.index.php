<?php

require_once "util.config.php";
require_once "util.auth.php";

if( $_POST["act"] == "change_lang" )
{
    if( isset($_COOKIE["lang"]) ) {
        switch( $_COOKIE["lang"] ) {
        case "zh":
            setcookie("lang", "en", time()+Config::$cookie_expire_time);
            $_COOKIE["lang"] = "en";
            break;

        case "en":
        default:
            setcookie("lang", "zh", time()+Config::$cookie_expire_time);
            $_COOKIE["lang"] = "zh";
            break;
        }
    }
    header("Location: index.php");
    exit;
}

?>