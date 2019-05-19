<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Cliente Model
 *
 * Copyright   (c) 2019 Kevin Giovanni Enriquez Cordovez
 *  
 * @author     GEO INFORMATIC SOLUTIONS SAS
 * @author     Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @version    0.0.1
 * @LastUpdate 2018-05-19
 */
class Cliente_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function getContrato($cliente_id) {
        if(is_numeric($cliente_id)){
            $this->db->select("
                id,
                fk_clientes,
                consecutivo,
                DATE_FORMAT(fecha_ini,'%d/%m/%Y') AS fecha_ini,
                DATE_FORMAT(fecha_fin,'%d/%m/%Y') AS fecha_fin,
                DATEDIFF(fecha_fin, fecha_ini) AS vigencia,
                servicios,
                observacion,
                created_user,
                created_clie,
                update_user,
                update_clie,
                created_at,
                update_at,
                FORMAT(cant_usuario, 0, 'de_DE') AS cant_usuario,
                FORMAT(cant_empresas, 0, 'de_DE') AS cant_empresas,
                FORMAT(cant_movimiento, 0, 'de_DE') AS cant_movimiento,
                FORMAT(cant_balances, 0, 'de_DE') AS cant_balances,
                FORMAT(cant_cxp, 0, 'de_DE') AS cant_cxp,
                FORMAT(cant_cxc, 0, 'de_DE') AS cant_cxc,
                FORMAT(filas_movimiento, 0, 'de_DE') AS filas_movimiento,
                FORMAT(filas_balances, 0, 'de_DE') AS filas_balances,
                FORMAT(filas_cxp, 0, 'de_DE') AS filas_cxp,
                FORMAT(filas_cxc, 0, 'de_DE') AS filas_cxc,
                FORMAT(espacio_disco, 0, 'de_DE') AS espacio_disco,
                estado,
                pc_nombre,
                pc_telefono,
                pc_email,
                ec_nombre,
                ec_telefono,
                ec_email
            ",TRUE);
            $this->db->where('fk_clientes', $cliente_id);
            $this->db->where('estado', 1);
            $this->db->order_by('id', 'DESC');
            $contrato = $this->db->get('sist__contratos')->row_array();
            return $contrato;
        }else{
            return FALSE;
        }
    }
    
    public function getLimites($cliente_id) {
        if(is_numeric($cliente_id)){
            $this->db->select('
                cant_movimiento,
                cant_balances,
                cant_cxp,
                cant_cxc,
                filas_movimiento,
                filas_balances,
                filas_cxp,
                filas_cxc
            ');
            $this->db->where('fk_clientes', $cliente_id);
            $this->db->where('estado', 1);
            $this->db->order_by('id', 'DESC');
            $contrato = $this->db->get('sist__contratos')->row_array();
            $cant = [
                'movimiento' => 0,
                'balances'   => 0,
                'cxp'        => 0,
                'cxc'        => 0
            ];            
            $cant = $this->db->query("
                SELECT COUNT(*) as cant, clie__archivos.tipo AS tipo 
                FROM clie__carpetas INNER JOIN clie__archivos ON clie__carpetas.archivos_id = clie__archivos.id 
                WHERE fk_empresas IN(
                    SELECT fk_empresas FROM clie__auditores_empresas WHERE fk_auditores IN(
                        SELECT id FROM auth__users WHERE fk_cliente = ".$cliente_id."
                    )
                ) 
                AND `type` IN('excel','csv') AND deleted_at = 0 
                GROUP BY tipo
            ")->result_array();
            if(is_array($cant) && count($cant)){
                foreach ($cant as $value) {
                    if($value['tipo'] == 'mov'){
                        $cant['movimiento'] = $value['cant'];
                    }elseif($value['tipo'] == 'blp'){
                        $cant['balances'] = $value['cant'];
                    }elseif($value['tipo'] == 'cxp'){
                        $cant['cxp'] = $value['cant'];
                    }elseif($value['tipo'] == 'cxc'){
                        $cant['cxc'] = $value['cant'];
                    }
                }
            }
            if(is_array($contrato) && count($contrato)){
                $limites = [
                    'movimiento' => [
                        'limite' => $contrato['cant_movimiento'],
                        'filas'  => $contrato['filas_movimiento'],
                        'cant'   => $cant['movimiento'],
                    ],
                    'balances' => [
                        'limite' => $contrato['cant_balances'],
                        'filas'  => $contrato['filas_balances'],
                        'cant'   => $cant['balances'],
                    ],
                    'cxp' => [
                        'limite' => $contrato['cant_cxp'],
                        'filas'  => $contrato['filas_cxp'],
                        'cant'   => $cant['cxp'],
                    ],
                    'cxc' => [
                        'limite' => $contrato['cant_cxc'],
                        'filas'  => $contrato['filas_cxc'],
                        'cant'   => $cant['cxc'],
                    ]
                ];
                return $limites;
            }
        }else{
            return FALSE;
        }
    }
    
}
