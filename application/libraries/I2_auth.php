<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/15
 * Time: 09:42
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class I2_auth
{
    protected $CI;

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        $this->CI->config->load('auth', TRUE);
        $this->CI->load->model('auth_model');
    }

    // __call
    public function __call($name, $arguments)
    {
        if(!method_exists($this->CI->auth_model, $name)){
            throw new Exception('Undefined method i2_auth::' . $name . '() called');
        }

        return call_user_func_array([$this->CI->auth_model, $name], $arguments);
    }



}