<?php

require_once "util.auth.php";
require_once "util.note.php";
require_once "db.user.php";
$datadb = new User();

if( $_POST["act"] == "login" &&
    isset($_POST["name"]) &&
    isset($_POST["pswd"]) ) {
    $row = $datadb->find_by_name( $_POST["name"] );
    if( is_null($row) || $row == FALSE ) {
        note_and_skip( "v=user", "user_not_exist" );
    } else if( sha1($_POST["pswd"]) == $row["pswd"] ) {
        $_SESSION["user"] = $row["id"];
        $GLOBALS["curr_user"] = $row;
        note_and_skip( "v=home", "login_success" );
    } else {
        note_and_skip( "v=user", "pswd_not_match" );
    }
}

if( $_POST["act"] == "logout" ) {
    unset( $_SESSION["user"] );
    unset( $GLOBALS["curr_user"] );
    note_and_skip( "v=user", "logout_success" );
}

if( $_POST["act"] == "change" &&
    isset($_POST["pswd_old"]) &&
    isset($_POST["pswd_new"]) &&
    isset($_POST["pswd_retry"]) ) {
    try {
        if( sha1($_POST["pswd_old"]) != $GLOBALS["curr_user"]["pswd"] ) {
            note_and_skip( "v=home", "change_pswd_fail_pswd" );
        } else if( $_POST["pswd_new"] != $_POST["pswd_retry"] ) {
            note_and_skip( "v=home", "change_pswd_fail_retry" );
        } else {
            $datadb->change_password( $GLOBALS["curr_user"]["id"], $_POST["pswd_new"] );
            note_and_skip( "v=home", "change_pswd_success" );
        }
    } catch (PDOException $e) {
        $action_result = "change_pswd_fail";
        note_and_skip( "v=home", "change_pswd_fail", $e->getMessage() );
    }
}


if( $_POST["act"] == "create" &&
    isset($_POST["name"]) &&
    isset($_POST["group"]) &&
    isset($_POST["permission"]) &&
    check_permission("user_management") ) {
    try {
        $row = $datadb->insert( $_POST["name"], $_POST["group"], $_POST["permission"] );
        if( is_null($row) || $row == FALSE ) {
            note_and_skip( "v=user&a=list", "user_create_fail" );
        } else {
            note_and_skip( "v=user&a=list", "user_create_success" );
        }
    } catch (PDOException $e) {
        note_and_skip( "v=user&a=list", "user_create_fail", $e->getMessage() );
    }
}


if( $_POST["act"] == "reset" &&
    isset($_POST["id"]) &&
    check_permission("user_management") ) {
    try {
        $row = $datadb->reset_password( $_POST["id"] );
        if( is_null($row) || $row == FALSE ) {
            note_and_skip( "v=user&a=list", "reset_pswd_fail" );
        } else {
            note_and_skip( "v=user&a=list", "reset_pswd_sucess" );
        }
    } catch (PDOException $e) {
        note_and_skip( "v=user&a=list", "reset_pswd_fail", $e->getMessage() );
    }
}

if( $_POST["act"] == "del" &&
    isset($_POST["id"]) &&
    check_permission("user_management") ) {
    try {
        $row = $datadb->delete_by_id( $_POST["id"] );
        if( is_null($row) || $row == FALSE ) {
            note_and_skip( "v=user&a=list", "reset_pswd_fail" );
        } else {
            note_and_skip( "v=user&a=list", "user_del_success" );
        }
    } catch (PDOException $e) {
        note_and_skip( "v=user&a=list", "reset_pswd_fail", $e->getMessage() );
    }
}

if( $_POST["act"] == "update" &&
    isset($_POST["id"]) &&
    isset($_POST["name"]) &&
    isset($_POST["group"]) &&
    isset($_POST["permission"]) &&
    check_permission("user_management") ) {
    try {
        $row = $datadb->update( $_POST["id"], $_POST["name"], $_POST["group"], $_POST["permission"] );
        if( is_null($row) || $row == FALSE ) {
            note_and_skip( "v=user&a=list", "user_update_fail" );
        } else {
            note_and_skip( "v=user&a=list", "user_update_success" );
        }
    } catch (PDOException $e) {
        note_and_skip( "v=user&a=list", "user_update_fail", $e->getMessage() );
    }
}

?>