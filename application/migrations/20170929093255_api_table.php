<?php
/**
 * Created by PhpStorm.
 * User: ganl
 * Date: 2017/9/29 0029
 * Time: 9:33
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_api_table extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('api_token')) {
            $this->dbforge->add_field(array(
                'access_token' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '40',
                ),
                'user_id' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                ),
                'client_id' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '80',
                    'null' => TRUE,
                ),
                'level' => array(
                    'type' => 'INTEGER',
                    'default' => 0,
                ),
                'ignore_limits' => array(
                    'type' => 'SMALLINT',
                    'default' => 0,
                ),
                'is_private_key' => array(
                    'type' => 'SMALLINT',
                    'default' => 0,
                ),
                'ip_addresses' => array(
                    'type' => 'text',
                    'null' => TRUE,
                ),
                'scope' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '2000',
                    'null' => TRUE,
                ),
                'create_time' => array(
                    'type' => 'BIGINT',
                ),
                'expires' => array(
                    'type' => 'BIGINT',
                    'null' => TRUE,
                ),
                'last_login_ip' => array(
                    'type' => 'character varying',
                    'constraint' => '45',
                    'null' => TRUE,
                ),
                'last_access' => array(
                    'type' => 'timestamp',
                    'null' => TRUE
                ),
            ));
            $this->dbforge->add_key('access_token', TRUE);
            $this->dbforge->create_table('api_token');
        }

        if (!$this->db->table_exists('api_logs')) {
            $this->dbforge->add_field(array(
                'id' => array(
                    'type' => 'serial',
                ),
                'uri' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '1024',
                ),
                'method' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '6'
                ),
                'params' => array(
                    'type' => 'text',
                    'null' => TRUE,
                ),
                'api_key' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '40'
                ),
                'ip_address' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '45'
                ),
                'time' => array(
                    'type' => 'BIGINT',
                ),
                'rtime' => array(
                    'type' => 'REAL',
//                    'default' => NULL,
                    'null' => TRUE
                ),
                'authorized' => array(
                    'type' => 'boolean'
                ),
                'response_code' => array(
                    'type' => 'smallint',
                    'default' => 0,
                )
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('api_logs');
        }

    }

    public function down()
    {
        if ($this->db->table_exists('api_token')) {
            $this->dbforge->drop_table('api_token');
        }
        if ($this->db->table_exists('api_logs')) {
            $this->dbforge->drop_table('api_logs');
        }
    }
}