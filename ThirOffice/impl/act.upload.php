<?php

if( $_POST["act"] == "image" ) {
    header("Content-Type:text/html; charset=utf-8");
    header("Access-Control-Allow-Origin: *");
    require("editor.md/examples/php/editormd.uploader.class.php");

	$path     = __DIR__ . DIRECTORY_SEPARATOR;
	$url      = dirname($_SERVER['PHP_SELF']) . '/';
	$savePath = realpath($path . '../uploads') . DIRECTORY_SEPARATOR;
	$saveURL  = 'uploads/';

	$formats  = array(
		'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp')
	);

    $name = 'editormd-image-file';

    if (isset($_FILES[$name]))
    {        
        $imageUploader = new EditorMdUploader($savePath, $saveURL, $formats['image'], 1, 'YmdHi');  // Ymdhis表示按日期生成文件名，利用date()函数

        $imageUploader->config(array(
            'maxSize' => 2048,
            'cover'   => true
        ));

        if ($imageUploader->upload($name))
        {
            $imageUploader->message("up_success: savePath=$savePath saveURL=$saveURL", 1);
        }
        else
        {
            $imageUploader->message("up_error: savePath=$savePath saveURL=$saveURL", 0);
        }
    } 
    exit;
}
?>