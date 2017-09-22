<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/8
 * Time: 23:38
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Oauth extends CI_Controller
{
    function __construct(){
        parent::__construct();
        $this->load->library("oauth2");
    }

    function token(){
        $server = $this->oauth2->oauth_server;
        $request = $this->oauth2->oauth_request;
        $response = $this->oauth2->oauth_response;
        $server->handleTokenRequest($request, $response);
        $response->send();
    }
}