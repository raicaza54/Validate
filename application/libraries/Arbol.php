<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Explorador
 * Arbol binario de datos
 * 
 * @Copyright   GEO INFORMATIC SOLUTIONS SAS
 * @Author      Kevin Giovanni Enriquez Cordovez - kevin.g.enriquez.c@gmail.com
 * @Description Libreria explorador de carpetas
 * @LastUpdate  2019-03-02
 */
class Arbol {

    private $CI;
    private $_elements = array();

    public function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->model('Explorador_model');
    }

//$items = [
//    ['id' => "10", 'parent' => "#", 'text' => "2019", 'type' => 'folder', 'state' => ['opened' => false, 'selected' => false, 'disabled' => false]],
//    ['id' => "20", 'parent' => "10", 'text' => "Enero", 'type' => 'folder', 'state' => ['opened' => false, 'selected' => false, 'disabled' => false]],
//    ['id' => "1", 'parent' => "20", 'text' => "Movimientos", 'type' => 'img', 'state' => ['opened' => false, 'selected' => false, 'disabled' => false]]
//];
    
    public function run($id_empresa) {
        $arbol = [];
        $childs = [];
        $elements  = $this->get($id_empresa);
        if(count($elements) > 0){
            $masters   = $elements["masters"];
            $childrens = $elements["childrens"];            
            foreach($masters as $master){
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
                        'file' => $master["archivos_id"]
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
    
    private function get($id_empresa) {
        $query = $this->CI->Explorador_model->getFolderEmpresa($id_empresa);
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
        $ramas = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
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
                                'file' => $row["archivos_id"]
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
                                'file' => $row["archivos_id"]
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
