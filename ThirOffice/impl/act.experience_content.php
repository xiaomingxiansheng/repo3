<?php

require_once "util.config.php";
require_once "util.note.php";
require_once "util.auth.php";
require_once "db.experience.php";
require_once "db.experience_content.php";

$experience_db = new Experience();
$experience_content_db = new ExperienceContent();

if( $_POST["act"] == "last_content" &&
    isset($_POST["experience_id"])  &&
    check_permission("experience_content"))
{
    $search_result = $experience_content_db->find_by_exprence_id( $_POST["experience_id"] );
    if( $row = $search_result->fetch(PDO::FETCH_ASSOC) ){
        header('Content-type: text/plain'); 
        echo $row["content"];
        exit;
    }
}

if( $_POST["act"] == "content" &&
    isset($_POST["experience_content_id"])  &&
    check_permission("experience_content"))
{
    $search_result = $experience_content_db->find_by_exprence_id( $_POST["experience_content_id"] );
    if( $row = $search_result->fetch(PDO::FETCH_ASSOC) ){
        header('Content-type: text/plain'); 
        echo $row["content"];
        exit;
    }
}

if( $_POST["act"] == "create" &&
    isset($_POST["title"])  &&
    isset($_POST["keyword"])  &&
    isset($_POST["content"])  &&
    check_permission("experience_create"))
{
    try{
        $now = time();

        // insert into experience
        if( FALSE == $experience_db->insert($now, $_POST["title"], $_POST["keyword"]) ) {
            note_and_skip( "v=experience", "save_experience_fail" );
        }

        $eid = $experience_db->get_last_id();

        // insert into experience_content
        if( FALSE == $experience_content_db->insert($now, $_SESSION["user"], $eid, $_POST["content"]) ) {
            note_and_skip( "v=experience", "save_experience_fail" );
        }
        note_and_skip( "v=experience", "save_experience_success" );
    } catch (Exception $e) {
        note_and_skip( "v=experience", "save_experience_fail", $e->getMessage() );
    }
}

if( $_POST["act"] == "edit" &&
    isset($_POST["eid"])  &&
    isset($_POST["title"])  &&
    isset($_POST["keyword"])  &&
    isset($_POST["content"])  &&
    check_permission("experience_edit"))
{
    try{
        $now = time();

        // update experience
        if( FALSE == $experience_db->update($now, $_POST["eid"], $_POST["title"], $_POST["keyword"]) ) {
            note_and_skip( "v=experience", "save_experience_fail" );
        }

        $eid = $_POST["eid"];

        // insert into experience_content
        if( FALSE == $experience_content_db->insert($now, $_SESSION["user"], $eid, $_POST["content"]) ) {
            note_and_skip( "v=experience", "save_experience_fail" );
        }
        note_and_skip( "v=experience", "save_experience_success" );
    } catch (Exception $e) {
        note_and_skip( "v=experience", "save_experience_fail", $e->getMessage() );
    }
}

if( $_POST["act"] == "import" &&
    isset($_POST["url"])  &&
    check_permission("experience_create"))
{
    // send request to server

    // parse title

    // save result
}


?>
