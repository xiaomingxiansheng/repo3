<?php

require_once "util.config.php";
require_once "util.note.php";
require_once "db.experience.php";

if( !isset($_SESSION["user"]) ||
    !check_permission("experience") ){
    header("Location: index.php");
    exit;
}

$datadb = new Experience();
$current_page=0;

if( isset( $_GET["p"] ) )
{
    $current_page=(int)$_GET["p"];
}

if( isset( $_GET["k"] ) && strlen($_GET["k"]) )
{
    $keywords = "&k=".$_GET["k"];
    $search_result = $datadb->search($_GET["k"]);
}
else
{
    $keywords="";
    $search_result = $datadb->search_all();
}

?>

<?php if(check_permission("experience_create")){ ?>
    <p class="hilight_action">
        <a href="index.php?v=experience_content&a=ceate" target="_blank"><?php echo $lang->experience->create_document; ?></a>
    </p>
<?php } ?>

<div class="search_bar">
    <form method="get" action="index.php" accept-charset="utf-8">
        <input type="hidden" name="v" value="experience" />
        <input class="input" type="text" name="k" placeholder="<?php echo $lang->experience->key_word; ?>" value="<?php echo $_GET["search"]; ?>" />
        <input type="submit" value="<?php echo $lang->experience->go_search; ?>"/>
    </form>
    <br/>
    <?php echo $lang->experience->total_found.$search_result->rowCount().$lang->experience->records; ?>
</div>

<div class="list_action">
    <?php if( $current_page > 0 ) { ?>
        <a style="float: left;" href="index.php?v=experience<?php echo $keywords; ?>&p=<?php echo ($current_page-1); ?>" ><?php echo $lang->experience->prev; ?></a>
    <?php } ?>
    <?php if( $search_result->rowCount() > ($current_page*Config::$expirence_result_per_page)+Config::$expirence_result_per_page ){ ?>
        <a style="float: right;" href="index.php?v=experience<?php echo $keywords; ?>&p=<?php echo ($current_page+1); ?>" ><?php echo $lang->experience->next; ?></a>
    <?php } ?>
</div>

<ul class="main">
<?php
    $current_idx = 0;
    $current_page_begin_idx = $current_page*Config::$expirence_result_per_page + 1;
    $current_page_end_idx = $current_page_begin_idx+Config::$expirence_result_per_page;
    while ( $row = $search_result->fetch(PDO::FETCH_ASSOC) )
    {
        $current_idx ++;
        if( $current_idx < $current_page_begin_idx ) {
            continue;
        }
        else if( $current_idx >= $current_page_end_idx ) {
            break;
        }
    ?>
    <li>
        <a href="index.php?v=experience_content&a=view&eid=<?php echo $row["id"] ?>" target="_blank"><?php echo $row["title"] ?></a>
        <span class="timestamp"><?php echo date("Y-m-d H:i", $row["timestamp"]); ?></span>
        <p><?php echo $row["keyword"] ?></p>
    </li>
<?php } ?>
</ul>
