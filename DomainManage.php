<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Domain Manage</title>
</head>
<body style="font-size:12px; font-weight:normal; padding-top:60px; margin:0 auto; width:1100px;">
<h1>Domain Manage</h1>

<?php
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
 
if (mysql_query("CREATE DATABASE IF NOT EXISTS domainmanage",$con))
  {
  }
else
  {
  echo "Error creating database: " . mysql_error();
  }
mysql_select_db("domainmanage", $con);
$sql = "CREATE TABLE domains (
userid int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(userid),
DomainName varchar(60),
Belongs varchar(20),
Nickname varchar(20),
AddDate varchar(40),
ExpirDates varchar(40),
EditDate varchar(40),
IPAddress varchar(30),
FtpAddress varchar(20),
UserName varchar(15),
Passwords varchar(20),
Notes varchar(254)
)";
$endtimes =  date('Y-m-d H:i',time());
$php_file = basename(__FILE__);
$result = mysql_query("SELECT * FROM domains");
echo "<table  border='1' cellspacing='0' bordercolor='#000000'>
<tr>
<th>编号</th>
<th>域名</th>
<th>所属人</th>
<th>昵称</th>
<th>添加日期</th>
<th>过期日期</th>
<th>编辑日期</th>
<th>域名IP</th>
<th>FTP地址</th>
<th>FTP用户名</th>
<th>FTP密码</th>
<th>备注</th>
<th>操作</th>
</tr>";
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['userid'] . "</th>";
  echo "<td>" . $row['DomainName'] . "</th>";
  echo "<td>" . $row['Belongs'] . "</th>";
  echo "<td>" . $row['Nickname'] . "</th>";
  echo "<td>" . $row['AddDate'] . "</th>";
  echo "<td>" . $row['ExpirDates'] . "</th>";
  echo "<td>" . $row['EditDate'] . "</th>";
  echo "<td>" . $row['IPAddress'] . "</th>";
  echo "<td>" . $row['FtpAddress'] . "</th>";
  echo "<td>" . $row['UserName'] . "</th>";
  echo "<td>" . $row['Passwords'] . "</th>";
  echo "<td>" . $row['Notes'] . "</th>";
  echo "<td>" ."<a href=".$php_file."?method=del&id=".$row['userid'].">删除</a> "."<a href=".$php_file."?method=edit&id=".$row['userid'].">修改</a>". "</th>";
  echo "</tr>";
  }
  $method =isset($_GET['method'])?$_GET['method'].$row['userid']:null;
echo "<a style=' font-size:18px;' href=".$php_file."?method=add" . ">添加信息</a>";
echo "</table>";


switch($method){
  case 'add':
	echo '<form method="POST" action="">
	 域名:<input type="text" name="DomainName"></input><br />
	 所属人:<input type="text" name="Belongs"></input><br />
	 昵称:<input type="text" name="Nickname"></input><br />
	 添加日期:<input type="text" name="AddDate"></input><br />
	 过期日期:<input type="text" name="ExpirDates"></input><br />
	 FTP地址:<input type="text" name="FtpAddress"></input><br />
	 FTP用户名:<input type="text" name="UserName"></input><br />
	 FTP密码:<input type="text" name="Passwords"></input><br />
	 备注:<input type="text" name="Notes"></input><br />
	 <input type="submit" class="submit" name="submit" value="提交" />
	 </form>';
	$submit=isset($_POST['submit'])?$_POST['submit']:null;
	if(isset($_POST['submit'])){
	$DomainName=$_POST['DomainName'];
	$Belongs=$_POST['Belongs'];
	$Nickname=$_POST['Nickname'];
	$AddDate=$_POST['AddDate'];
	$ExpirDates=$_POST['ExpirDates'];
	$FtpAddress=$_POST['FtpAddress'];
	$UserName=$_POST['UserName'];
	$Passwords=$_POST['Passwords'];
	$Notes=$_POST['Notes'];
	$IPAddress = gethostbyname($DomainName);
	mysql_query("INSERT INTO domains (DomainName,Belongs,Nickname,AddDate,ExpirDates,EditDate,IPAddress,FtpAddress,UserName,Passwords,Notes) 
	VALUES ('$DomainName','$Belongs','$Nickname','$AddDate','$ExpirDates','$endtimes','$IPAddress','$FtpAddress','$UserName','$Passwords','$Notes')");
	
echo "信息添加成功 " . '<a href="' . $php_file . '">' . "返回首页" . '</a>';
	}
	break;

	

	case 'edit':
		$postid = isset($_GET['id'])?$_GET['id']:0;
		$result = mysql_query("SELECT * FROM domains WHERE userid=".$postid );
		if($result){
			$data = mysql_fetch_assoc($result);
		}
	echo '<form method="POST" action="">
	 域名:<input type="text" name="DomainName" value="'.$data['DomainName'].'");"></input><br />
	 所属人:<input type="text" name="Belongs" value="'.$data['Belongs'].'"></input><br />
	 昵称:<input type="text" name="Nickname" value="'.$data['Nickname'].'"></input><br />
	 添加日期:<input type="text" name="AddDate" value="'.$data['AddDate'].'"></input><br />
	 过期日期:<input type="text" name="ExpirDates" value="'.$data['ExpirDates'].'"></input><br />
	 FTP地址:<input type="text" name="FtpAddress" value="'.$data['FtpAddress']. '"></input><br />
	 FTP用户名:<input type="text" name="UserName" value="'.$data['UserName'].'"></input><br />
	 FTP密码:<input type="text" name="Passwords" value="'.$data['Passwords']. '"></input><br />
	 备注:<input type="text" name="Notes" value="' . $data['Notes'] . '"></input><br />
	 <input type="submit" class="submit" name="submit" value="提交" />
	 <input type="hidden" name="postid" value="' . $data['userid'] . '"></input>
	 </form>';

	$submit=isset($_POST['submit'])?$_POST['submit']:null;
	$postid = isset($_POST['postid'])?$_POST['postid']:0;
	if(isset($_POST['submit'])){
	$DomainName=$_POST['DomainName'];
	$Belongs=$_POST['Belongs'];
	$Nickname=$_POST['Nickname'];
	$AddDate=$_POST['AddDate'];
	$ExpirDates=$_POST['ExpirDates'];
	$FtpAddress=$_POST['FtpAddress'];
	$UserName=$_POST['UserName'];
	$Passwords=$_POST['Passwords'];
	$Notes=$_POST['Notes'];
	$IPAddress = gethostbyname($DomainName);
	mysql_query("UPDATE domains SET DomainName='$DomainName',Belongs='$Belongs',Nickname='$Nickname',AddDate='$AddDate',ExpirDates='$ExpirDates',EditDate='$endtimes',IPAddress='$IPAddress',FtpAddress='$FtpAddress',UserName='$UserName',Passwords='$Passwords',Notes='$Notes'  WHERE userid=$postid");
	
echo "信息修改成功 " . '<a href="' . $php_file . '">' . "返回首页" . '</a>';
	}
	break;
	case 'del':
		$postid = isset($_GET['id'])?$_GET['id']:0;
		$result = mysql_query("SELECT * FROM domains WHERE userid=".$postid );
		
	mysql_query("DELETE FROM domains WHERE userid=$postid");
	
echo "信息删除成功 " . '<a href="' . $php_file . '">' . "返回首页" . '</a>';
	
	break;
		}
mysql_query($sql,$con);
mysql_close($con);
?>
</body>
</html>
