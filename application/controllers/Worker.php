<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Worker — daemon que procesa jobs de Gearman.
 *
 * Solo accesible desde CLI:
 *   php /var/www/html/index.php worker listen
 *
 * Tipos de job soportados:
 *   - condicion_cuenta : ejecuta un análisis de Condición de Cuenta async.
 *
 * Migración pilot — primer análisis convertido a async (mayo 2026).
 */
class Worker extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!is_cli()) {
            show_404();
        }
        // Evitar que CI mate el proceso del worker ante un error SQL.
        $this->db->db_debug = FALSE;
        $this->load->model('Condicion_model');
        $this->load->model('Archivos_model');
        $this->load->library('Condicion');
    }

    /**
     * Loop principal — escucha indefinidamente jobs de Gearman.
     */
    public function listen() {
        if (!extension_loaded('gearman')) {
            fwrite(STDERR, "FATAL: extensión 'gearman' no cargada\n");
            exit(1);
        }

        $host = 'gearman';
        $port = 4730;

        $w = new GearmanWorker();
        $w->addServer($host, $port);
        $w->addFunction('condicion_cuenta', [$this, 'process_condicion_cuenta']);

        $this->log("Worker iniciado, escuchando en {$host}:{$port} (funciones: condicion_cuenta)");

        while (true) {
            @$w->work();
            $rc = $w->returnCode();
            if ($rc === GEARMAN_SUCCESS) {
                continue;
            }
            if ($rc === GEARMAN_NO_ACTIVE_FDS || $rc === GEARMAN_IO_WAIT) {
                sleep(2);
                continue;
            }
            $this->log("Gearman returnCode={$rc} ({$w->error()})");
            sleep(1);
        }
    }

    /**
     * Procesa un job de condición de cuenta.
     * Payload esperado: { analisis_id, archivo_id, condicion: [{cuenta, porcentaje, tolerancia}, ...] }
     */
    public function process_condicion_cuenta($job) {
        $payload = json_decode($job->workload(), TRUE);
        $analisis_id = $payload['analisis_id'] ?? NULL;
        $this->log("Procesando condicion_cuenta analisis_id={$analisis_id}");

        if (!$analisis_id) {
            $this->log("Payload inválido — falta analisis_id");
            return json_encode(['ok' => FALSE, 'error' => 'invalid payload']);
        }

        try {
            $this->markProgress($analisis_id, 'processing', 5);

            $archivo_id = $payload['archivo_id'];
            $condicion  = $payload['condicion'];

            $column = $this->archivo->columnCondicion($archivo_id);
            if (!$column) {
                throw new Exception('No se pudo obtener la columna de cuenta para archivo_id=' . $archivo_id);
            }
            $items = @$this->Archivos_model->getBases($archivo_id, $column);

            if (!is_array($items) || count($items) <= 0) {
                throw new Exception('El archivo no es legible o no tiene filas para analizar');
            }

            $this->markProgress($analisis_id, 'processing', 40);

            $condiciones = [];
            foreach ($condicion as $value) {
                $condiciones[$value['cuenta']] = [
                    'porcentaje' => $value['porcentaje'],
                    'tolerancia' => $value['tolerancia'],
                ];
            }
            $this->condicion->condiciones = $condiciones;
            $this->condicion->datos       = $items;
            $resultado = $this->condicion->run();

            $this->db->where('id', $analisis_id)->update('clie__analisis', [
                'analisis' => json_encode($resultado),
                'estado'   => 'completed',
                'progreso' => 100,
            ]);

            $this->log("Job {$analisis_id} OK");
            return json_encode(['ok' => TRUE]);

        } catch (Exception $e) {
            $msg = substr($e->getMessage(), 0, 1020);
            $this->db->where('id', $analisis_id)->update('clie__analisis', [
                'estado'    => 'failed',
                'error_msg' => $msg,
            ]);
            $this->log("Job {$analisis_id} FAIL: {$msg}");
            return json_encode(['ok' => FALSE, 'error' => $msg]);
        }
    }

    private function markProgress($id, $estado, $progreso) {
        $this->db->where('id', $id)->update('clie__analisis', [
            'estado'   => $estado,
            'progreso' => $progreso,
        ]);
    }

    private function log($msg) {
        echo '['.date('c').'] '.$msg.PHP_EOL;
    }
}
