<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/8/23
 * Time: 16:16
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Migrate
{
    protected $forge_db;
    protected $my_util;
    protected $my_forge;
    private $_migration_table = 'migrations';

    private $_prod_db;
    private $_prod_user;
    private $_prod_pwd;

    public function __construct()
    {
        $this->config->load('database');

        $this->_prod_db = config_item('db_config_prod')['database'];
        $this->_prod_user = config_item('db_config_prod')['username'];
        $this->_prod_pwd = config_item('db_config_prod')['password'];

        $this->forge_db = $this->load->database(config_item('db_forge'), true);
        $this->my_util = $this->load->dbutil($this->forge_db, true);

        //If not exist database `i2soft`
        if (!$this->my_util->database_exists($this->_prod_db)) {
            $this->my_forge = $this->load->dbforge($this->forge_db, true);
            if (!$this->my_forge->create_database($this->_prod_db)) { //create database `i2soft`
                log_message('error', 'Database not exist and create failed !');
                return false;
            } else {
                $this->forge_db->query("CREATE USER " . $this->_prod_user . " WITH PASSWORD '" . $this->_prod_pwd . "' CREATEDB");
                $this->forge_db->query("ALTER USER " . $this->_prod_user . " WITH PASSWORD '" . $this->_prod_pwd . "' CREATEDB");
                $this->forge_db->query("ALTER DATABASE " . $this->_prod_db . " OWNER TO " . $this->_prod_user);
                log_message('info', 'Create database success !');
            }
        }

        $this->load->database();
        $this->load->dbutil();
        $this->load->dbforge();

        // If the migrations table is missing, make it
        if (!$this->db->table_exists($this->_migration_table)) {
            $this->dbforge->add_field(array(
                'version' => array('type' => 'BIGINT', 'constraint' => 20),
            ));

            $this->dbforge->create_table($this->_migration_table);

            $this->db->insert($this->_migration_table, array('version' => 0));
        }

    }

    public function __get($name)
    {
        return get_instance()->$name;
    }

    public function upgrade()
    {
        if (!$this->migration->current() === true) {
            log_message('error', $this->migration->error_string());
        } else {
            echo 'upgrade success!';
        }
    }
}