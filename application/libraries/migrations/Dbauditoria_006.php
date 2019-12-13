<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dbauditoria_006{
    private $CI;
    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->dbforge();
    }

    public function up() {
        $fields = [
            'cant_usuario' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de auditores por cliente',
                'null'       => TRUE
            ],
            'cant_empresas' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de empresas por cliente',
                'null'       => TRUE
            ],
            'cant_movimiento' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de archivos tipo movimiento',
                'null'       => TRUE
            ],
            'cant_balances' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de archivos tipo balance de prueba',
                'null'       => TRUE
            ],
            'cant_cxp' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de archivos tipo cuentas por pagar',
                'null'       => TRUE
            ],
            'cant_cxc' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de archivos tipo cuentas por cobrar',
                'null'       => TRUE
            ],
            'filas_movimiento' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de filas en el archivos tipo movimiento',
                'null'       => TRUE
            ],
            'filas_balances' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de filas en el archivos tipo balance de prueba',
                'null'       => TRUE
            ],
            'filas_cxp' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de filas en el archivos tipo cuentas por pagar',
                'null'       => TRUE
            ],
            'filas_cxc' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'cantidad de filas en el archivos tipo cuentas por cobrar',
                'null'       => TRUE
            ],
            'espacio_disco' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'comment'    => 'espacio en disco por cliente',
                'null'       => TRUE                
            ],
            'tipo_contrato' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'comment'    => '0: demo, 1: pago',
                'null'       => TRUE                
            ],
        ];
        $this->CI->dbforge->add_column('sist__contratos', $fields);
    }

    public function down() {
        $this->CI->dbforge->drop_column('sist__contratos','cant_usuario');
        $this->CI->dbforge->drop_column('sist__contratos','cant_empresas');
        $this->CI->dbforge->drop_column('sist__contratos','cant_movimiento'); 
        $this->CI->dbforge->drop_column('sist__contratos','cant_balances');
        $this->CI->dbforge->drop_column('sist__contratos','cant_cxp');
        $this->CI->dbforge->drop_column('sist__contratos','cant_cxc');
        $this->CI->dbforge->drop_column('sist__contratos','filas_movimiento');
        $this->CI->dbforge->drop_column('sist__contratos','filas_balances');
        $this->CI->dbforge->drop_column('sist__contratos','filas_cxp');
        $this->CI->dbforge->drop_column('sist__contratos','filas_cxc');
        $this->CI->dbforge->drop_column('sist__contratos','espacio_disco');
        $this->CI->dbforge->drop_column('sist__contratos','tipo_contrato');
    }

}