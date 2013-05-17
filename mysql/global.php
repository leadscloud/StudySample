<?php
/**
 * 公共文件，程序中的其它文件都会加载此文件
 * 定义一些常量及加载一些必备的文件，你需要了解变量的作用域
 */
header("Content-Type:text/html; charset=utf-8");
// 设置错误等级
error_reporting() === E_ALL & ~E_NOTICE or error_reporting(E_ALL & ~E_NOTICE);
// 定义项目物理跟路径
define('ABS_PATH',dirname(__FILE__));

// 公共函数库， 以后可以把此文件放到一个名为functions.php的文件里
// include ABS_PATH.'/functions.php';
// =======函数开始=========

/**
 * 自定义错误处理
 * @param   int	 $errno		必需。为用户定义的错误规定错误报告级别。必须是一个值数。
 * @return  $errstr			必需。为用户定义的错误规定错误消息。
 * @access  $errfile		可选。规定错误在其中发生的文件名。
 * @param	int $errline	可选。规定错误发生的行号。
 * @param	array $errcontext	可选。规定一个数组，包含了当错误发生时在用的每个变量以及它们的值。
 * @static  makes the class property accessible without needing an instantiation of the class
 */
function handler_error($errno,$errstr,$errfile,$errline,$errcontext)
{
    if (E_STRICT===$errno) return true;
	return throw_error($errstr,$errno,$errfile,$errline,$errcontext);
} // end handler_error


/**
 * 错误处里函数
 *
 * @param  $errstr          错误消息
 * @param int $errno        异常类型
 * @return bool
 * http://php.net/manual/en/function.set-error-handler.php
 */
function throw_error($errstr,$errno=E_NOTICE,$errfile=null,$errline=0,$errcontext=array())
{
	$string  = $file = null;
	//debug_backtrace 可以回溯跟踪函数的调用信息,用于调试信息
	$backtrace = array_reverse(debug_backtrace());
	$error   = $traces[0]; unset($traces[0]);
    $errfile = $errfile ? $errfile : $error['file'];
    $errline = $errline ? $errline : $error['line'];
	//如果设置不显示错误便停止输出
	if (error_reporting() === 0) return false;
	//显示跟踪的信息
	foreach($traces as $i=>$trace) {
		$file  = isset($trace['file']) ? $trace['file'] : $file;
		$line  = isset($trace['line']) ? $trace['line'] : null;
		$class = isset($trace['class']) ? $trace['class'] : null;
		$type  = isset($trace['type']) ? $trace['type'] : null;
		$args  = isset($trace['args']) ? $trace['args'] : null;
		$function  = isset($trace['function']) ? $trace['function'] : null;
		$string   .= "\t#".$i.' ['.date("y-m-d H:i:s").'] '.$file.($line?'('.$line.') ':' ');
		$string   .= $class.$type.$function.'(';
		if (is_array($args)) {
            $arrs = array();
            foreach ($args as $v) {
                if (is_object($v)) {
                    $arrs[] = implode(' ',get_object_vars($v));
                } else {
                    $error_level = error_reporting(0);
                    $vars = print_r($v,true);
                    error_reporting($error_level);
                    while (strpos($vars,chr(32).chr(32))!==false) {
                        $vars = str_replace(chr(32).chr(32),chr(32),$vars);
                    }
                    $arrs[] = $vars;
                }
            }
            $string.= str_replace("\n",'',implode(', ',$arrs));
        }
        $string.=")\r\n";
	}
	
	//var_export函数返回关于传递给该函数的变量的结构信息，它和 var_dump() 类似，不同的是其返回的表示是合法的 PHP 代码。
	$context = var_export($errcontext, TRUE);
    $log = "[Message]:\r\n\t{$errstr}\r\n";
    $log.= "[File]:\r\n\t{$errfile} ({$errline})\r\n";
    $log.= $context?"[Context]:\r\n{$context}\r\n":'';
	$log.= $string?"[Trace]:\r\n{$string}\r\n":'';
    // 记录日志
    error_log($log, 3, ABS_PATH.'/error.log');
	// 处里错误
	$errorType = array (
               E_ERROR			=> 'ERROR',
               E_WARNING        => 'WARNING',
               E_PARSE          => 'PARSING ERROR',
               E_NOTICE         => 'NOTICE',
               E_CORE_ERROR     => 'CORE ERROR',
               E_CORE_WARNING   => 'CORE WARNING',
               E_COMPILE_ERROR  => 'COMPILE ERROR',
               E_COMPILE_WARNING => 'COMPILE WARNING',
               E_USER_ERROR     => 'USER ERROR',
               E_USER_WARNING   => 'USER WARNING',
               E_USER_NOTICE    => 'USER NOTICE',
               E_STRICT         => 'STRICT NOTICE',
               E_RECOVERABLE_ERROR  => 'RECOVERABLE ERROR'
    );
	// create error message
    if (array_key_exists($errno, $errorType)) {
        $err = $errorType[$errno];
    } else {
        $err = 'CAUGHT EXCEPTION';
    }
	$log = $err.'\r\n'.$log;
    switch ($errno) {
        case E_ERROR:
            // 格式化为HTML
            $html = str_replace("\t",str_repeat('&nbsp; ',2),nl2br(esc_html($log)));
            // 格式化成HTML完成页面
            $html = error_page('系统错误',$html,true);
            // 输出错误信息，并停止程序
            echo $html; exit();
            break;
        case E_WARNING: case E_NOTICE:
			// 格式化为HTML
			$html = str_replace("\t",str_repeat('&nbsp; ',2),nl2br(esc_html($log)));
			// 格式化成HTML完成页面
			$html = error_page('系统错误',$html,true);
            echo $html;
            break;
        default: break;
    }
} // end throw_error

/**
 * 错误页面
 *
 * @param string $title
 * @param string $content
 * @param bool $is_full     是否输出完整页面
 * @return string
 */
function error_page($title,$content,$is_full=false) {
    // CSS
    $css = '<style type="text/css">';
    $css.= '.alert-error{color:#b94a48;background-color:#f2dede;border-color:#eed3d7}.alert-error h4{color:#b94a48}';
    $css.= '.alert{padding:8px 35px 8px 14px;margin-bottom:20px;text-shadow:0 1px 0 rgba(255,255,255,0.5);background-color:#fcf8e3;border:1px solid #fbeed5;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px}';
    $css.= '#error-title { width:500px; border-bottom:solid 1px #B5B5B5; margin:0 0 15px 80px; }';
    $css.= '#error-title h1{ font-size: 25px; margin:10px 0 5px 0; }';
    $css.= '#error-content,#error-buttons { margin:10px 0 10px 80px; }';
    if ($is_full) {
        $css.= 'body { margin:10px 20px; font-family: Verdana; color: #333333; background:#FAFAFA; font-size: 12px; line-height: 1.5; }';
        $css.= '#error-page { width:900px; margin:15px auto; }';
        $css.= '#error-title { width:800px;}';
    }
    $css.= '</style>';
    // Page
    $page = '<div id="error-page" class="alert alert-error">';
    $page.= '<div id="error-title"><h1>'.$title.'</h1></div>';
    $page.= '<div id="error-content">'.$content.'</div>';
    $page.= '<div id="error-buttons"><button type="button" onclick="window.history.back();">返回</button></div>';
    $page.= '</div>';

    if ($is_full) {
        $hl = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $hl.= '<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $hl.= '<title>'.$title.'</title>';
        $hl.= $css.'</head><body>'.$page;
        $hl.= '</body></html>';
    } else {
    	$hl = $css.$page;
    }
    return $hl;
}
/**
 * 转换特殊字符为HTML实体
 *
 * @param   string $str
 * @return  string
 */
function esc_html($str){
    if(empty($str)) {
        return $str;
    } elseif (is_array($str)) {
		$str = array_map('esc_html', $str);
	} elseif (is_object($str)) {
		$vars = get_object_vars($str);
		foreach ($vars as $key=>$data) {
			$str->{$key} = esc_html($data);
		}
	} else {
        $str = htmlspecialchars($str);
    }
    return $str;
}


// 处理错误
set_error_handler('handler_error');
?>