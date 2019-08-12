<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Explorador
 * Arbol binario de datos
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria explorador de Resultados
 * @LastUpdate  2019-03-02
 */
class Arbolr {

    private $CI;
    private $_elements = array();

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model('Resultados_model');
    }

//$items = [
//    ['id' => "10", 'parent' => "#", 'text' => "2019", 'type' => 'folder', 'state' => ['opened' => false, 'selected' => false, 'disabled' => false]],
//    ['id' => "20", 'parent' => "10", 'text' => "Enero", 'type' => 'folder', 'state' => ['opened' => false, 'selected' => false, 'disabled' => false]],
//    ['id' => "1", 'parent' => "20", 'text' => "Movimientos", 'type' => 'img', 'state' => ['opened' => false, 'selected' => false, 'disabled' => false]]
//];
    
    private function supTipo($tipo) {
        $r = ['', ''];
        if($tipo == 'mov'){
            $r = [
                ' - [Movimiento]'
            ];
        }elseif($tipo == 'blp'){
            $r = [
                ' - [Balance de Prueba]'
            ];
        }
        return $r;
    }
    
    public function run($id_empresa, $id_archivo = NULL) {
        $arbol = []; $childs = []; $tipo = [];
        $elements  = $this->get($id_empresa, $id_archivo);
        if(count($elements) > 0){
            $masters   = $elements["masters"];
            $childrens = $elements["childrens"];
            foreach($masters as $master){
                $tipo = $this->supTipo($master["tipo"]);
                $arbol[] = [
                    'id'     => $master["id"],
                    'parent' => '#',
                    'text'   => $master["label"],
                    'type'   => $master["type"],
                    'state'  => [
                        'opened'   => boolval($master["opened"]),
                        'selected' => boolval($master["selected"]),
                        'disabled' => boolval($master["disabled"]),
                    ],
                    'li_attr' => [
                        'file'           => $master["id"],
                        'title'          => $master["label"].$tipo[0],
                        'data-toggle'    => "tooltip",
                        'data-placement' => "top"
                    ]
                ];
                $childs = $this->nested($childrens, $master["id"]);
                if(count($childs) > 0){
                    $arbol = array_merge($arbol, $childs);
                }
                $childs = [];
            }
        }
        return $arbol;
    }
    
    private function get($id_empresa, $id_archivo) {
        $query = $this->CI->Resultados_model->getFolderEmpresa($id_empresa, $id_archivo);
        $this->_elements["masters"] = $this->_elements["childrens"] = array();
        if (count($query) > 0) {
            foreach ($query as $element) {
                if ($element["parent_id"] == 0) {
                    array_push($this->_elements["masters"], $element);
                } else {
                    array_push($this->_elements["childrens"], $element);
                }
            }
        }
        return $this->_elements;
    }

    private function nested($rows = array(), $parent_id = 0) {
        $ramas = []; $tipo = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $tipo = $this->supTipo($row["tipo"]);
                if ($row["parent_id"] == $parent_id) {
                    if ($row["have_childrens"] == 1) {
                        $ramas[] = [
                            'id'     => $row["id"],
                            'parent' => $row["parent_id"],
                            'text'   => $row['label'],
                            'type'   => $row["type"],
                            'state'  => [
                                'opened'   => boolval($row["opened"]),
                                'selected' => boolval($row["selected"]),
                                'disabled' => boolval($row["disabled"])
                            ],
                            'li_attr' => [
                                'file'           => $row["id"],
                                'title'          => $row["label"].$tipo[0],
                                'data-toggle'    => "tooltip",
                                'data-placement' => "top"
                            ]
                        ];
                    } else {
                        $ramas[] = [                    
                            'id'     => $row["id"],
                            'parent' => $row["parent_id"],
                            'text'   => $row['label'],
                            'type'   => $row["type"],
                            'state'  => [
                                'opened'   => boolval($row["opened"]),
                                'selected' => boolval($row["selected"]),
                                'disabled' => boolval($row["disabled"])
                            ],
                            'li_attr' => [
                                'file'           => $row["id"],
                                'title'          => $row["label"].$tipo[0],
                                'data-toggle'    => "tooltip",
                                'data-placement' => "top"
                            ]
                        ];
                    }
                    $ramas = array_merge($ramas, $this->nested($rows, $row["id"]));
                }
            }
        }
        return $ramas;
    }

}
