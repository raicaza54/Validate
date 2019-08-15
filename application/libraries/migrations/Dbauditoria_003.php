<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_003{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $data = [
            [
                'id'            => 1,
                'etiqueta'      => 'Supervisor',
                'nivel'         => '1',
                'observacion'   => '',
                'created_user'  => '1',
                'created_clie' => '1',
                'update_user'   => '1',
                'update_clie'   => '1',
            ]
        ];
        $this->db->insert_batch('sist__jerarquia', $data);

        $data = [
            [
                'id'             => 1,
                'nombre'         => 'GEO INFORMATIC SOLUTIONS SAS',
                'identificacion' => '0123456789',
                'direccion'      => 'Carrera 48 # 76 D Sur 52 mall vegas plaza, oficina 314',
                'telefonos'      => '3331212',
                'correo'         => 'eoinformaticsolutions@gmail.com',
                'persona'        => 'Kevin Enriquez',
                'persona_tlfs'   => '3183163746',
                'persona_direc'  => 'Tr 35A Sur 78-22 Envigado',
                'persona_correo' => 'kevin.g.enriquez.c@gmail.com',
                'comercial'      => 'Luisa Londoño',
                'observacion'    => '',
                'created_user'   => '1',
                'created_clie'  => '1',
                'update_user'    => '1',
                'update_clie'    => '1',
            ]
        ];
        $this->db->insert_batch('clie__clientes', $data);

        $data = [
            [
                'id'             => 1,
                'nombre'         => 'GEO INFORMATIC SOLUTIONS SAS',
                'identificacion' => '0123456789',
                'direccion'      => 'Carrera 48 # 76 D Sur 52 mall vegas plaza, oficina 314',
                'telefonos'      => '3331212',
                'correo'         => 'eoinformaticsolutions@gmail.com',
                'persona'        => 'Kevin Enriquez',
                'persona_tlfs'   => '3183163746',
                'persona_direc'  => 'Tr 35A Sur 78-22 Envigado',
                'persona_correo' => 'kevin.g.enriquez.c@gmail.com',
                'observacion'    => '',
                'created_user'   => '1',
                'created_clie'  => '1',
                'update_user'    => '1',
                'update_clie'    => '1',
            ]
        ];
        $this->db->insert_batch('clie__empresas', $data);
        
        $data = [
            [
                'fk_auditores'  => '1',
                'fk_empresas'   => '1',
                'observacion'   => '',
                'created_user'  => '1',
                'created_clie' => '1',
                'update_user'   => '1',
                'update_clie'   => '1',
            ]
        ];
        $this->db->insert_batch('clie__auditores_empresas', $data);        
    }

    public function down() {
        $this->db->truncate('sist__jerarquia');
        $this->db->truncate('clie__clientes');
        $this->db->truncate('clie__auditores_empresas');
        $this->db->truncate('clie__empresas');
    }

}
