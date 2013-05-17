<?php
header('Content-Type: text/html; charset=utf-8');
define("ABS_PATH",dirname(__FILE__));
require("function.php");
set_error_handler("customError",E_ALL);
trigger_error('a eroor by user');