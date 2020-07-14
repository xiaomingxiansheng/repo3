<?php

require_once "db.user.php";

session_start();

unset( $GLOBALS["curr_user"] );

if( isset($_SESSION["user"]) )
{
    $datadb = new User();
    $GLOBALS["curr_user"] = $datadb->find_by_id($_SESSION["user"]);
    if( $GLOBALS["curr_user"] == FALSE ){
        unset($GLOBALS["curr_user"]);
    }
}

function check_permission( $permission )
{
    if( !isset($GLOBALS["curr_user"]) || is_null($GLOBALS["curr_user"]) )
    {
        return FALSE;
    }

    if( !isset($GLOBALS["curr_user"]["permission"]) || is_null($GLOBALS["curr_user"]["permission"]) )
    {
        return FALSE;
    }

    $user_permissions = explode(',', $GLOBALS["curr_user"]["permission"]);
    $user_permissions_count = count($user_permissions);

    foreach ($user_permissions as $user_permission) {
        if( $user_permission == $permission ){
            return TRUE;
        }
    }
    return FALSE;
}

?>