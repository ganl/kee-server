<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/15
 * Time: 09:46
 */

class Auth_model extends MY_Model
{
    public $auth_tables = array();

    public $messages;
    public $errors;

    public function __construct()
    {
        parent::__construct();
        $this->config->load('auth', TRUE);
        $this->lang->load('auth');
        $this->auth_tables = $this->config->item('tables', 'auth');

        $this->identity_column = $this->config->item('identity', 'auth');

        $this->messages = [];
        $this->errors = [];

    }

    public function login($identity, $password) {
        if(empty($identity) || empty($password)){
            $this->set_error('login_unsuccessful');
            return FALSE;
        }
        $this->_table = $this->auth_tables['users'];
        $user = $this->get_by($this->identity_column, $identity);
        if(!empty($user)){

        }
        var_dump($user);
        return true;
    }

    public function set_error($error){
        $this->errors[] = $error;
        return $error;
    }

    public function clear_errors() {
        $this->errors = [];
        return TRUE;
    }

    public function errors() {
        $output = '';
        foreach ($this->errors as $error) {
            $output .= $this->lang->line($error, FALSE) ? $this->lang->line($error) : $error ;
        }
        return $output;
    }

}