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

    protected $messages;
    protected $errors;

    private $__inactive = 0;
    private $__active = 1;
    private $__suspend = 2;

    public function __construct()
    {
        parent::__construct();
        $this->config->load('auth', TRUE);
        $this->lang->load('auth');
        $this->auth_tables = $this->config->item('tables', 'auth');

        $this->identity_column = $this->config->item('identity', 'auth');
        $this->store_salt = $this->config->item('store_salt', 'auth');
        $this->salt_length = $this->config->item('salt_length', 'auth');

        $this->hash_method = $this->config->item('hash_method', 'auth');

        $this->join = $this->config->item('join', 'auth');

        $this->messages = [];
        $this->errors = [];
    }

    // Check login valid
    public function login($identity, $password)
    {
        // todo: captcha

        if (empty($identity) || empty($password)) {
            $this->set_error('login_unsuccessful');
            return FALSE;
        }

        if ($this->is_max_login_attempts_exceeded($identity)) {
            $this->set_error('login_timeout');
            return FALSE;
        }

        $this->_table = $this->auth_tables['users'];
        $user = $this->get_by($this->identity_column, $identity);

        if (!empty($user)) {
            $login_result = $this->verify_password($password, $user);
            if ($login_result) {

                if ($user->active === $this->__inactive) {
                    $this->set_error('login_unsuccessful_not_active');
                    return FALSE;
                }

                $ret = $this->update_last_login($user);
var_dump($ret);
            }
            $this->increase_login_attempts($identity, $login_result ? 1 : 0);
            return $login_result;
        } else {
            $this->set_error('login_unknown_user');
        }
        return FALSE;

    }

    /**
     * Hashes the password to be stored in the database.
     */
    function hash_password($password, $salt = false)
    {
        if (empty($password)) {
            return FALSE;
        }

        if ($this->hash_method == 'bcrypt' || $this->config->item('use_password_hash', 'auth')) {
            return password_hash($password, $this->config->item('password_hash_algo', 'auth'), $this->config->item('password_hash_options', 'auth'));
        } else {
            if (!($this->store_salt && $salt)) {
                $salt = $this->salt();
                return $salt . substr(hash($this->hash_method, $password . $salt), 0, -$this->salt_length);
            } else {
                return hash($this->hash_method, $password . $salt);
            }
        }
    }

    /**
     * Verify password
     *
     */
    function verify_password($password, $user)
    {
        if (empty($user) || empty($password)) {
            return FALSE;
        }
        if ($this->hash_method == 'bcrypt' || $this->config->item('use_password_hash', 'auth')) {
            return password_verify($password, $user->password);
        } else {
            if ($this->store_salt) {
                $hash_pwd = hash($this->hash_method, $password . $user->salt);
            } else {
                $salt = substr($user->password, 0, $this->salt_length);
                $hash_pwd = $salt . substr(hash($this->hash_method, $password . $salt), 0, -$this->salt_length);
            }
            return ($hash_pwd == $user->password ? TRUE : FALSE);
        }
    }

    function increase_login_attempts($identity, $result = 0)
    {
        if ($this->config->item('track_login_attempts', 'auth')) {
            $data = array('ip_address' => '', 'login' => $identity, 'time' => time(), 'result' => $result);
            if ($this->config->item('track_login_ip_address', 'auth')) {
                $data['ip_address'] = $this->input->ip_address();
            }
            $this->_table = $this->auth_tables['login_attempts'];
            return $this->insert($data);
        }
        return false;
    }

    /**
     * check, is max login attempts exceeded
     * @param $identity
     * @param $ip_address
     * @return boolean
     */
    function is_max_login_attempts_exceeded($identity, $ip_address = NULL)
    {
        if ($this->config->item('track_login_attempts', 'auth')) {
            $max_attempts = $this->config->item('maximum_login_attempts', 'auth');
            if ($max_attempts > 0) {
                $attempts = $this->get_attempts_count($identity, $ip_address);
                return $attempts > $max_attempts;
            }
        }
        return false;
    }

    function get_attempts_count($identity, $ip_address = NULL)
    {
        if ($this->config->item('track_login_attempts', 'auth')) {
            $this->_database->select('1', FALSE);
            $this->_database->where('login', $identity);
            if ($this->config->item('track_login_ip_address', 'auth')) {
                if (!isset($ip_address)) {
                    $ip_address = $this->input->ip_address();
                }
                $this->_database->where('ip_address', $ip_address);
            }
            $this->_database->where('time >', time() - $this->config->item('lockout_time', 'auth'), FALSE);
            $qres = $this->_database->get($this->auth_tables['login_attempts']);
            return $qres->num_rows();
        }
        return 0;
    }

    function is_time_lock_out($identity, $ip_address = NULL)
    {
        return $this->is_max_login_attempts_exceeded($identity, $ip_address);
    }

    function update_last_login($user)
    {
        $data['last_login'] = time();
        $data['ip_address'] = $this->input->ip_address();
        $this->_table = $this->auth_tables['users'];
        return $this->update($user->id, $data);
    }

    /**
     * Generates a random salt value.
     *
     * Salt generation code taken from https://github.com/ircmaxell/password_compat/blob/master/lib/password.php
     *
     * @return boolean|string
     * @author Anthony Ferrera
     **/
    public function salt()
    {

        $raw_salt_len = 16;

        $buffer = '';
        $buffer_valid = false;

        if (function_exists('random_bytes')) {
            $buffer = random_bytes($raw_salt_len);
            if ($buffer) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid && function_exists('mcrypt_create_iv') && !defined('PHALANGER')) {
            $buffer = mcrypt_create_iv($raw_salt_len, MCRYPT_DEV_URANDOM);
            if ($buffer) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid && function_exists('openssl_random_pseudo_bytes')) {
            $buffer = openssl_random_pseudo_bytes($raw_salt_len);
            if ($buffer) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid && @is_readable('/dev/urandom')) {
            $f = fopen('/dev/urandom', 'r');
            $read = strlen($buffer);
            while ($read < $raw_salt_len) {
                $buffer .= fread($f, $raw_salt_len - $read);
                $read = strlen($buffer);
            }
            fclose($f);
            if ($read >= $raw_salt_len) {
                $buffer_valid = true;
            }
        }

        if (!$buffer_valid || strlen($buffer) < $raw_salt_len) {
            $bl = strlen($buffer);
            for ($i = 0; $i < $raw_salt_len; $i++) {
                if ($i < $bl) {
                    $buffer[$i] = $buffer[$i] ^ chr(mt_rand(0, 255));
                } else {
                    $buffer .= chr(mt_rand(0, 255));
                }
            }
        }

        $salt = $buffer;

        // encode string with the Base64 variant used by crypt
        $base64_digits = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
        $bcrypt64_digits = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $base64_string = base64_encode($salt);
        $salt = strtr(rtrim($base64_string, '='), $base64_digits, $bcrypt64_digits);

        $salt = substr($salt, 0, $this->salt_length);


        return $salt;

    }

    public function user($id = null, $identity = null)
    {
        $this->_table = $this->auth_tables['users'];
        $user = array();
        if ($id != null) {
            $user = $this->get_by('id', $id);
        } elseif ($identity != null) {
            $user = $this->get_by($this->identity_column, $identity);
        }
        return $user;
    }

    public function get_user_tenant($id)
    {
        $query = $this->_database->join($this->auth_tables['tenant'], $this->auth_tables['users'] . '.' . $this->join['tenant'] . '=' . $this->auth_tables['tenant'] . '.id')
            ->where($this->auth_tables['users'] . '.id', $id)
            ->get($this->auth_tables['users']);
        return $query->row();
    }

    public function get_user_roles($id)
    {
        $query = $this->_database->join($this->auth_tables['roles'], $this->auth_tables['role_user'] . '.' . $this->join['roles'] . '=' . $this->auth_tables['roles'] . '.id')
            ->where($this->auth_tables['role_user'] . '.' . $this->join['users'], $id)
            ->get($this->auth_tables['role_user']);
        return $query->result();
    }

    public function set_error($error)
    {
        $this->errors[] = $error;
        return $error;
    }

    public function clear_errors()
    {
        $this->errors = [];
        return TRUE;
    }

    public function errors()
    {
        $output = '';
        foreach ($this->errors as $error) {
            $output .= $this->lang->line($error, FALSE) ? $this->lang->line($error) : $error;
        }
        return $output;
    }

}