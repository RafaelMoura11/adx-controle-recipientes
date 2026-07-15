<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Saida_model $Saida_model
 */
class Saidas extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Saida_model');
    }

    /**
     * POST /api/saidas
     * {
     *   "motorista_id": 5,
     *   "rota_id": 2,
     *   "data_hora_saida": "2026-07-14 08:30:00",
     *   "observacoes": "opcional",
     *   "pontos": [
     *     {"ponto_entrega_id": 10, "recipientes": ["REC-000001", "REC-000002"]}
     *   ]
     * }
     */
    public function store()
    {
        $payload = $this->input_json();

        if (empty($payload['motorista_id']) || empty($payload['data_hora_saida']) || empty($payload['pontos']) || ! is_array($payload['pontos'])) {
            $this->responder(array(), false, 'Campos obrigatorios: motorista_id, data_hora_saida, pontos[].', 422);
            return;
        }

        $pontos = array();
        foreach ($payload['pontos'] as $ponto) {
            if (empty($ponto['ponto_entrega_id']) || empty($ponto['recipientes']) || ! is_array($ponto['recipientes'])) {
                $this->responder(array(), false, 'Cada ponto precisa de ponto_entrega_id e recipientes[].', 422);
                return;
            }

            $pontos[] = array(
                'ponto_entrega_id' => (int) $ponto['ponto_entrega_id'],
                'codigos' => array_map('strtoupper', $ponto['recipientes']),
            );
        }

        $resultado = $this->Saida_model->registrar_saida(
            (int) $payload['motorista_id'],
            isset($payload['rota_id']) ? (int) $payload['rota_id'] : null,
            $payload['data_hora_saida'],
            $payload['observacoes'] ?? null,
            $pontos,
            $this->current_user->id,
            'api'
        );

        if (! $resultado['sucesso']) {
            $this->responder(array(), false, $resultado['erro'], 422);
            return;
        }

        $this->responder(array('saida_id' => $resultado['saida_id']), true, 'Saida registrada com sucesso.', 201);
    }

    /**
     * GET /api/saidas/{id}
     */
    public function show($id)
    {
        $saida = $this->Saida_model->find($id);

        if (! $saida) {
            $this->responder(array(), false, 'Saida nao encontrada.', 404);
            return;
        }

        $itens = $this->Saida_model->itens_por_ponto($id);

        $this->responder(array(
            'saida' => $saida,
            'itens' => $itens,
        ));
    }
}
