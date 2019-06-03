<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_010{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('clie__resultados', TRUE);
        $this->CI->dbforge->add_field([
            'id'             => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_empresas'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'order'          => [
                'type'       => 'INT',
                'constraint' => '3',
                'comment'    => 'ordenar',
                'null'       => TRUE
            ],
            'parent_id'      => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'registro padre',
                'null'       => TRUE
            ],
            'label'          => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'nombre del nodo',
                'null'       => TRUE
            ],
            'have_childrens' => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'registro hijo',
                'default'    => 0
            ],
            'deleted_at'   => [
                'type'       => 'INT',
                'constraint' => '1',
                'comment'    => 'borrado logico',
                'default'    => 0
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'comment'    => 'tipo de icono en el arbol',
                'null'       => TRUE
            ],
            'opened' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => 'Si el directorio se muestra desplegado',
                'null'       => TRUE,
                'default'    => 0
            ],
            'disabled' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => 'Si esta habilitado/deshabilitado el item',
                'null'       => TRUE,
                'default'    => 0
            ],
            'selected' => [
                'type'       => 'TINYINT',
                'constraint' => '1',
                'comment'    => 'Si esta seleccionado por defecto',
                'null'       => TRUE,
                'default'    => 0
            ],
            'path'    => [
                'type'       => 'VARCHAR',
                'constraint' => '512',
                'comment'    => 'ruta y nombre del archivo',
                'null'       => TRUE
            ],
            'observacion'    => [
                'type'       => 'VARCHAR',
                'constraint' => '1024',
                'comment'    => 'observaciones',
                'null'       => TRUE
            ],
            'created_user'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id usuario creador'
            ],
            'created_clie'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id cliente creador'
            ],
            'update_user'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id usuario actualizo'
            ],
            'update_clie'    => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id cliente actualizo'
            ],
        ]);
        $this->CI->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->add_key('fk_empresas');
        $this->CI->dbforge->create_table('clie__resultados');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('clie__resultados', TRUE);
    }    
}