<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/9/28 0028
 * Time: 17:48
 */

require APPPATH . 'libraries/rpc/Rpc_client.php';

class Base_rpc extends Rpc_client
{
    protected function _get_cc_uuid($params)
    {
        return isset($params['cc_uuid']) ? $params['cc_uuid'] : $this->global_functions->ctrl_uuid();
    }

    public function node_hello($host)
    {
        return $this->send($host, 'rpc.wk_hello', array());
    }

    public function node_os_auth($host, $params)
    {
        $rpc_args = [];
        if (isset($params['os_user'])) {
            $rpc_args['os_user'] = base64_encode($params['os_user']);
        } else {
            return 'args_error';
        }
        if (isset($params['os_passwd'])) {
            $rpc_args['os_passwd'] = base64_encode($params['os_passwd']);
        } else {
            return 'args_error';
        }
        if (isset($params['auth_type'])) {
            $rpc_args['auth_type'] = $params['auth_type'];
        }
        $rpc_args['cc_uuid'] = $this->_get_cc_uuid($params);

        return $this->send($host, 'rpc.node_os_auth', $rpc_args);
    }

}