<?php

require_once "util.config.php";
require_once "util.note.php";
require_once "db.user.php";
require_once "db.experience.php";
require_once "db.experience_content.php";

if( !isset($_SESSION["user"]) ||
    !check_permission("experience_content") ){
    header("Location: index.php");
    exit;
}

$experience_db = new Experience();
$experience_content_db = new ExperienceContent();
$user_db = new User();
unset($ecid);

?>

<?php switch( $_GET["a"] ){ ?>
<?php case "ceate": ?>
    <form action="action.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <input type="hidden" name="page" value="experience_content"/>
    <input type="hidden" name="act" value="create"/>
    <div class="experience_title">
        <p><input class="title_input" type="text" name="title" placeholder="<?php echo $lang->experience_content->title; ?>" /></p>
        <p><input class="keyword_input" type="text" name="keyword" placeholder="<?php echo $lang->experience_content->keyword; ?>" /></p>
        <p><input type="submit" value="<?php echo $lang->experience_content->save; ?>"/></p>
        <p>
            <?php echo $lang->experience_content->import_url; ?>
            <input type="text" id="import_url" />
            <input type="button" onclick="do_import();" value="import" />
        </p>
    </div>
    <div id="mdedit_box">
        <textarea id="mdedit_text" name="content"></textarea>
    </div>
    </form>
    <script type="text/javascript">
        var editor;
        function do_import(){
            var cap_url = $("#import_url").val();
            var target_url = "http://fuckyeahmarkdown.com/go/?u="+encodeURI(cap_url);
            $.get(
                target_url,
                function (result, status) {
                    testEditor.setValue( result );
                    $("#wait_box").hide();
                }
            );
            $("#wait_box").show();
        };
        $(document).ready(
            function (){
                $("#wait_box").hide();
                testEditor = editormd("mdedit_box", {
                    htmlDecode      : true,
                    height          : 640,
                    tocm            : true,
                    emoji           : true,
                    taskList        : true,
                    tex             : true,
                    flowChart       : true,
                    sequenceDiagram : true,
                    path            : "editor.md/lib/",
                    readOnly        : false,
                    saveHTMLToTextarea : true,
                    imageUpload     : true,
                    imageFormats    : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                    imageUploadURL  : "action.php",
                });
            }
        );
    </script>
    <div id="wait_box">
        <img src="images/wait.gif" />
    </div>
    <style type="text/css">
        .content {
            margin-left: auto;
            margin-right: auto;
            width:90%;
            min-width: 45em;
            clear:both;
        }
    </style>
<?php break; ?>

<?php case "edit": ?>
    <?php $search_result = $experience_db->find_by_id( $_GET["eid"] ); ?>
    <?php
    if( isset( $_GET["ecid"] ) ){
        $content_resault=$experience_content_db->find_by_id( $_GET["ecid"] );
        if( $content_resault ){
            $exp_content = $content_resault["content"];
        }
    } else {
        $history_list = $experience_content_db->find_by_exprence_id( $_GET["eid"] );
        if( $row = $history_list->fetch(PDO::FETCH_ASSOC) ){
            $exp_content = $row["content"];
        }
    }
    ?>
    <form action="action.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
    <input type="hidden" name="page" value="experience_content"/>
    <input type="hidden" name="act" value="edit"/>
    <input type="hidden" name="eid" value="<?php echo $_GET["eid"]; ?>"/>
    <div class="experience_title">
        <p><input class="title_input" type="text" name="title" placeholder="<?php echo $lang->experience_content->title; ?>" value="<?php echo $search_result["title"]; ?>" /></p>
        <p><input class="keyword_input" type="text" name="keyword" placeholder="<?php echo $lang->experience_content->keyword; ?>" value="<?php echo $search_result["keyword"]; ?>" /></p>
        <p><input type="submit" value="<?php echo $lang->experience_content->save; ?>"/></p>
    </div>
    <div id="history">
        <h4><?php echo $lang->experience_content->modify_history; ?></h4>
        <?php $history_list = $experience_content_db->find_by_exprence_id( $_GET["eid"] ); ?>
        <ul>
        <?php while( $row = $history_list->fetch(PDO::FETCH_ASSOC) ) { ?>
            <li>
                <a href="index.php?v=experience_content&a=edit&eid=<?php echo $_GET["eid"]; ?>&ecid=<?php echo $row["id"]; ?>">
                    <?php echo date("Y-m-d H:i", $row["timestamp"]); ?>
                    &nbsp;
                    <?php
                        $user = $user_db->find_by_id( $row["author_id"] );
                        if( $user ) {
                            echo $user["name"];
                        }
                    ?>
                </a>
            </li>
            <?php if( !isset($ecid) ) { $ecid = $row["id"]; } ?>
        <?php } ?>
        </ul>
    </div>
    <div id="mdedit_box">
        <textarea id="mdedit_text" name="content"><?php echo htmlentities($exp_content); ?></textarea>
    </div>
    </form>
    <script type="text/javascript">
        $(document).ready(
            function(){
                editormd("mdedit_box", {
                    // markdown        : content,
                    height          : 640,
                    htmlDecode      : true,
                    tocm            : true,
                    emoji           : true,
                    taskList        : true,
                    tex             : true,
                    flowChart       : true,
                    sequenceDiagram : true,
                    path            : "editor.md/lib/",
                    readOnly        : false,
                    saveHTMLToTextarea : true,
                    imageUpload     : true,
                    imageFormats    : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
                    imageUploadURL  : "action.php",
                });
            }
        );
    </script>
    <style type="text/css">
        .content {
            margin-left: auto;
            margin-right: auto;
            width:90%;
            min-width: 45em;
            clear:both;
        }
    </style>
<?php break; ?>

<?php case "view": ?>
<?php default: ?>
    <?php $search_result = $experience_db->find_by_id( $_GET["eid"] ); ?>
    <?php
    if( isset( $_GET["ecid"] ) ) {
        $content_resault=$experience_content_db->find_by_id( $_GET["ecid"] );
        if( $content_resault ){
            $exp_content = $content_resault["content"];
        }
    } else {
        $content_resault=$experience_content_db->find_by_exprence_id( $_GET["eid"] );
        if( $row = $content_resault->fetch(PDO::FETCH_ASSOC) ){
            $exp_content = $row["content"];
        }
    } ?>
    <div class="experience_title">
        <h1><?php echo $search_result["title"]; ?></h1>
        <h3><?php echo $search_result["keyword"]; ?></h3>
        <?php if(check_permission("experience_edit")){ ?>
        <p class="hilight_action">
            <a href="index.php?v=experience_content&a=edit&eid=<?php echo $search_result["id"] ?>" target="_self"><?php echo $lang->experience_content->edit; ?></a>
        </p>
        <?php } ?>
    </div>
    <div id="history">
        <h4><?php echo $lang->experience_content->modify_history; ?></h4>
        <?php $history_list = $experience_content_db->find_by_exprence_id( $_GET["eid"] ); ?>
        <ul>
        <?php while( $row = $history_list->fetch(PDO::FETCH_ASSOC) ) { ?>
            <li>
                <a href="index.php?v=experience_content&a=view&eid=<?php echo $_GET["eid"]; ?>&ecid=<?php echo $row["id"]; ?>">
                    <?php echo date("Y-m-d H:i", $row["timestamp"]); ?>
                    &nbsp;
                    <?php
                        $user = $user_db->find_by_id( $row["author_id"] );
                        if( $user ) {
                            echo $user["name"];
                        }
                    ?>
                </a>
            </li>
            <?php if( !isset($ecid) ) { $ecid = $row["id"]; } ?>
        <?php } ?>
        </ul>
    </div>
    <br/>
    <div id="mdedit_box">
        <textarea id="mdedit_text" name="content"><?php echo htmlentities($exp_content); ?></textarea>
    </div>
    <script type="text/javascript">
        $(document).ready(
            function() {
                editormd.markdownToHTML("mdedit_box", {
                    // markdown        : content,
                    htmlDecode      : true,
                    // htmlDecode      : "style,script,iframe",
                    //toc             : false,
                    tocm            : true,
                    //tocContainer    : "#custom-toc-container",
                    //gfm             : false,
                    //tocDropdown     : true,
                    // markdownSourceCode : true,
                    emoji           : true,
                    taskList        : true,
                    tex             : true,
                    flowChart       : true,
                    sequenceDiagram : true,
                });
            }
        );
    </script>
<?php break; ?>
<?php } ?>

<link rel="stylesheet" href="editor.md/css/editormd.min.css" />
<script src="editor.md/editormd.min.js"></script>
<script src="editor.md/lib/marked.min.js"></script>
<script src="editor.md/lib/prettify.min.js"></script>
<script src="editor.md/lib/raphael.min.js"></script>
<script src="editor.md/lib/underscore.min.js"></script>
<script src="editor.md/lib/sequence-diagram.min.js"></script>
<script src="editor.md/lib/flowchart.min.js"></script>
<script src="editor.md/lib/jquery.flowchart.min.js"></script>

