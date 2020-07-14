
<?php
header("Content-Type: text/html;charset=utf-8");

require_once "util.auth.php";
?>


<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <title><?php echo $lang->index->title; ?></title>
    <link type="text/css" href="css/main.css" rel="stylesheet">
    <script src="js/jquery-3.4.1.min.js"></script>
    </head>
    <body>
        <div class="title_welcome">
            <div class="title_action_lang">
                <form id="change_lang" action="action.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" name="page" value="index"/>
                    <input type="hidden" name="act" value="change_lang"/>
                    <a href="javascript:$('#change_lang').submit();"><?php echo $lang->index->change_lang; ?></a>
                </form>
            </div>
            <?php echo $lang->index->welcome; ?>
        </div>

        <div class="title_action_bar">
        <?php if( isset($GLOBALS["curr_user"]) ){ ?>
            <div class="title_action">
            <form id="logout" action="action.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                <input type="hidden" name="page" value="user"/>
                <input type="hidden" name="act" value="logout"/>
                <a href="javascript:$('#logout').submit();"><?php echo $lang->index->logout; ?></a>
            </form>
            </div>

            <div class="title_action">
                <a href="index.php?v=user&a=change_pswd"><?php echo $lang->index->change_pswd; ?></a>
            </div>

            <div class="title_action">
                <a href="index.php"><?php echo $lang->index->index; ?></a>
            </div>
        <?php } ?>
        </div>

        <div class="content">


