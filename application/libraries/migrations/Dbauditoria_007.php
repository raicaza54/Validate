<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_007{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('clie__clientes_users', TRUE);
        $fields = [
            'fk_cliente' => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'Cliente al cual pertenece el auditor',
                'null'       => FALSE
            ],
        ];
        $this->CI->dbforge->add_column('auth__users', $fields);
    }

    public function down() {
        $this->CI->dbforge->drop_column('auth__users','fk_cliente');
    }

}