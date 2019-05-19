<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_009{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $fields = [
            'pc_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'default'    => '',
                'comment'    => 'Persona contacto',
                'null'       => TRUE
            ],
            'pc_telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'default'    => '',
                'comment'    => 'Persona contacto telefonos',
                'null'       => TRUE
            ],
            'pc_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'default'    => '',
                'comment'    => 'Persona contacto emails',
                'null'       => TRUE
            ],
            'ec_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'default'    => '',
                'comment'    => 'Ejecutivo comercial',
                'null'       => TRUE
            ],
            'ec_telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'default'    => '',
                'comment'    => 'Ejecutivo comercial telefonos',
                'null'       => TRUE
            ],
            'ec_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'default'    => '',
                'comment'    => 'Ejecutivo comercial emails',
                'null'       => TRUE
            ],
        ];
        $this->CI->dbforge->add_column('sist__contratos', $fields);
    }

    public function down() {
        $this->CI->dbforge->drop_column('sist__contratos','pc_nombre');
        $this->CI->dbforge->drop_column('sist__contratos','pc_telefono');
        $this->CI->dbforge->drop_column('sist__contratos','pc_email');
        $this->CI->dbforge->drop_column('sist__contratos','ec_nombre');
        $this->CI->dbforge->drop_column('sist__contratos','ec_telefono');
        $this->CI->dbforge->drop_column('sist__contratos','ec_email');
    }

}