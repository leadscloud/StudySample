<?php
$php_file = basename(__FILE__);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Mysql Connect Test</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
			<link rel="stylesheet" href="css/font-awesome-ie7.min.css">
        <![endif]-->

        <!-- Add your site or application content here -->

		<div class="navbar navbar-inverse navbar-fixed-top">
		  <div class="navbar-inner">
			<div class="container">
			  <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="brand" href="#">域名管理系统</a>
			  <div class="nav-collapse collapse">
				<ul class="nav">
				  <li class="active"><a href="<?php echo $php_file;?>">首页</a></li>
				  <li><a href="<?php echo $php_file.'?method=new';?>">添加</a></li>
				  <li><a href="#contact">关于</a></li>
				</ul>
			  </div><!--/.nav-collapse -->
			</div>
		  </div>
		</div>
		<div class="container">
		  		<?php
					$php_file = basename(__FILE__);
					$method 	= isset($_GET['method'])?$_GET['method']:null;
					switch($method){
						case 'new':
							manage_page('add');
							break;
						case 'edit':
							manage_page('edit');
							break;
						case 'save':
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
							
						
							break;
						default:
							//数据库连接
							$conn = mysql_connect("localhost","root","");
							mysql_select_db("test", $conn);
							$result = mysql_query("SELECT * FROM `domain`", $conn);
							
							echo '<table class="table table-bordered table-hover">';
							echo   '<tr>';
							echo     '<th>域名</th>';
							echo     '<th>IP</th>';
							echo     '<th>用户名</th>';
							echo     '<th>昵称</th>';
							echo     '<th>FTP地址</th>';
							echo     '<th>FTP用户名</th>';
							echo     '<th>FTP密码</th>';
							echo     '<th>添加日期</th>';
							echo     '<th>编辑日期</th>';
							echo     '<th>备注</th>';
							echo     '<th style="width:130px;">动作</th>';
							echo   '</tr>';
							//显示所有数据
							if($result) {
								while($row = mysql_fetch_array($result)){
									echo   '<tr>';
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
								echo '<tr rowspan="11">无记录！</tr>';
							}
							
							echo '</table>';
							mysql_close($conn);
							break;
					}
					
					
					
					if(isset($_POST['submit'])) {
					}
					
					function manage_page($action){
						global $php_file;
						$postid 	= isset($_GET['postid'])?$_GET['postid']:null;
						
						//获取内容
						$conn = mysql_connect("localhost","root","");
						mysql_select_db("test", $conn);
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
						
						
					?>
					  <form class="form-horizontal" method="POST" action="<?php echo $php_file.'?method=save';?>">
						<ul class="nav nav-tabs">
						  <li><a href="#general" data-toggle="tab" class="active">基本信息</a></li>
						  <li><a href="#ftpinfo" data-toggle="tab">FTP信息</a></li>
						  <li><a href="#otherinfo" data-toggle="tab">其他信息</a></li>
						</ul>
						<div class="tab-content">
						  <div class="tab-pane active" id="general">
							<div class="control-group">
							  <label class="control-label" for="input01">域名</label>
							  <div class="controls">
								<input type="text" name="domain" value="<?php echo $domain;?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="input01">IP地址</label>
							  <div class="controls">
								<input type="text" name="ip" value="<?php echo $ip?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="input01">所属人</label>
							  <div class="controls">
								<input type="text" name="name" value="<?php echo $name;?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="input01">昵称</label>
							  <div class="controls">
								<input type="text" name="nickname" value="<?php echo $nickname;?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
						  </div>
						  <div class="tab-pane" id="ftpinfo">
							<div class="control-group">
							  <label class="control-label" for="input01">FTP地址</label>
							  <div class="controls">
								<input type="text" name="ftphost" value="<?php echo $ftphost;?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="input01">FTP用户名</label>
							  <div class="controls">
								<input type="text" name="ftpuser" value="<?php echo $ftpuser;?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="input01">FTP密码</label>
							  <div class="controls">
								<input type="text" name="ftppass" value="<?php echo $ftppass;?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
						  </div>
						  <div class="tab-pane" id="otherinfo">
							<div class="control-group">
							  <label class="control-label" for="input01">添加日期</label>
							  <div class="controls">
								<input type="text" name="datetime" value="<?php echo $datetime;?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="input01">修改日期</label>
							  <div class="controls">
								<input type="text" name="edittime" value="<?php echo $edittime;?>" placeholder="" class="input-xlarge">
							  </div>
							</div>
							<div class="control-group">
							  <label class="control-label" for="input01">备注</label>
							  <div class="controls">
								<textarea class="span2" name="note" rows="10"><?php echo $note;?></textarea>
							  </div>
							</div>
						  </div>
						  
						</div>
						<?php
						if($action!='add') {
							echo '<input type="hidden" name="postid" value="'.$postid.'">';
						}
						?>
						<button type="submit" name="submit" class="btn btn-primary">确定</button>
					  </form>
					<?php
						mysql_close($conn);
					}
					
				?>
		  
		</div>


        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.0.min.js"><\/script>')</script>
		<script src="js/bootstraped.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>