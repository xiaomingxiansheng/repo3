
<div class="note_message">
<?php

require_once "util.config.php";
require_once "util.note.php";

$note_message=$_GET["n"];
if( isset($_GET["n"]) && isset($lang->note->$note_message) ){
    echo $lang->note->$note_message;
} else {
    echo $lang->note->page_not_found;
}

$ex_message=pop_note_ex_msg($_GET["i"]);
if( isset($ex_message) && !is_null($ex_message) ){
    echo "<br/>".$ex_message;
}
?>

</div>

<?php $to_view="index.php?".pop_note_next_view($_GET["i"]); ?>
<?php $skip_total=Config::$skip_time_in_sec; ?>

<div class="note_skip">
    <?php echo $lang->note->auto_skip; ?>
    <span id="skip_left_time"><?php echo $skip_total; ?></span>
    <a href="index.php?v=<?php echo $to_view; ?>">
        <?php echo $lang->note->click_here; ?>
    </a>
</div>

<script type="text/javascript">
    var left_sec = <?php echo $skip_total; ?>;
    $(document).ready(
        function(){
            window.setInterval(function(){
                left_sec --;
                $("#skip_left_time").text(left_sec);
                if( left_sec <= 0 ) {
                    window.location.replace("<?php echo $to_view; ?>");
                }
            }, 1000);
        }
    );
</script>
