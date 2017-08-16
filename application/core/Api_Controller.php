<?php

require_once(APPPATH . 'third_party/restserver/libraries/REST_Controller.php');

/**
 * Base Controller for API module
 */
class API_Controller extends REST_Controller
{

    // API Key object to represent identity consuming the API endpoint
    protected $mApiKey = NULL;
    protected $_config;

    // Constructor
    public function __construct()
    {
        parent::__construct();

        // send PHP headers when necessary (e.g. enable CORS)
        $config = $this->config->item('rest');
        $this->load->model('user_model', 'users');
        $this->load->library('i2_auth');

        $headers = empty($config['headers']) ? array() : $config['headers'];
        foreach ($headers as $header) {
            header($header);
        }
        is_array($config) OR $config = array();
        $this->_config = array_merge(array(
//            'token_type'             => 'bearer',
            'access_lifetime'        => 7200,
            'refresh_token_lifetime' => 1209600
        ), $config);

        $this->verify_token();
    }

    // Verify access token (e.g. API Key, JSON Web Token)
    protected function verify_token()
    {
        // lookup API Key record by value from HTTP header
        $key = $this->input->get_request_header(config_item('rest_key_name'));
        if(is_null($key)){
            $key = $this->input->get_post('Token');
        }
        $this->mApiKey = $this->api_keys->get_by('access_token', $key);
        if (!empty($this->mApiKey)) {
            $this->mUser = $this->users->get_by('username', $this->mApiKey->user_id);
            // only when the API Key represents a user
            if (!empty($this->mUser)) {
                $this->mUserRoles = $this->i2_auth->get_user_roles($this->mUser->id);
                $this->mUserMainRole = $this->mUserRoles[0]->name;
            } else {
                // anonymous access via API Key
                $this->mUserMainRole = 'anonymous';
            }
        }
    }

    // Verify authentication (by user group, or by "anonymous")
    // $group parameter can be name, ID, name array, ID array, or mixed array
    protected function verify_auth($groups = 'members')
    {
        $groups = is_string($groups) ? array($groups) : $groups;

        if (empty($this->mUser)) {
            // anonymous access
            if (!in_array($this->mUserMainGroup, $groups))
                $this->error_unauthorized();
        } else {
            // user groups not match with requirement
            if (!$this->ion_auth->in_group($groups, $this->mUser->id))
                $this->error_unauthorized();
        }
    }

    // Shortcut functions following REST_Controller convention
    protected function success($msg = NULL)
    {
        $data = array('ret' => TRUE);
        if (!empty($msg)) $data['message'] = $msg;
        $this->response($data, REST_Controller::HTTP_OK);
    }

    protected function created($msg = NULL)
    {
        $data = array('ret' => TRUE);
        if (!empty($msg)) $data['message'] = $msg;
        $this->response($data, REST_Controller::HTTP_CREATED);
    }

    protected function accepted($msg = NULL)
    {
        $data = array('ret' => TRUE);
        if (!empty($msg)) $data['message'] = $msg;
        $this->response($data, REST_Controller::HTTP_ACCEPTED);
    }

    protected function error($msg = 'An error occurs', $code = REST_Controller::HTTP_OK, $additional_data = array())
    {
        $data = array('ret' => FALSE, 'error' => $msg);

        // (optional) append additional data
        if (!empty($additional_data))
            $data['data'] = $additional_data;

        $this->response($data, $code);
    }

    protected function error_bad_request()
    {
        $data = array('ret' => FALSE);
        $this->response($data, REST_Controller::HTTP_BAD_REQUEST);
    }

    protected function error_unauthorized()
    {
        $data = array('ret' => FALSE);
        $this->response($data, REST_Controller::HTTP_UNAUTHORIZED);
    }

    protected function error_forbidden()
    {
        $data = array('ret' => FALSE);
        $this->response($data, REST_Controller::HTTP_FORBIDDEN);
    }

    protected function error_not_found()
    {
        $data = array('ret' => REST_Controller::HTTP_NOT_FOUND, 'msg' => '');
        $this->response($data, REST_Controller::HTTP_NOT_FOUND);
    }

    protected function error_method_not_allowed()
    {
        $data = array('ret' => FALSE);
        $this->response($data, REST_Controller::HTTP_METHOD_NOT_ALLOWED);
    }

    protected function error_not_implemented($additional_data = array())
    {
        // show "not implemented" info only during development mode
        if (ENVIRONMENT == 'development') {
            $trace = debug_backtrace();
            $caller = $trace[1];

            $data = array(
                'url' => current_url(),
                'module' => $this->router->fetch_module(),
                'controller' => $this->router->fetch_class(),
                'action' => $this->router->fetch_method(),
            );

            if (!empty($additional_data))
                $data = array_merge($data, $additional_data);

            $this->error('Not implemented', REST_Controller::HTTP_NOT_IMPLEMENTED, $data);
        } else {
            $this->error_not_found();
        }
    }

    // Functions from codeigniter-restserver
    protected function _generate_key()
    {
        do {
            // Generate a random salt
            /*$salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);
            var_dump($salt);
            // If an error occurred, then fall back to the previous method
            if ($salt === FALSE) {
                $salt = hash('sha256', time() . mt_rand());
            }*/
            $rand = $this->security->get_random_bytes(64);
            $salt = ($rand === FALSE)
                ? md5(uniqid(mt_rand(), TRUE)).hash('sha256', time() . mt_rand())
                : bin2hex($rand);
            $new_key = substr($salt, 0, config_item('rest_key_length'));
        } while ($this->_key_exists($new_key));
        return $new_key;
    }

    protected function _get_key($key)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->get(config_item('rest_keys_table'))
            ->row();
    }

    protected function _key_exists($key)
    {
        return $this->db
                ->where(config_item('rest_key_column'), $key)
                ->count_all_results(config_item('rest_keys_table')) > 0;
    }

    protected function _insert_key($key, $data)
    {
        $data[config_item('rest_key_column')] = $key;
        $data['create_time'] = function_exists('now') ? now() : time();
        $data['expires'] = $data['create_time'] + $this->_config['access_lifetime'];
        return $this->db
            ->set($data)
            ->insert(config_item('rest_keys_table'));
    }

    protected function _update_key($key, $data)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->update(config_item('rest_keys_table'), $data);
    }

    protected function _delete_key($key)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->delete(config_item('rest_keys_table'));
    }
}