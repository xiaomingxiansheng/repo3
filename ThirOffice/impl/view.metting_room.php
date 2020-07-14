<?php

require_once "util.config.php";
require_once "util.note.php";
require_once "db.metting_room_book.php";

if( !isset($_SESSION["user"]) ||
    !check_permission("meetting_room") ){
    header("Location: index.php?v=home");
    exit;
}

$now_time=time();
$datadb = new MettingRoomBook();
$current_page=0;
if( isset( $_GET["p"] ) )
{
    $current_page=(int)$_GET["p"];
}
$now_time += $current_page*3600*24*Config::$metting_room_day_per_page; // move days

?>

<?php if( check_permission("metting_room_book") ) { ?>
    <form action="action.php" method="post">
        <input type="hidden" name="page" value="metting_room"/>
        <input type="hidden" name="act" value="add"/>
        <p>
            <span><?php echo $lang->metting_room->book_metting_room; ?></span>
            <select name="room">
                <?php
                    for( $i=1; $i<=Config::$metting_room_num; $i++ )
                    {
                        echo "<option>$i</option>";
                    }
                ?>
            </select>
            <input style="width: 10em;" id="pick_from_time" name="from" type="text" autocomplete="off" placeholder="<?php echo $lang->metting_room->start_time; ?>"/>
            <span>=></span>
            <input style="width: 10em;" id="pick_to_time" name="to" type="text" autocomplete="off" placeholder="<?php echo $lang->metting_room->stop_time; ?>" />
            <input type="submit" value="<?php echo $lang->metting_room->book; ?>"/>
        </p>
    </form>
<?php } ?>

<?php if( check_permission("metting_room_cancel") ) { ?>
    <form action="action.php" method="post">
        <input type="hidden" name="page" value="metting_room"/>
        <input type="hidden" name="act" value="del"/>
        <p>
            <span><?php echo $lang->metting_room->cancel_by_id; ?></span>
            <input style="width: 10em;" name="id" type="text" autocomplete="off" placeholder="<?php echo $lang->metting_room->book_id; ?>" />
            <input type="submit" value="<?php echo $lang->metting_room->cancel; ?>"/>
        </p>
    </form>
<?php } ?>

<div class="list_action">
<?php if( $current_page > 0 ) { ?>
    <a style="float: left;" href="index.php?v=metting_room&p=<?php echo ($current_page-1); ?>" ><?php echo $lang->metting_room->prev; ?></a>
<?php } ?>
    <a style="float: right;" href="index.php?v=metting_room&p=<?php echo ($current_page+1); ?>" ><?php echo $lang->metting_room->next; ?></a>
</div>

<?php
$day_start=strtotime( date('Y-m-d 9:00', $now_time) );
for( $i = 0; $i < Config::$metting_room_day_per_page; $i++ ) {
    echo "<div class=\"book_list\">";
    echo "<div class=\"book_day\">";
    echo "<span>".date('Y-m-d', $day_start)."</span>";
    echo "</div>";
    echo "<div class=\"book_rooms\">";
    for( $room=1; $room <= Config::$metting_room_num; $room++ )
    {
        $hour_start = $day_start;
        echo "\n<ul class=\"book_bar\">";
        echo "<li>";
        echo "<span> ".$lang->metting_room->room." $room ：</span>";
        echo "</li>\n";
        for($j=0; $j<30; $j++)
        {
            $hour_end = $hour_start+1800;
            $book_result = $datadb->getbooked($room, $hour_start, $hour_end);
            $row = $book_result->fetch(PDO::FETCH_ASSOC);
            if( $book_result == NULL || $book_result == FALSE || $row == FALSE ){
                echo "<li>";
                echo "<div class=\"room_empty\"></div>";
                echo "<em>".date('H:i', $hour_start)."-".date('H:i', $hour_end)." ".$lang->metting_room->idle." </em>";
                echo "</li>";
            } else {
                echo "<li>";
                echo "<div class=\"room_mark\"></div>";
                echo "<em>".date('H:i', $row['from'])."-".date('H:i', $row['to'])." ".$lang->metting_room->booked." (".$row['id'].") => ".$row['name']."</em>";
                echo "</li>";
            }
            $hour_start = $hour_end;
            $book_result->closeCursor();
        }
        echo "</ul>\n";
    }
    echo "</div>";
    echo "</div>";
    $day_start+=3600*24;
}
?>

<style type="text/css">
    .room_mark {
        width: 1em;
        height: 1em;
        background-color: #2255FF;
    }
    .room_empty {
        width: 1em;
        height: 1em;
        background-color: #88CCFF;
    }
    .book_list {
        margin-top: 1em;
        float:top;
        clear:both;
    }
    .book_day {
        margin-top: 1em;
        margin-right: 1em;
        color: #EE5511;
        font-weight: bolder;
        float:left;
    }
    .book_rooms {
        float:left;
    }
    .book_bar {
        padding: 0;
        list-style: none;
        float:top;
        clear:both;
        color:#555555;
    }
    .book_bar li {
        padding: 0;
        margin-left: 1px;
        margin-bottom: 5px;
        float: left;
        position: relative;
        text-align: center;
        list-style:none;
    }
    .book_bar li em {
        border-radius: 10px;
        border-color: #888888;
        border-style: solid;
        border-width: 1px;
        background-color: #FFEE00;
        color: #555555;
        width: 20em;
        position: absolute;
        top: -55px;
        text-align: center;
        padding: 10px;
        font-style: normal;
        z-index: 2;
        display: none;
    }
    </style>

    <script type="text/javascript">
    $(document).ready(
        function(){
            $(".book_bar li").hover(
                function(){
                    $(this).find("em").animate({opacity: "show"}, "fast");
                },
                function(){
                    $(this).find("em").animate({opacity: "hide"}, "fast");
                }
            );

            $('#pick_from_time').datetimepicker({step: 30});
            $('#pick_to_time').datetimepicker({step: 30});
        }
    );
    </script>
    <script src="js/jquery.datetimepicker.full.min.js"></script>
    <link type="text/css" href="css/jquery.datetimepicker.min.css" rel="stylesheet"/>
