<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/15
 * Time: 09:42
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class I2_auth
{

    public function __construct()
    {
        $this->config->load('auth', TRUE);
        $this->load->model('auth_model');
    }

    // __call
    public function __call($name, $arguments)
    {
        if (!method_exists($this->auth_model, $name)) {
            throw new Exception('Undefined method i2_auth::' . $name . '() called');
        }

        return call_user_func_array([$this->auth_model, $name], $arguments);
    }

    public function __get($name)
    {
        return get_instance()->$name;
    }


}