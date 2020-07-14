<?php
if( !isset($_SESSION["user"]) ){
    header("Location: index.php?v=user");
    exit;
}
?>


<ul class="main">
    <?php if( check_permission("meetting_room") ) { ?>
        <li>
            <a href="index.php?v=metting_room"><?php echo $lang->home->metting_room; ?></a>
            <p><?php echo $lang->home->metting_room_desc; ?></p>
        </li>
    <?php } ?>

    <?php if( check_permission("experience") ) { ?>
        <li>
            <a href="index.php?v=experience"><?php echo $lang->home->experience; ?></a>
            <p><?php echo $lang->home->experience_desc; ?></p>
        </li>
    <?php } ?>

    <?php if( check_permission("user_management") ) { ?>
        <li>
            <a href="index.php?v=user&a=list"><?php echo $lang->home->manage_user; ?></a>
            <p><?php echo $lang->home->manage_user_desc; ?></p>
        </li>
    <?php } ?>
</ul>
