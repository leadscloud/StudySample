<?php
$php_file = basename(__FILE__);
// 加载公共文件
include dirname(__FILE__).'/global.php';
//加载头部文件
include 'header.php';
$method = isset($_GET['method'])?$_GET['method']:null;
switch($method){
	case 'new':
		manage_page('add');
		break;
	case 'edit':
		manage_page('edit');
		break;
	case 'delete':
		$postid = isset($_POST['postid'])?$_POST['postid']:null;
		$conn = mysql_connect("localhost","root","");
		mysql_select_db("test", $conn);
		if(mysql_query("DELETE FROM `domain` WHRER `postid`=".$postid)){
			//删除成功，显示你想显示的html页面。
			echo '删除成功。';
		}else{
			echo '删除失败！';
		}
		break;
	case 'save':
		//保存数据提交，并且验证是POST提交
		if(isset($_POST['submit'])) {
			$postid 	= isset($_POST['postid'])?$_POST['postid']:null;
			$domain 	= isset($_POST['domain'])?$_POST['domain']:null;
			$name 		= isset($_POST['name'])?$_POST['name']:null;
			$nickname 	= isset($_POST['nickname'])?$_POST['nickname']:null;
			$ip 		= isset($_POST['ip'])?$_POST['ip']:null;
			$ftphost 	= isset($_POST['ftphost'])?$_POST['ftphost']:null;
			$ftpuser 	= isset($_POST['ftpuser'])?$_POST['ftpuser']:null;
			$ftppass 	= isset($_POST['ftppass'])?$_POST['ftppass']:null;
			$datetime 	= isset($_POST['datetime'])?$_POST['datetime']:date('Y-m-d H:i:s');
			$edittime 	= isset($_POST['edittime'])?$_POST['edittime']:date('Y-m-d H:i:s');
			$note 		= isset($_POST['note'])?$_POST['note']:null;
			//保存
			$conn = mysql_connect("localhost","root","");
			mysql_select_db("test", $conn);
			mysql_query("SET NAMES utf8;", $conn);
			if($postid) {
				//编辑
				$result = mysql_query("UPDATE `domain` SET `domain`='$domain',`name`='$name',`nickname`='$nickname',`ip`='$ip',`ftphost`='$ftphost',`ftpuser`='$ftpuser',`edittime`='$edittime' WHERE `postid`=$postid", $conn);
				$text = '更新成功';
			} else {
				//添加
				$result = mysql_query("INSERT INTO `domain`(`domain`,`name`,`nickname`,`ip`,`ftphost`,`ftpuser`,`ftppass`) VALUES ('$domain','$name','$nickname','$ip','$ftphost','$ftpuser','$ftppass')", $conn);
				$text = '添加成功';
			}
			
			if($result)
				echo '<div  class="alert alert-success">'.$text.'</div>';
		}
		break;
	default:
		//数据库连接
		$conn = mysql_connect("localhost","root","");
		mysql_select_db("test", $conn);
		mysql_query("SET NAMES utf8;", $conn);
		$result = mysql_query("SELECT * FROM `domain`", $conn);

		echo '<div class="row-fluid">';
		echo   '<div class="span12">';
		echo     '<table class="table table-striped table-bordered">';
		echo       '<thead>';
		echo         '<tr>';
		echo		   '<th style="width:20px"><input type="checkbox" name="select" value="all"></th>';
		echo           '<th>域名</th>';
		echo           '<th>IP</th>';
		echo           '<th>用户名</th>';
		echo           '<th>昵称</th>';
		echo           '<th>FTP地址</th>';
		echo           '<th>FTP用户名</th>';
		echo           '<th>FTP密码</th>';
		echo           '<th>添加日期</th>';
		echo           '<th>编辑日期</th>';
		echo           '<th>备注</th>';
		echo           '<th style="width:130px;">动作</th>';
		echo         '</tr>';
		echo       '</thead>';

		echo       '<tbody>';
		//显示所有数据
		if($result) {
			while($row = mysql_fetch_array($result)){
				echo   '<tr>';
				echo     '<td><input type="checkbox" name="listids[]" value="'.$row['postid'].'"></td>';
				echo     '<td>'.$row['domain'].'</td>';
				echo     '<td>'.$row['ip'].'</td>';
				echo     '<td>'.$row['name'].'</td>';
				echo     '<td>'.$row['nickname'].'</td>';
				echo     '<td>'.$row['ftphost'].'</td>';
				echo     '<td>'.$row['ftpuser'].'</td>';
				echo     '<td>'.$row['ftppass'].'</td>';
				echo     '<td>'.$row['datetime'].'</td>';
				echo     '<td>'.$row['edittime'].'</td>';
				echo     '<td>'.$row['note'].'</td>';
				echo     '<td><a class="btn" href="mysql.php?method=edit&postid='.$row['postid'].'">编辑</a> <a class="btn" href="mysql.php?method=delete&postid='.$row['postid'].'">删除</a></td>';
				echo   '</tr>';
			}
		} else {
			echo '<tr rowspan="12">无记录！</tr>';
		}
		echo       '</tbody>';
		echo     '</table>';
		echo   '</div>';
		echo '</div>';
		//关闭数据库连接
		mysql_close($conn);
		break;
}


/**
 * 管理编辑页面
 * @param   string    $action    添加或编辑
 */
function manage_page($action){
	global $php_file;
	$postid 	= isset($_GET['postid'])?$_GET['postid']:null;
	
	//获取内容
	$conn = mysql_connect("localhost","root","");
	mysql_select_db("test", $conn);
	mysql_query("SET NAMES utf8;", $conn);
	$result = mysql_query("SELECT * FROM `domain` WHERE `postid`=".$postid, $conn);
	if($result) {
		$_DATA = mysql_fetch_assoc($result);
	}
	
	$domain 	= isset($_DATA['domain'])?$_DATA['domain']:null;
	$name 		= isset($_DATA['name'])?$_DATA['name']:null;
	$nickname 	= isset($_DATA['nickname'])?$_DATA['nickname']:null;
	$ip 		= isset($_DATA['ip'])?$_DATA['ip']:null;
	$ftphost 	= isset($_DATA['ftphost'])?$_DATA['ftphost']:null;
	$ftpuser 	= isset($_DATA['ftpuser'])?$_DATA['ftpuser']:null;
	$ftppass 	= isset($_DATA['ftppass'])?$_DATA['ftppass']:null;
	$datetime 	= isset($_DATA['datetime'])?$_DATA['datetime']:null;
	$edittime 	= isset($_DATA['edittime'])?$_DATA['edittime']:null;
	$note 		= isset($_DATA['note'])?$_DATA['note']:null;


	echo '<form class="form-horizontal" method="POST" action="'.$php_file.'?method=save">';
	echo   '<ul class="nav nav-tabs">';
	echo     '<li><a href="#general" data-toggle="tab" class="active">基本信息</a></li>';
	echo     '<li><a href="#ftpinfo" data-toggle="tab">FTP信息</a></li>';
	echo     '<li><a href="#otherinfo" data-toggle="tab">其他信息</a></li>';
	echo   '</ul>';
	echo   '<div class="tab-content">';

	echo     '<div class="tab-pane active" id="general">';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">域名</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$domain.'" placeholder="请输入你的域名" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">IP地址</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$ip.'" placeholder="IP地址" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">所属人</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$name.'" placeholder="所属人" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">昵称</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$nickname.'" placeholder="昵称" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo     '</div>';

	echo     '<div class="tab-pane active" id="ftpinfo">';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">FTP地址</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$ftphost.'" placeholder="FTP地址" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">FTP用户名</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$ftpuser.'" placeholder="FTP用户名" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">FTP密码</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$ftppass.'" placeholder="FTP密码" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo     '</div>';

	echo     '<div class="tab-pane active" id="otherinfo">';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">添加日期</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$datetime.'" placeholder="添加日期" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">修改日期</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$edittime.'" placeholder="修改日期" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo       '<div class="control-group">';
	echo         '<label class="control-label" for="input01">备注</label>';
	echo         '<div class="controls">';
	echo           '<input type="text" name="domain" value="'.$note.'" placeholder="备注" class="input-xlarge">';
	echo         '</div>';
	echo       '</div>';
	echo     '</div>';

	echo   '</div>';
	if($action!='add') {
		echo '<input type="hidden" name="postid" value="'.$postid.'">';
	}
	echo   '<button type="submit" name="submit" class="btn btn-primary">确定</button>';
	echo '</form>';
	//关闭数据库连接
	mysql_close($conn);
}
//加载底部文件
include 'header.php';
?>
