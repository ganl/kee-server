<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/24
 * Time: 15:39
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Error_message
{
    /**
     * return Code
     * @var [type]
     */
    public static $errCodes = [
        // Status code £¬ ret
        '200' => '200',
        '400' => '400',
        '401' => '401',
        '500' => '500',

        // err code
        '0' => '0',
        'success' => '0',
        'undefined_message' => '-1',

        //old
        'auth_fail' => '1001',

        'user_already_exist' => '801',
        'user_not_exist' => '802',

        'wkuuid_not_exist' => '1002',
        'ssl_invalid' => '1003',

        'version_invalid' => '1004',
        'systype_invalid' => '1005',

        'signature_invalid' => '1006',
        'token_invalid' => '1007',
        'token_expire' => '1008',

        'phone_number_invalid' => '1100',
        'phone_timewait_invalid' => '1101',
        'phone_code_send_invalid' => '1102',
        'phone_code_server_error' => '1103',
        'username_pwd_invalid' => '1104',

        'phone_code_invalid' => '1105',
        'name_duplicate' => '1106',

        'node_not_on_cc' => '1107',
        'node_add_failed' => '1108',
        'node_not_exist' => '1109',
        'node_not_owner' => '1128',
        'node_add_modify' => '1134',
        'node_add_modify_failed' => '1135',
        'node_modify_failed' => '1110',
        'node_cant_delete' => '1111',
        'node_del_failed' => '1112',
        'node_reboot_failed' => '1136',
        'node_online_exception' => '1140',
        'backup_online_exception' => '1141',

        'rule_bk_null' => '1113',
        'rpc_call_fail' => '1114',
        'src_path_check_fail' => '1115',
        'target_path_check_fail' => '1130',
        'rule_add_fail' => '1116',
        'phone_invalid' => '1117',

        'unknown_operate' => '1118',
        'operate_failed' => '1119',

        'rule_del_failed' => '1120',
        'rule_not_exist' => '1121',
        'rule_data_not_exist' => '1131',

        'dir_param_empty' => '1122',
        'dir_param_invalid' => '1123',
        'dir_get_failed' => '1124',


        'get_node_ip_failed' => '1125',
        'server_path_check_fail' => '1126',
        'recovery_add_fail' => '1127',
        'recovery_data_del_fail' => '1129',

        'vm_power_on_failed' => '1132',
        'vm_set_ip_failed' => '1133',
        'vm_not_exist' => '1142',
        'vm_exist' => '1143',
        'vm_exist_power_on' => '1144',
        'vm_opened' => '1145',

        'disk_space_not_enough' => '1137',
        'get_disk_size_failed' => '1138',
        'bak_rpc_error' => '1139',

        'database_op' => '9998',

        'server_error' => '9999',

        'code_passwd_invalid' => '2001',
        'code_param_invalid' => '2002',


        'qrcode_account_locked' => '2003',
        'qrcode_account_not_exist' => '2004',
        'qrcode_account_loss' => '2005',


        /*
         * new
         */

        //common
        'uuid_invalid' => '10001001',
        'params_invalid' => '10001002',

        'login_successful' => '10001003',


        //node
        'node.cc_uuid_invalid' => '10011113',
        'node.may_not_online' => '10011114',
        'node.auth_passwd_invalid' => '10011115',
        'node.license_limit' => '10011116',


        //dir
        'dir.check_not_exist' => '10021001',
        'dir.mk_failed' => '10021002',


        //rep
        'rule.type_invalid' => '10031001',
        'rule.cdp_path_empty' => '10031002',
        'rule.modify_failed' => '10031003',
        'rule.not_stop' => '10031004',


        //rc
        'rc.in_running_rep' => '10041001',
        'rc.not_exist' => '10041002',

        //logs
        'logs.start_lte_end' => '10051001',


    ];


    public static function getCode($code = '400')
    {
        if (!isset(self::$errCodes[$code])) {
            $code = '400';
        }
        return self::$errCodes[$code];
    }

    /**
     * @return string, code message
     * @var string
     */
    public static function getMsg($code = '0', $_ = false)
    {
        if(!in_array($code, self::$errCodes)){
            $code = '-1';
        }
        $msg = array_flip(self::$errCodes);
        return ($_ ? "[{$code}] " : '') . $msg[$code];
    }

}

class Err extends Error_message{}