<?php require_once "util.note.php"; ?>

<?php switch( $_GET["a"] ){ ?>
<?php case "change_pswd": ?>
    <form action="action.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <input type="hidden" name="page" value="user"/>
    <input type="hidden" name="act" value="change"/>
    <ul class="main">
        <li>
            <span class="label"><?php echo $lang->user->pswd_old; ?></span>
            <input class="text" type="password" name="pswd_old" placeholder="<?php echo $lang->user->pswd_old_hint; ?>"/>
            <br/>
            <span class="label"><?php echo $lang->user->pswd_new; ?></span>
            <input class="text" type="password" name="pswd_new" placeholder="<?php echo $lang->user->pswd_new_hint; ?>"/>
            <br/>
            <span class="label"><?php echo $lang->user->pswd_retry; ?></span>
            <input class="text" type="password" name="pswd_retry" placeholder="<?php echo $lang->user->pswd_retry_hint; ?>"/>
            <br/>
            <input class="button" type="submit" value="<?php echo $lang->user->change_pswd; ?>"/>
            <a class="button" href="index.php"><?php echo $lang->user->back; ?></a>
            <br/>
        </li>
    </ul>
    </form>
<?php break; ?>

<?php case "list": ?>
    <?php
        if( !check_permission("user_management") ) {
            note_and_skip( "v=home", "page_not_found" );
        }
    ?>
    <?php $search_result = $datadb->find_all(); ?>
    <table align="center">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Group</th>
            <th>Permission</th>
            <th colspan="3">Action</th>
        </tr>
        <?php while( $row = $search_result->fetch(PDO::FETCH_ASSOC) ) { ?>
        <tr>
        <form action="action.php" method="post">
            <td>
                <input type="hidden" name="page" value="user"/>
                <input type="hidden" name="act" value="update"/>
                <input type="text" name="id" value="<?php echo $row["id"]; ?>" readonly="readonly"/>
            </td>
            <td>
                <input type="text" name="name" value="<?php echo $row["name"]; ?>" />
            </td>
            <td>
                <input type="text" name="group" value="<?php echo $row["group"]; ?>" />
            </td>
            <td>
                <input type="text" name="permission" value="<?php echo $row["permission"]; ?>" />
            </td>
            <td>
                <input type="submit" value="<?php echo $lang->user->btn_update; ?>" />
            </td>
        </form>
            <td>
                <form action="action.php" method="post">
                <input type="hidden" name="page" value="user"/>
                <input type="hidden" name="act" value="reset"/>
                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>"/>
                <input type="submit" value="<?php echo $lang->user->btn_reset; ?>" />
                </form>
            </td>
            <td>
                <form action="action.php" method="post">
                <input type="hidden" name="page" value="user"/>
                <input type="hidden" name="act" value="del"/>
                <input type="hidden" name="id" value="<?php echo $row["id"]; ?>"/>
                <input type="submit" value="<?php echo $lang->user->btn_delete; ?>" />
                </form>
            </td>
        </form>
        </tr>
        <?php } ?>
        <tr>
        <form action="action.php" method="post">
            <td>
                <input type="hidden" name="page" value="user"/>
                <input type="hidden" name="act" value="create"/>
                Create New
            </td>
            <td>
                <input type="text" name="name" value="<?php echo $row["name"]; ?>" />
            </td>
            <td>
                <input type="text" name="group" value="<?php echo $row["group"]; ?>" />
            </td>
            <td>
                <input type="text" name="permission" value="<?php echo $row["permission"]; ?>" />
            </td>
            <td>
                <input type="submit" value="<?php echo $lang->user->btn_add; ?>" />
            </td>
            <td></td>
            <td></td>
        </form>
        </tr>
    </table>
<?php break; ?>

<?php default: ?>
    <form action="action.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <input type="hidden" name="page" value="user"/>
    <input type="hidden" name="act" value="login"/>
    <ul class="main">
        <li>
            <span class="label"><?php echo $lang->user->login_name; ?></span>
            <input class="text" type="text" name="name" placeholder="<?php echo $lang->user->login_name_hint; ?>"/>
            <br/>
            <span class="label"><?php echo $lang->user->login_pswd; ?></span>
            <input class="text" type="password" name="pswd" placeholder="<?php echo $lang->user->login_pswd_hint; ?>"/>
            <br/>
            <input class="button" type="submit" value="<?php echo $lang->user->login; ?>"/>
            <br/>
        </li>
    </ul>
    </form>
<?php break; ?>
<?php } ?>
