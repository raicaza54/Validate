<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_012{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('sist__listas', TRUE);
        $this->CI->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'lista'   => [
                'type'       => 'VARCHAR',
                'constraint' => '354',
                'comment'    => 'nombre de la lista',
                'null'       => FALSE
            ],            
            'nombre'   => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
                'comment'    => 'nombre del implicado',
                'null'       => TRUE
            ],
            'aka'   => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
                'comment'    => 'alias del implicado',
                'null'       => TRUE
            ],
            'identificacion'     => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
                'comment'    => 'nit, cc o identificacion del implicado',
                'null'       => TRUE
            ],
            'otros'     => [
                'type'    => 'TEXT',
                'comment' => 'Cadena serializada campos adicionales',
                'null'    => TRUE
            ],
            'deleted_at'   => [
                'type'       => 'INT',
                'constraint' => '1',
                'comment'    => 'borrado logico',
                'default'    => 0
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
        $this->CI->dbforge->add_key('nombre');
        $this->CI->dbforge->add_key('aka');
        $this->CI->dbforge->add_key('identificacion');
        $this->CI->dbforge->create_table('sist__listas');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('sist__listas', TRUE);
    }    
}