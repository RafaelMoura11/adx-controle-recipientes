<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Saida_model extends CI_Model
{
    public function find($id)
    {
        return $this->db->select('saidas.*, motorista.nome AS motorista_nome, rotas.nome AS rota_nome, registrou.nome AS registrado_por_nome')
            ->from('saidas')
            ->join('usuarios AS motorista', 'motorista.id = saidas.motorista_id')
            ->join('usuarios AS registrou', 'registrou.id = saidas.usuario_registrou_id')
            ->join('rotas', 'rotas.id = saidas.rota_id', 'left')
            ->where('saidas.id', $id)
            ->get()
            ->row();
    }

    public function all($limit = null, $offset = 0)
    {
        $this->db->select('saidas.*, motorista.nome AS motorista_nome, rotas.nome AS rota_nome')
            ->from('saidas')
            ->join('usuarios AS motorista', 'motorista.id = saidas.motorista_id')
            ->join('rotas', 'rotas.id = saidas.rota_id', 'left')
            ->order_by('saidas.data_hora_saida', 'DESC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function contar()
    {
        return $this->db->count_all_results('saidas');
    }

    public function itens_por_ponto($saida_id)
    {
        return $this->db->select('saida_itens.*, pontos_entrega.nome AS ponto_nome, recipientes.codigo')
            ->from('saida_itens')
            ->join('pontos_entrega', 'pontos_entrega.id = saida_itens.ponto_entrega_id')
            ->join('recipientes', 'recipientes.id = saida_itens.recipiente_id')
            ->where('saida_itens.saida_id', $saida_id)
            ->order_by('pontos_entrega.ordem', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Registra uma saida completa: cabecalho, pontos e itens (recipientes),
     * validando estoque e atualizando o estado atual de cada recipiente.
     * $pontos = array( array('ponto_entrega_id' => int, 'codigos' => array('REC-000001', ...)), ... )
     *
     * @return array('sucesso' => bool, 'saida_id' => int|null, 'erro' => string|null)
     */
    public function registrar_saida($motorista_id, $rota_id, $data_hora_saida, $observacoes, array $pontos, $usuario_registrou_id, $origem = 'painel')
    {
        $this->load->model('Recipiente_model');

        $this->db->trans_begin();

        // Valida todos os recipientes antes de gravar qualquer coisa.
        $todos_codigos = array();
        foreach ($pontos as $ponto) {
            $todos_codigos = array_merge($todos_codigos, $ponto['codigos']);
        }

        if (empty($todos_codigos)) {
            $this->db->trans_rollback();
            return array('sucesso' => false, 'saida_id' => null, 'erro' => 'Informe ao menos um recipiente.');
        }

        if (count($todos_codigos) !== count(array_unique($todos_codigos))) {
            $this->db->trans_rollback();
            return array('sucesso' => false, 'saida_id' => null, 'erro' => 'Ha recipientes duplicados na saida.');
        }

        $recipientes_por_codigo = array();
        foreach ($todos_codigos as $codigo) {
            $recipiente = $this->Recipiente_model->find_by_codigo($codigo);

            if (! $recipiente) {
                $this->db->trans_rollback();
                return array('sucesso' => false, 'saida_id' => null, 'erro' => "Recipiente {$codigo} nao encontrado.");
            }

            if ($recipiente->status !== 'estoque') {
                $this->db->trans_rollback();
                return array('sucesso' => false, 'saida_id' => null, 'erro' => "Recipiente {$codigo} nao esta em estoque (status atual: {$recipiente->status}).");
            }

            $recipientes_por_codigo[$codigo] = $recipiente;
        }

        $this->db->insert('saidas', array(
            'motorista_id' => $motorista_id,
            'rota_id' => $rota_id ?: null,
            'usuario_registrou_id' => $usuario_registrou_id,
            'data_hora_saida' => $data_hora_saida,
            'observacoes' => $observacoes ?: null,
            'status' => 'aberta',
            'origem' => $origem,
            'created_at' => date('Y-m-d H:i:s'),
        ));
        $saida_id = $this->db->insert_id();

        foreach ($pontos as $ponto) {
            $this->db->insert('saida_pontos', array(
                'saida_id' => $saida_id,
                'ponto_entrega_id' => $ponto['ponto_entrega_id'],
                'quantidade_planejada' => count($ponto['codigos']),
            ));

            $ponto_info = $this->db->where('id', $ponto['ponto_entrega_id'])->get('pontos_entrega')->row();

            foreach ($ponto['codigos'] as $codigo) {
                $recipiente = $recipientes_por_codigo[$codigo];

                $this->db->insert('saida_itens', array(
                    'saida_id' => $saida_id,
                    'ponto_entrega_id' => $ponto['ponto_entrega_id'],
                    'recipiente_id' => $recipiente->id,
                    'status_item' => 'em_uso',
                    'created_at' => date('Y-m-d H:i:s'),
                ));

                $this->Recipiente_model->update($recipiente->id, array(
                    'status' => 'em_uso',
                    'saida_atual_id' => $saida_id,
                    'ponto_entrega_atual_id' => $ponto['ponto_entrega_id'],
                    'localizacao_atual' => $ponto_info->nome,
                ));
            }
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return array('sucesso' => false, 'saida_id' => null, 'erro' => 'Erro ao gravar a saida no banco de dados.');
        }

        $this->db->trans_commit();

        return array('sucesso' => true, 'saida_id' => $saida_id, 'erro' => null);
    }
}
