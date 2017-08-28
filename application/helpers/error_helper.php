<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/24
 * Time: 17:19
 */

defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH.'libraries/Error_message.php');

if (!function_exists('i2_code')) {
    //success to use Err::$errCodes directly
    function i2_code($code)
    {
        return Error_message::getCode($code);
    }
}

if (!function_exists('i2_msg')) {
    function i2_msg($code, $translate = false)
    {
        if($translate) {
            return "[{$code}] " . get_instance()->lang->line(Error_message::getMsg($code));
        }else{
            return Error_message::getMsg($code, true);
        }
    }
}