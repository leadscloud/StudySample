<?php
require("global.php");
// modify by zhanglei

$php_file = basename(__FILE__);
//echo PHP_FILE;

$method =isset($_GET['method'])?$_GET['method']:null;
switch($method){
	case 'upload':
		if(isset($_POST['submit'])){
			if ($_FILES["file"]["type"] == "text/plain"){
				if ($_FILES["file"]["error"] > 0)
					$html = "上传文件发生错误：" . $_FILES["file"]["error"];
				else {
					 move_uploaded_file($_FILES["file"]["tmp_name"],
		  "upload/" . $_FILES["file"]["name"]);
					$html = '上传成功！';
				}
			}else {
				$html = '非法文件';
			}
			echo $html;
		}
		break;
	case 'edit':
		$filename = isset($_GET['filename'])?$_GET['filename']:null;
		echo '<form method="POST" action="'.$php_file.'?method=save" >';
		echo   '<textarea rows="10" cols="50" name="content">';
		echo		file_get_contents(ABS_PATH.'/upload/'.$filename);
		echo   '</textarea>';
		echo   '<input type="hidden" name="filename" value="'.$filename.'" />';
		echo   '<input type="submit" name="submit" value="Submit" />';
		echo '</form>';
		break;
	case 'save':
		$content = isset($_POST['content'])?$_POST['content']:null;
		$filename = isset($_POST['filename'])?$_POST['filename']:null;
		file_put_contents('upload/'.$filename,$content);
		echo '保存成功<a href="index.php"></a>';
		break;
	default:
		//加载头部
		include 'header.php';
		echo '<form action="'.$php_file.'?method=upload" method="POST" enctype="multipart/form-data">';
        echo 	'<label for="file">Filename:</label>';
        echo 	'<input type="file" name="file" id="file" />';
       	echo 	'<input type="submit" name="submit" value="Submit" />';
        echo '</form>';
		
		//path to directory to scan
		$directory = "./upload/";
 
		//get all image files with a .jpg extension.
		$text = glob($directory . "*.txt");
		//print each file name
		foreach($text as $txt)
		{
			echo $txt . '<a href="'.$php_file.'?method=edit&filename='.substr($txt,9).'">编辑</a>';
		}
		//加载底部
		include 'footer.php';
		break;
}
?>
