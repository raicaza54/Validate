<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @Packager    StraData S.A.S.
 * @Copyright   StraData S.A.S. - www.stradata.com.co
 * @Author      Gustavo Adolfo Naranjo - gustavo.naranjo@stradata.com.co
 * @Description Controlador Generico - Modulo de Registro
 * @LastUpdate  2018-05-24
 */
class Empresas extends CI_Controller {

    public $response = array(
        "meta"   => array(
            "copyright" => "Verify 2019",
            "authors"   => array(
                "Kevin Enriquez",
            )
        ),
        "status" => "422",
        "source" => array(
            "pointer" => ""
        ),
        "title"  => "Invalid Attribute",
        "detail" => "",
        "data"   => array(),
        "csrf"   => ''
    );

    function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->load->model('Empresas_model');
    }

    public function datos() {
        $response = $this->response;
        try {
            $items = $this->Empresas_model->getAll();
            if (!is_array($items)) {
                throw new Exception("No existen datos para mostrar", 204);
            }
            $response["data"] = $items;
            throw new Exception("Resultado retornando correctamente", 200);
        } catch (Exception $exc) {
            $response["status"] = $exc->getCode();
            $exception          = array(
                "code"    => $exc->getCode(),
                "message" => $exc->getMessage(),
            );
            if ($exception["code"] === 200) {
                $response["title"]  = "Procedimiento realizado satisfactoriamente";
                $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Petición correcta";
            } elseif ($exception["code"] === 202) {
                $response["title"]  = "Petición Aceptada pero incompleta";
                $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Petición Aceptada pero incompleta";
                log_message("error", $exc->getCode() . ' - ' . $exc->getMessage());
            } elseif ($exception["code"] === 500) {
                $response["title"]  = "Error Interno del Servidor";
                $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
                log_message("error", $exc->getCode() . ' - ' . $exc->getMessage());
            } else {
                $response["title"]  = "Error Interno del Servidor";
                $response["detail"] = (strlen($exception["message"]) && !empty($exception["message"])) ? $exception["message"] : "Internal Server Error";
                log_message("error", $exc->getCode() . ' - ' . $exc->getMessage());
            }
        }
        $response['csrf'] = $this->security->get_csrf_hash();
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

}
