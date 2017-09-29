<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/16
 * Time: 09:55
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rpc_client
{
    public function __construct()
    {
        $this->load->library('xmlrpc');
        $this->load->library('global_functions');
        $this->load->config('rpc_config', TRUE);

        $this->SEPARATOR_KEY = $this->config->item('SEPARATOR_KEY', 'rpc_config');
        $this->SEPARATOR_PARAM = $this->config->item('SEPARATOR_PARAM', 'rpc_config');
        $this->protocol = $this->config->item('protocol', 'rpc_config');
    }

    public function __get($name)
    {
        return get_instance()->$name;
    }

    protected function process_params($rpc_args) {
        $param_Str = "";
        foreach ($rpc_args as $key=>$value) {
            $param_Str .= $param_Str.$key. $this->SEPARATOR_KEY .$value.$this->SEPARATOR_PARAM;
        }
        return array($param_Str);
    }

    protected function send($ip, $rpc_method, $params, $port = 26821, $path = '/RPC2')
    {
        $server_url = $this->protocol.'://'.$ip.$path;
        $this->xmlrpc->server($server_url, $port);
        $this->xmlrpc->method($rpc_method);

        $request = $this->process_params($params);//may need process

        $this->xmlrpc->request($request);

        if(! $this->xmlrpc->send_request()){
//            return $this->xmlrpc->display_error();
            return -1;
        }else{
            return $this->xmlrpc->display_response();
        }
    }

}