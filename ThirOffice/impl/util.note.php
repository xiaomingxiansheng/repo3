<?php

session_start();

function note_and_skip( $to_view, $note_msg, $ex_message=NULL ){
    if( isset( $_SESSION["next_note_id"]) ){
        $id = (int)$_SESSION["next_note_id"];
    } else {
        $id = 1;
    }
    $_SESSION["next_note_id"] = $id + 1;
    $_SESSION["skip"][$id] = $to_view;
    $_SESSION["ex_message"][$id] = $ex_message;
    header("Location: index.php?v=note&n=$note_msg&i=$id");
    exit;
}

function pop_note_next_view( $id ) {
    $result = $_SESSION[ "skip" ][ $id ];
    unset( $_SESSION[ "skip" ][ $id ] );
    return $result;
}

function pop_note_ex_msg( $id ) {
    $result = $_SESSION[ "ex_message" ][ $id ];
    unset( $_SESSION[ "ex_message" ][ $id ] );
    return $result;
}

?>
