<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_018{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $this->CI->dbforge->drop_table('sist__comprar', TRUE);
        $this->CI->dbforge->add_field([
            'id'           => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_clientes'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
                'null'       => FALSE
            ],
            'fk_users'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
                'null'       => FALSE
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'nombre completo',
                'null'       => TRUE
            ],
            'correo'     => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'un correo electronico',
                'null'       => TRUE
            ],
            'telefono'     => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'telefono de contacto',
                'null'       => TRUE
            ],
            'envios'     => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'contador de envios',
                'default'    => 1
            ],
            'user_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'comment'    => 'direccion ip de la peticion',
                'null'       => TRUE
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'comment'    => '0:recibida, 1:informada, 2:en proceso, 3:compra, 4:declinada',
                'default'    => 0
            ],
            'observacion'  => [
                'type'       => 'VARCHAR',
                'constraint' => '1024',
                'comment'    => 'observaciones',
                'null'       => TRUE
            ],
            'created_user' => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id usuario creador'
            ],
            'created_clie' => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'id cliente creador'
            ],
            'update_user'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id usuario actualizo'
            ],
            'update_clie'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'ultimo id cliente actualizo'
            ],
        ]);
        $this->CI->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->CI->dbforge->add_key('id', TRUE);
        $this->CI->dbforge->create_table('sist__comprar');
    }
    
    public function down() {
        $this->CI->dbforge->drop_table('sist__comprar', TRUE);
   }    
}