<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_014{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('auth__users_terminos', TRUE);
        $this->CI->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_users'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'identificador de clientes',
                'null'       => FALSE
            ],
            'aceptado'  => [
                'type'       => 'datetime',
                'comment'    => 'Fecha que se aceptan terminos y condiciones'
            ],
            'username'  => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'email'     => [
                'type'       => 'VARCHAR',
                'constraint' => '254',
            ],            
            'estado'   => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'comment'    => '1: aceptado 0: terminos cambiaron debe volver a aceptar',
                'null'       => FALSE,
                'default'    => 1
            ],
            'ayudame'   => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'comment'    => '1: ver ayuda 0: no mostrar ayuda',
                'null'       => FALSE,
                'default'    => 1
            ],
            'user_platform'   => [
                'type'       => 'VARCHAR',
                'constraint' => '256',
                'comment'    => 'Sistema Operativo del cliente',
                'null'       => FALSE,
            ],
            'user_ip'   => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'comment'    => 'IP del cliente',
                'null'       => FALSE,
            ],
            'user_agent'   => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'comment'    => 'Agente del cliente',
                'null'       => FALSE,
            ],
            'observacion'   => [
                'type'       => 'VARCHAR',
                'constraint' => '1024',
                'comment'    => 'observaciones',
                'null'       => TRUE
            ],
            'created_user'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id usuario creador'
            ],
            'created_clie'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id cliente creador'
            ],
            'update_user'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id usuario actualizo'
            ],
            'update_clie'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id cliente actualizo'
            ],
        ]);
        $this->CI->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->add_key('fk_users');
        $this->CI->dbforge->create_table('auth__users_terminos');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('auth__users_terminos', TRUE);
    }    
}