<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_008{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $fields = [
            'estado' => [
                'type'       => 'INT',
                'constraint' => '11',
                'default'    => 1,
                'comment'    => '0:inactivo, 1:activo, 2:cancelado, 3:borrado',
                'null'       => FALSE
            ],
        ];
        $this->CI->dbforge->add_column('sist__contratos', $fields);
    }

    public function down() {
        $this->CI->dbforge->drop_column('sist__contratos','estado');
    }

}