<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Recipiente_model $Recipiente_model
 */
class Entrada_model extends MY_Model
{
    public function find($id)
    {
        return $this->db->select('entradas.*, motorista.nome AS motorista_nome, registrou.nome AS registrado_por_nome')
            ->from('entradas')
            ->join('usuarios AS registrou', 'registrou.id = entradas.usuario_registrou_id')
            ->join('usuarios AS motorista', 'motorista.id = entradas.motorista_id', 'left')
            ->where('entradas.id', $id)
            ->get()
            ->row();
    }

    public function all($limit = null, $offset = 0)
    {
        $this->db->select('entradas.*, motorista.nome AS motorista_nome, registrou.nome AS registrado_por_nome')
            ->from('entradas')
            ->join('usuarios AS registrou', 'registrou.id = entradas.usuario_registrou_id')
            ->join('usuarios AS motorista', 'motorista.id = entradas.motorista_id', 'left')
            ->order_by('entradas.data_hora_entrada', 'DESC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result();
    }

    public function contar()
    {
        return $this->db->count_all_results('entradas');
    }

    public function itens($entrada_id)
    {
        return $this->db->select('entrada_itens.*, recipientes.codigo')
            ->from('entrada_itens')
            ->join('recipientes', 'recipientes.id = entrada_itens.recipiente_id')
            ->where('entrada_itens.entrada_id', $entrada_id)
            ->get()
            ->result();
    }

    /**
     * Registra uma entrada (devolucao ao estoque) para uma lista de codigos
     * de recipientes, fechando o saida_item aberto correspondente de cada
     * um, atualizando o estado do recipiente e recalculando o status das
     * saidas afetadas.
     *
     * @return array('sucesso' => bool, 'entrada_id' => int|null, 'erro' => string|null)
     */
    public function registrar_entrada($motorista_id, $data_hora_entrada, $observacoes, array $codigos, $usuario_registrou_id, $origem = 'painel')
    {
        $this->load->model('Recipiente_model');

        $this->db->trans_begin();

        if (empty($codigos)) {
            $this->db->trans_rollback();
            return array('sucesso' => false, 'entrada_id' => null, 'erro' => 'Informe ao menos um recipiente.');
        }

        if (count($codigos) !== count(array_unique($codigos))) {
            $this->db->trans_rollback();
            return array('sucesso' => false, 'entrada_id' => null, 'erro' => 'Há recipientes duplicados na entrada.');
        }

        $saida_itens_por_codigo = array();
        $recipientes_por_codigo = array();

        foreach ($codigos as $codigo) {
            $recipiente = $this->Recipiente_model->find_by_codigo($codigo);

            if (! $recipiente) {
                $this->db->trans_rollback();
                return array('sucesso' => false, 'entrada_id' => null, 'erro' => "Recipiente {$codigo} não encontrado.");
            }

            if ($recipiente->status !== 'em_uso') {
                $this->db->trans_rollback();
                return array('sucesso' => false, 'entrada_id' => null, 'erro' => "Recipiente {$codigo} não está em uso (status atual: {$recipiente->status}).");
            }

            $saida_item = $this->db->where('recipiente_id', $recipiente->id)
                ->where('status_item', 'em_uso')
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get('saida_itens')
                ->row();

            if (! $saida_item) {
                $this->db->trans_rollback();
                return array('sucesso' => false, 'entrada_id' => null, 'erro' => "Recipiente {$codigo} não possui saída em aberto.");
            }

            $recipientes_por_codigo[$codigo] = $recipiente;
            $saida_itens_por_codigo[$codigo] = $saida_item;
        }

        $this->db->insert('entradas', array(
            'usuario_registrou_id' => $usuario_registrou_id,
            'motorista_id' => $motorista_id ?: null,
            'data_hora_entrada' => $data_hora_entrada,
            'observacoes' => $observacoes ?: null,
            'origem' => $origem,
            'created_at' => date('Y-m-d H:i:s'),
        ));
        $entrada_id = $this->db->insert_id();

        $saidas_afetadas = array();

        foreach ($codigos as $codigo) {
            $recipiente = $recipientes_por_codigo[$codigo];
            $saida_item = $saida_itens_por_codigo[$codigo];

            $this->db->insert('entrada_itens', array(
                'entrada_id' => $entrada_id,
                'recipiente_id' => $recipiente->id,
                'saida_item_id' => $saida_item->id,
                'created_at' => date('Y-m-d H:i:s'),
            ));
            $entrada_item_id = $this->db->insert_id();

            $this->db->where('id', $saida_item->id)->update('saida_itens', array(
                'status_item' => 'retornado',
                'entrada_item_id' => $entrada_item_id,
            ));

            $this->Recipiente_model->update($recipiente->id, array(
                'status' => 'estoque',
                'saida_atual_id' => null,
                'ponto_entrega_atual_id' => null,
                'localizacao_atual' => 'Estoque Central',
            ));

            $saidas_afetadas[$saida_item->saida_id] = true;
        }

        foreach (array_keys($saidas_afetadas) as $saida_id) {
            $this->_recalcular_status_saida($saida_id);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return array('sucesso' => false, 'entrada_id' => null, 'erro' => 'Erro ao gravar a entrada no banco de dados.');
        }

        $this->db->trans_commit();

        return array('sucesso' => true, 'entrada_id' => $entrada_id, 'erro' => null);
    }

    private function _recalcular_status_saida($saida_id)
    {
        $total = $this->db->where('saida_id', $saida_id)->count_all_results('saida_itens');
        $retornados = $this->db->where('saida_id', $saida_id)->where('status_item', 'retornado')->count_all_results('saida_itens');

        if ($retornados === 0) {
            $status = 'aberta';
        } elseif ($retornados < $total) {
            $status = 'parcialmente_retornada';
        } else {
            $status = 'concluida';
        }

        $this->db->where('id', $saida_id)->update('saidas', array('status' => $status));
    }
}
