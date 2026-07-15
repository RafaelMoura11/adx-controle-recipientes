<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Recipiente_model $Recipiente_model
 */
class Dashboard extends Operador_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Recipiente_model');
    }

    public function index()
    {
        $hoje = date('Y-m-d');

        $dados = array(
            'total_estoque' => $this->Recipiente_model->contar_por_status('estoque'),
            'total_em_uso' => $this->Recipiente_model->contar_por_status('em_uso'),
            'total_manutencao' => $this->Recipiente_model->contar_por_status('manutencao'),
            'saidas_hoje' => $this->db->where('DATE(data_hora_saida)', $hoje)->count_all_results('saidas'),
            'entradas_hoje' => $this->db->where('DATE(data_hora_entrada)', $hoje)->count_all_results('entradas'),
        );

        render_page('dashboard/index', $dados);
    }
}
