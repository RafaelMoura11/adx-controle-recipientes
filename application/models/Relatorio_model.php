<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Relatorio_model extends MY_Model
{
    /**
     * Relatorio 01 - Movimentacao de recipientes por local de destino,
     * agrupado por saida + ponto de entrega, filtrado por range de data
     * da saida.
     */
    public function movimentacao($data_de, $data_ate)
    {
        return $this->db->select("
				pontos_entrega.nome AS local_destino,
				saidas.data_hora_saida,
				GROUP_CONCAT(recipientes.codigo ORDER BY recipientes.codigo SEPARATOR ', ') AS recipientes_saida,
				COUNT(*) AS quantidade_saida,
				SUM(saida_itens.status_item = 'retornado') AS quantidade_retornado,
				SUM(saida_itens.status_item = 'em_uso') AS recipiente_em_uso
			", false)
            ->from('saida_itens')
            ->join('saidas', 'saidas.id = saida_itens.saida_id')
            ->join('pontos_entrega', 'pontos_entrega.id = saida_itens.ponto_entrega_id')
            ->join('recipientes', 'recipientes.id = saida_itens.recipiente_id')
            ->where('saidas.data_hora_saida >=', $data_de)
            ->where('saidas.data_hora_saida <=', $data_ate)
            ->group_by('saidas.id, pontos_entrega.id')
            ->order_by('saidas.data_hora_saida', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Relatorio 02 - Motoristas responsaveis pela devolucao de cada
     * recipiente, agrupado por entrada, filtrado por range de data
     * da entrada.
     */
    public function devolucoes_por_motorista($data_de, $data_ate)
    {
        return $this->db->select("
				motorista.nome AS motorista,
				entradas.data_hora_entrada,
				GROUP_CONCAT(recipientes.codigo ORDER BY recipientes.codigo SEPARATOR ', ') AS recipientes_entrada,
				COUNT(*) AS quantidade_entrada
			", false)
            ->from('entrada_itens')
            ->join('entradas', 'entradas.id = entrada_itens.entrada_id')
            ->join('recipientes', 'recipientes.id = entrada_itens.recipiente_id')
            ->join('usuarios AS motorista', 'motorista.id = entradas.motorista_id', 'left')
            ->where('entradas.data_hora_entrada >=', $data_de)
            ->where('entradas.data_hora_entrada <=', $data_ate)
            ->group_by('entradas.id')
            ->order_by('entradas.data_hora_entrada', 'DESC')
            ->get()
            ->result();
    }
}
