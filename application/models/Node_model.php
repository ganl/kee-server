<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/16
 * Time: 14:21
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Node_model extends MY_Model
{
    protected $_table = 'nodes';

    public function __construct()
    {
        parent::__construct();
        $this->set_tenant_prefix();
    }
}