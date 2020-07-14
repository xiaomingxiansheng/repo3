<?php

require_once "util.config.php";
require_once "util.note.php";
require_once "util.auth.php";
require_once "db.metting_room_book.php";

$datadb = new MettingRoomBook();

if( $_POST["act"] == "del" &&
    isset($_POST["id"])  &&
    check_permission("metting_room_cancel"))
{
    try {
        $datadb->delete_by_id( $_POST["id"] );
        note_and_skip( "v=metting_room", "metting_room_cancel_success" );
    }
    catch (Exception $e) {
        note_and_skip( "v=metting_room", "metting_room_cancel_fail", $e->getMessage() );
    }
}


if( $_POST["act"] == "add"  &&
    check_permission("metting_room_book"))
{
    try {
        $from=strtotime($_POST["from"]);
        $to=strtotime($_POST["to"]);
        if( isset($_POST["room"]) &&
            isset($_POST["from"]) &&
            isset($_POST["to"]) &&
            isset($GLOBALS["curr_user"]) &&
            $from < $to &&
            $datadb->checkvailid( $_POST["room"], $from, $to ) &&
            $datadb->insert( $_POST["room"], $GLOBALS["curr_user"]["name"], $from, $to )
            )
        {
            note_and_skip( "v=metting_room", "metting_room_book_success" );
        }
        else
        {
            note_and_skip( "v=metting_room", "metting_room_book_failed" );
        }
    }
    catch (Exception $e) {
        note_and_skip( "v=metting_room", "metting_room_book_failed", $e->getMessage() );
    }
}



?>
