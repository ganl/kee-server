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
    }

    public function __get($name)
    {
        return get_instance()->$name;
    }

    protected function _get_cc_uuid($params){
        return isset($params['cc_uuid']) ? $params['cc_uuid'] : $this->global_functions->ctrl_uuid();
    }

    protected function process_params($rpc_args) {
        $param_Str = "";
        foreach ($rpc_args as $key=>$value) {
            $param_Str .= $param_Str.$key. $this->SEPARATOR_KEY .$value.$this->SEPARATOR_PARAM;
        }
        return array($param_Str);
    }

    private function send($ip, $rpc_method, $params, $port = 26821, $path = '/RPC2')
    {
        $server_url = $ip.$path;
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

    public function node_hello($host){
        return $this->send($host, 'rpc.wk_hello', array());
    }

    public function node_os_auth($host, $params){
        $rpc_args = [];
        if(isset($params['os_user'])){
            $rpc_args['os_user'] = base64_encode($params['os_user']);
        }else{
            return 'args_error';
        }
        if(isset($params['os_passwd'])){
            $rpc_args['os_passwd'] = base64_encode($params['os_passwd']);
        }else{
            return 'args_error';
        }
        if(isset($params['auth_type'])){
            $rpc_args['auth_type'] = $params['auth_type'];
        }
        $rpc_args['cc_uuid'] = $this->_get_cc_uuid($params);

        return $this->send($host, 'rpc.node_os_auth', $rpc_args);
    }

}