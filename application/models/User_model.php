<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/9
 * Time: 18:12
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{
    public $belongs_to = array( 'tenant' );
//    public $has_many = array( 'roles' );

    public function __construct()
    {
        parent::__construct();
//        $this->set_schema('i2soft');
    }

    public function get_user_tenant($id) {
        $user = $this->with('tenant')
            ->get($id);
        return $user;
    }
}