<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Entradas extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Entrada_model');
    }

    /**
     * POST /api/entradas
     * {
     *   "motorista_id": 5,
     *   "data_hora_entrada": "2026-07-14 17:00:00",
     *   "observacoes": "opcional",
     *   "recipientes": ["REC-000001", "REC-000002"]
     * }
     */
    public function store()
    {
        $payload = $this->input_json();

        if (empty($payload['data_hora_entrada']) || empty($payload['recipientes']) || ! is_array($payload['recipientes'])) {
            $this->responder(array(), false, 'Campos obrigatorios: data_hora_entrada, recipientes[].', 422);
            return;
        }

        $codigos = array_map('strtoupper', $payload['recipientes']);

        $resultado = $this->Entrada_model->registrar_entrada(
            isset($payload['motorista_id']) ? (int) $payload['motorista_id'] : null,
            $payload['data_hora_entrada'],
            $payload['observacoes'] ?? null,
            $codigos,
            $this->current_user->id,
            'api'
        );

        if (! $resultado['sucesso']) {
            $this->responder(array(), false, $resultado['erro'], 422);
            return;
        }

        $this->responder(array('entrada_id' => $resultado['entrada_id']), true, 'Entrada registrada com sucesso.', 201);
    }

    /**
     * GET /api/entradas/{id}
     */
    public function show($id)
    {
        $entrada = $this->Entrada_model->find($id);

        if (! $entrada) {
            $this->responder(array(), false, 'Entrada nao encontrada.', 404);
            return;
        }

        $this->responder(array(
            'entrada' => $entrada,
            'itens' => $this->Entrada_model->itens($id),
        ));
    }
}
