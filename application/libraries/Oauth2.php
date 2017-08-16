<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/8
 * Time: 23:53
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Oauth2
{

    protected $CI;

    private $_dsn;
    private $_username;
    private $_password;

    public $oauth_request;
    public $oauth_response;

    public $oauth_server;

    public function __construct()
    {
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        $this->CI->config->load('database');


        $this->_dsn = $this->CI->config->item('db_config')['dsn'];
        $this->_username = $this->CI->config->item('db_config')['username'];
        $this->_password = $this->CI->config->item('db_config')['password'];

        $this->init();

    }

    protected function init(){
        // Autoloading (composer is preferred, but for this example let's just do this)
        require_once($this->get_application_folder().'/third_party/oauth2/src/OAuth2/Autoloader.php');
        OAuth2\Autoloader::register();


        $this->oauth_request = OAuth2\Request::createFromGlobals();
        $this->oauth_response = new OAuth2\Response();

        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $this->_dsn, 'username' => $this->_username, 'password' => $this->_password),
            array('user_table' => 'users'));

        // create array of supported grant types
        $grantTypes = array(
            'authorization_code' => new OAuth2\GrantType\AuthorizationCode($storage),
            'user_credentials'   => new OAuth2\GrantType\UserCredentials($storage),
            'client_credentials'   => new OAuth2\GrantType\ClientCredentials($storage),
            'refresh_token'      => new OAuth2\GrantType\RefreshToken($storage, array(
                'always_issue_new_refresh_token' => true,
            )),
        );

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $this->oauth_server = new OAuth2\Server($storage, array(
            'enforce_state' => true,
            'allow_implicit' => true,
        ), $grantTypes);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        //$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        //$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

    }

    private function get_application_folder()
    {
        return dirname(__DIR__);
    }



}