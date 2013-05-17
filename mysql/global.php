<?php
/**
 * �����ļ��������е������ļ�������ش��ļ�
 * ����һЩ����������һЩ�ر����ļ�������Ҫ�˽������������
 */
header("Content-Type:text/html; charset=utf-8");
// ���ô���ȼ�
error_reporting() === E_ALL & ~E_NOTICE or error_reporting(E_ALL & ~E_NOTICE);
// ������Ŀ�����·��
define('ABS_PATH',dirname(__FILE__));

// ���������⣬ �Ժ���԰Ѵ��ļ��ŵ�һ����Ϊfunctions.php���ļ���
// include ABS_PATH.'/functions.php';
// =======������ʼ=========

/**
 * �Զ��������
 * @param   int	 $errno		���衣Ϊ�û�����Ĵ���涨���󱨸漶�𡣱�����һ��ֵ����
 * @return  $errstr			���衣Ϊ�û�����Ĵ���涨������Ϣ��
 * @access  $errfile		��ѡ���涨���������з������ļ�����
 * @param	int $errline	��ѡ���涨���������кš�
 * @param	array $errcontext	��ѡ���涨һ�����飬�����˵�������ʱ���õ�ÿ�������Լ����ǵ�ֵ��
 * @static  makes the class property accessible without needing an instantiation of the class
 */
function handler_error($errno,$errstr,$errfile,$errline,$errcontext)
{
    if (E_STRICT===$errno) return true;
	return throw_error($errstr,$errno,$errfile,$errline,$errcontext);
} // end handler_error


/**
 * �����ﺯ��
 *
 * @param  $errstr          ������Ϣ
 * @param int $errno        �쳣����
 * @return bool
 * http://php.net/manual/en/function.set-error-handler.php
 */
function throw_error($errstr,$errno=E_NOTICE,$errfile=null,$errline=0,$errcontext=array())
{
	$string  = $file = null;
	//debug_backtrace ���Ի��ݸ��ٺ����ĵ�����Ϣ,���ڵ�����Ϣ
	$backtrace = array_reverse(debug_backtrace());
	$error   = $traces[0]; unset($traces[0]);
    $errfile = $errfile ? $errfile : $error['file'];
    $errline = $errline ? $errline : $error['line'];
	//������ò���ʾ�����ֹͣ���
	if (error_reporting() === 0) return false;
	//��ʾ���ٵ���Ϣ
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
	
	//var_export�������ع��ڴ��ݸ��ú����ı����Ľṹ��Ϣ������ var_dump() ���ƣ���ͬ�����䷵�صı�ʾ�ǺϷ��� PHP ���롣
	$context = var_export($errcontext, TRUE);
    $log = "[Message]:\r\n\t{$errstr}\r\n";
    $log.= "[File]:\r\n\t{$errfile} ({$errline})\r\n";
    $log.= $context?"[Context]:\r\n{$context}\r\n":'';
	$log.= $string?"[Trace]:\r\n{$string}\r\n":'';
    // ��¼��־
    error_log($log, 3, ABS_PATH.'/error.log');
	// �������
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
            // ��ʽ��ΪHTML
            $html = str_replace("\t",str_repeat('&nbsp; ',2),nl2br(esc_html($log)));
            // ��ʽ����HTML���ҳ��
            $html = error_page('ϵͳ����',$html,true);
            // ���������Ϣ����ֹͣ����
            echo $html; exit();
            break;
        case E_WARNING: case E_NOTICE:
			// ��ʽ��ΪHTML
			$html = str_replace("\t",str_repeat('&nbsp; ',2),nl2br(esc_html($log)));
			// ��ʽ����HTML���ҳ��
			$html = error_page('ϵͳ����',$html,true);
            echo $html;
            break;
        default: break;
    }
} // end throw_error

/**
 * ����ҳ��
 *
 * @param string $title
 * @param string $content
 * @param bool $is_full     �Ƿ��������ҳ��
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
    $page.= '<div id="error-buttons"><button type="button" onclick="window.history.back();">����</button></div>';
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
 * ת�������ַ�ΪHTMLʵ��
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


// �������
set_error_handler('handler_error');
?>