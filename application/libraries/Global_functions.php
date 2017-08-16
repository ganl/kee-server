<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/16
 * Time: 15:19
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Global_functions
{
    public function __construct()
    {

    }

    public function __get($name)
    {
        return get_instance()->$name;
    }

    /**
     * get/set/update ctrl's uuid
     * @param string , uuid
     * @return string|boolean
     */
    public function ctrl_uuid($uuid = null)
    {
        return $this->uuid();// test code
    }

    /**
     * uuid
     * @return bool|string
     */
    function uuid()
    {
        if (function_exists('com_create_guid')) {
            $uuid = com_create_guid();
            $uuid = substr($uuid, 1, strlen($uuid) - 2);
            return $uuid;
        } else {
            #mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = md5(uniqid(rand(), true));
            $hyphen = chr(45);// "-"
            $uuid = //chr(123)// "{".
                strtoupper(substr($charid, 0, 8)) . $hyphen
                . strtoupper(substr($charid, 8, 4)) . $hyphen
                . strtoupper(substr($charid, 12, 4)) . $hyphen
                . strtoupper(substr($charid, 16, 4)) . $hyphen
                . strtoupper(substr($charid, 20, 12))//.chr(125)// "}"
            ;
            return $uuid;
        }
    }

}