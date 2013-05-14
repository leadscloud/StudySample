<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>测试身高</title>
</head>
<body>
<form action="" method="post">
  Name:
  <input name="name" />
  <br />
  <input type="radio" name="sex" value="male" checked="checked" />
  Male
  <input type="radio" name="sex" value="female"  />
  Female <br />
  Height:
  <input name="height" />
  Cm<br />
  Weight:
  <input name="weight" />
  Kg<br />
  <input type="submit" value="Submit" name="submit" />
  <br />
</form>
<?php
if (isset($_POST['submit']))
{
$name=$_POST['name'];
$sex=$_POST['sex'];
$height=$_POST['height'];
$weight=$_POST['weight'];

$int_options = array("options"=>
array("min_range"=>100, "max_range"=>260));
$x=var_dump(filter_var($height, FILTER_VALIDATE_INT, $int_options));
$int_options = array("options"=>
array("min_range"=>20, "max_range"=>400));
$y=var_dump(filter_var($weight, FILTER_VALIDATE_INT, $int_options));

if ($name==""||$height==""||$weight==""){
   echo "请输入完整信息";
	}
else if(!$y="bool(false)"){
	
	$norm=$weight/($height*$height)*10000;
	
	if($norm>20&&$norm<25){
		echo $name." 你的身材不错，挺健康。".$norm;
		}
	else if($norm<20){
		echo $name." 你有点瘦，多吃点饭。".$norm;
		}
	else if($norm>25){
		echo $name." 你有点胖，需要锻炼。".$norm;
		}

	}
	
}

?>
</body>
</html>
