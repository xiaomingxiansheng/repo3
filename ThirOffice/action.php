<?php

$act_file="impl/act.".$_POST["page"].".php";
if( !file_exists($act_file) ){
    header("Location:  index.php?v=note&n=page_not_found");
}
else {
    require $act_file;

    // if not handle, back to source page
    $action_page = $_POST["page"];
    $action_result = "nothing_to_do";
    header("Location: index.php?v=note&lv=$action_page&n=$action_result");
    exit;
}

?>