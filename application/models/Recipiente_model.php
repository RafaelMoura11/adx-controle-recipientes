<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Recipiente_model extends CI_Model
{
    protected $table = 'recipientes';

    public function find($id)
    {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function find_by_codigo($codigo)
    {
        return $this->db->where('codigo', $codigo)->get($this->table)->row();
    }

    public function all($status = null, $limit = null, $offset = 0)
    {
        if ($status !== null) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('codigo', 'ASC');

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get($this->table)->result();
    }

    public function contar($status = null)
    {
        if ($status !== null) {
            $this->db->where('status', $status);
        }

        return $this->db->count_all_results($this->table);
    }

    public function contar_por_status($status)
    {
        return $this->db->where('status', $status)->count_all_results($this->table);
    }

    public function proximo_codigo()
    {
        $ultimo = $this->db->select('codigo')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get($this->table)
            ->row();

        $proximo_numero = 1;

        if ($ultimo) {
            $numero_atual = (int) substr($ultimo->codigo, 4);
            $proximo_numero = $numero_atual + 1;
        }

        return 'REC-'.str_pad($proximo_numero, 6, '0', STR_PAD_LEFT);
    }

    public function create(array $dados)
    {
        $dados['codigo'] = $this->proximo_codigo();
        $dados['status'] = 'estoque';
        $dados['localizacao_atual'] = 'Estoque Central';
        $dados['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $dados);
        return $this->db->insert_id();
    }

    public function update($id, array $dados)
    {
        $dados['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', $id)->update($this->table, $dados);
    }

    /**
     * Reconstroi o estado atual do recipiente a partir do historico
     * imutavel (saida_itens/entrada_itens). Util como correcao manual
     * caso o estado desnormalizado divirja do historico real.
     */
    public function recalcular_status($recipiente_id)
    {
        $item_aberto = $this->db->select('saida_itens.saida_id, saida_itens.ponto_entrega_id, pontos_entrega.nome AS ponto_nome')
            ->from('saida_itens')
            ->join('pontos_entrega', 'pontos_entrega.id = saida_itens.ponto_entrega_id')
            ->where('saida_itens.recipiente_id', $recipiente_id)
            ->where('saida_itens.status_item', 'em_uso')
            ->order_by('saida_itens.id', 'DESC')
            ->limit(1)
            ->get()
            ->row();

        if ($item_aberto) {
            $this->update($recipiente_id, array(
                'status' => 'em_uso',
                'saida_atual_id' => $item_aberto->saida_id,
                'ponto_entrega_atual_id' => $item_aberto->ponto_entrega_id,
                'localizacao_atual' => $item_aberto->ponto_nome,
            ));
        } else {
            $this->update($recipiente_id, array(
                'status' => 'estoque',
                'saida_atual_id' => null,
                'ponto_entrega_atual_id' => null,
                'localizacao_atual' => 'Estoque Central',
            ));
        }
    }

    /**
     * Historico completo de movimentacoes de um recipiente (saidas + entradas),
     * a partir das tabelas transacionais imutaveis.
     */
    public function historico($recipiente_id)
    {
        $saidas = $this->db->select("
				'saida' AS tipo,
				saidas.data_hora_saida AS data_hora,
				pontos_entrega.nome AS local,
				motorista.nome AS motorista_nome,
				registrou.nome AS registrado_por,
				saida_itens.status_item
			", false)
            ->from('saida_itens')
            ->join('saidas', 'saidas.id = saida_itens.saida_id')
            ->join('pontos_entrega', 'pontos_entrega.id = saida_itens.ponto_entrega_id')
            ->join('usuarios AS motorista', 'motorista.id = saidas.motorista_id')
            ->join('usuarios AS registrou', 'registrou.id = saidas.usuario_registrou_id')
            ->where('saida_itens.recipiente_id', $recipiente_id)
            ->get()
            ->result();

        $entradas = $this->db->select("
				'entrada' AS tipo,
				entradas.data_hora_entrada AS data_hora,
				NULL AS local,
				motorista.nome AS motorista_nome,
				registrou.nome AS registrado_por,
				NULL AS status_item
			", false)
            ->from('entrada_itens')
            ->join('entradas', 'entradas.id = entrada_itens.entrada_id')
            ->join('usuarios AS registrou', 'registrou.id = entradas.usuario_registrou_id')
            ->join('usuarios AS motorista', 'motorista.id = entradas.motorista_id', 'left')
            ->where('entrada_itens.recipiente_id', $recipiente_id)
            ->get()
            ->result();

        $historico = array_merge($saidas, $entradas);

        usort($historico, function ($a, $b) {
            return strtotime($b->data_hora) <=> strtotime($a->data_hora);
        });

        return $historico;
    }
}
