<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_dbauditoria_listas extends CI_Migration {

    public function __construct() {
        parent::__construct();
        $this->load->dbforge();
    }

    public function up() {
        $this->dbforge->drop_table('sist__permisos', TRUE);
        $this->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'permiso'       => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'permiso',
                'null'       => TRUE
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
            'created_clier' => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('sist__permisos');

        $this->dbforge->drop_table('sist__permisos_groups', TRUE);
        $this->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_permisos'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'permisos',
                'unsigned'   => TRUE,
            ],
            'fk_groups'     => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'grupos',
                'unsigned'   => TRUE,
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
            'created_clier' => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('fk_permisos');
        $this->dbforge->add_key('fk_groups');
        $this->dbforge->create_table('sist__permisos_groups');

        $this->dbforge->drop_table('sist__permisos_users', TRUE);
        $this->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_permisos'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'permisos',
                'unsigned'   => TRUE,
            ],
            'fk_users'      => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'grupos',
                'unsigned'   => TRUE,
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
            'created_clier' => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('fk_permisos');
        $this->dbforge->add_key('fk_users');
        $this->dbforge->create_table('sist__permisos_users');

        $this->dbforge->drop_table('sist__jerarquia', TRUE);
        $this->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'etiqueta'      => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'etiqueta para mostrar',
                'null'       => FALSE
            ],
            'nivel'         => [
                'type'       => 'INT',
                'constraint' => '11',
                'comment'    => 'orden de la jerarquia siendo 1 el mas alto',
                'null'       => FALSE
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
            'created_clier' => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('sist__jerarquia');

        $this->dbforge->drop_table('sist__contratos', TRUE);
        $this->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_clientes'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'consecutivo'   => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'comment'    => 'numero de contrato',
                'null'       => FALSE
            ],
            'fecha_ini'     => [
                'type'    => 'DATETIME',
                'comment' => 'fecha de inicio de contrato',
                'null'    => TRUE
            ],
            'fecha_fin'     => [
                'type'    => 'DATETIME',
                'comment' => 'fecha de finalizacion de contrato',
                'null'    => TRUE
            ],
            'servicios'     => [
                'type'    => 'TEXT',
                'comment' => 'Servicios contratados',
                'null'    => FALSE
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
            'created_clier' => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('fk_clientes');
        $this->dbforge->create_table('sist__contratos');

        $this->dbforge->drop_table('clie__clientes', TRUE);
        $this->dbforge->add_field([
            'id'             => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'nombre'         => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'nombre del cliente',
                'null'       => FALSE
            ],
            'identificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'NIT/CC/CE del cliente',
                'null'       => TRUE
            ],
            'direccion'      => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'direccion comercial del cliente',
                'null'       => TRUE
            ],
            'telefonos'      => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'comment'    => 'telefonos locales del cliente',
                'null'       => TRUE
            ],
            'correo'         => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'correo electronico del cliente',
                'null'       => TRUE
            ],
            'persona'        => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'nombre de persona contacto',
                'null'       => TRUE
            ],
            'persona_tlfs'   => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'telefonos de persona contacto',
                'null'       => TRUE
            ],
            'persona_direc'  => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'direccion de persona contacto',
                'null'       => TRUE
            ],
            'persona_correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'correo electronico de persona contacto',
                'null'       => TRUE
            ],
            'comercial'      => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'comment'    => 'comercial encargado del cliente',
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
            'created_clier'  => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('clie__clientes');

        $this->dbforge->drop_table('clie__clientes_users', TRUE);
        $this->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_clientes'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'fk_users'      => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
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
            'created_clier' => [
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

        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('fk_clientes');
        $this->dbforge->add_key('fk_users');
        $this->dbforge->create_table('clie__clientes_users');

        $this->dbforge->drop_table('clie__auditores_empresas', TRUE);
        $this->dbforge->add_field([
            'id'            => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'fk_auditores'  => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
            ],
            'fk_empresas'   => [
                'type'       => 'INT',
                'constraint' => '11',
                'unsigned'   => TRUE,
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
            'created_clier' => [
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

        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('fk_auditores');
        $this->dbforge->add_key('fk_empresas');
        $this->dbforge->create_table('clie__auditores_empresas');

        $this->dbforge->drop_table('clie__empresas', TRUE);
        $this->dbforge->add_field([
            'id'             => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => TRUE,
                'auto_increment' => TRUE
            ],
            'nombre'         => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'nombre del cliente',
                'null'       => FALSE
            ],
            'identificacion' => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'NIT/CC/CE del cliente',
                'null'       => TRUE
            ],
            'direccion'      => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'direccion comercial del cliente',
                'null'       => TRUE
            ],
            'telefonos'      => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'comment'    => 'telefonos locales del cliente',
                'null'       => TRUE
            ],
            'correo'         => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'correo del cliente',
                'null'       => TRUE
            ],
            'persona'        => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'nombre de persona contacto',
                'null'       => TRUE
            ],
            'persona_tlfs'   => [
                'type'       => 'VARCHAR',
                'constraint' => '250',
                'comment'    => 'telefonos de persona contacto',
                'null'       => TRUE
            ],
            'persona_direc'  => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'direccion de persona contacto',
                'null'       => TRUE
            ],
            'persona_correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'comment'    => 'correo electronico de persona contacto',
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
            'created_clier'  => [
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
        $this->dbforge->add_field("`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->add_field("`update_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('clie__empresas');
    }

    public function down() {
        $this->dbforge->drop_table('sist__permisos', TRUE);
        $this->dbforge->drop_table('sist__permisos_groups', TRUE);
        $this->dbforge->drop_table('sist__permisos_users', TRUE);
        $this->dbforge->drop_table('sist__jerarquia', TRUE);
        $this->dbforge->drop_table('sist__contratos', TRUE);
        $this->dbforge->drop_table('clie__clientes', TRUE);
        $this->dbforge->drop_table('clie__clientes_users', TRUE);
        $this->dbforge->drop_table('clie__auditores_empresas', TRUE);
        $this->dbforge->drop_table('clie__empresas', TRUE);
    }

}
