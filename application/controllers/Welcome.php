<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $this->load->helper('url');
		$this->load->view('welcome_message');
	}

/*    public function make_base() {
        $this->load->library('VpxMigration');

        // All Tables:
        $this->vpxmigration->generate();
    }*/

    public function test_upgrade()
    {
        $this->load->library('migrate');
        $this->migrate->upgrade();
    }

    public function test_error()
    {
        echo '<pre>';
        echo i2_code('dir.check_not_exist') . "\n";
        echo Err::$errCodes['dir.check_not_exist'] . "\n";
        echo i2_msg('400') . "\n";
        $this->lang->load('auth');
        echo i2_msg(Err::$errCodes['login_successful']) . "\n";
        echo i2_msg(Err::$errCodes['login_successful'], true) . "\n";
    }
}
