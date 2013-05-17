<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<body>
<form action="phptest.php" method="post">
姓名: <input type="text" name="username"/><br/>
性别:<input type="radio" value="0" name="sex" checked>男生 
      <input type="radio" value="1"  name="sex" checked>女生<br/>
身高: <input type="text" name="height" />（cm）<br/>
体重: <input type="text"  name="weight" />（kg)<br/>
<input type="submit" name="submit" value="提交"/>
</form>
<?php
 $submit=isset($_POST['submit'])?$_POST['submit']:null;
 $username=isset($_POST['username'])?$_POST['username']:null;
 $height=isset($_POST['height'])?$_POST['height']:null;
 $weight=isset($_POST['weight'])?$_POST['weight']:null;
//通过判断按钮的变量名是否在$_POST中定义，如果有表示该表单已提交
if ($username)
       {
		   filter_var($username, FILTER_SANITIZE_STRING);
		   if (preg_match("/[\xe0-\xef][\x80-\xbf]+$/", $username))
	          echo "姓名：".$username."<br/>";
	       else
	          echo "请输入中文名字<br/>";
      }
/*if($sex)
 {
	  if($sex===0)
		  echo "性别：男<br/>";
	  else
	      echo "性别：女<br/>";   
 }*/
	  
if($height)
    {
		if(!filter_var($height, FILTER_VALIDATE_INT))
           {
             echo("输入的字符无效,只能输入整型.<br/>");
           }
       else
           {
             echo("输入的字符有效<br/>");
           }
	   if (preg_match("/^\d{2,3}$/", $height))
		    echo "身高：".$height."cm<br/>";
		 else
		    echo "请输入你的身高<br/>";
	}
 if($weight)
    {
		if(!filter_var($weight, FILTER_VALIDATE_FLOAT))
           {
             echo("输入的字符无效.<br/>");
           }
       else
           {
             echo("输入的字符有效<br/>");
           }
		 
		 if (preg_match("/^[0-9]*$/", $weight))
		    echo "体重:".$weight."kg<br/>";
		 else
		    echo "请输入你的体重<br/>";
	}
  //(身高cm－80)×70﹪=标准体重 
 $normal=($height-80)*0.7;
 if($username&&$height&&$weight)
 {
      if($normal==$weight)
        {
	      echo "您的身体健康，符合国际标准";
        }
      elseif($normal>$weight)
        {
	      echo "您的身体偏胖，高于国际标准";
        }
      else
        {
	      echo "您的身体偏瘦，高于国际标准";
        }
  }

?>
</body>
</html>