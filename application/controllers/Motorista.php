<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motorista extends Motorista_Controller {

	public function index()
	{
		$destinos = $this->db->select('
				saidas.id AS saida_id,
				saidas.data_hora_saida,
				pontos_entrega.id AS ponto_entrega_id,
				pontos_entrega.nome AS ponto_nome,
				pontos_entrega.endereco AS ponto_endereco,
				COUNT(*) AS quantidade_recipientes
			', FALSE)
			->from('saida_itens')
			->join('saidas', 'saidas.id = saida_itens.saida_id')
			->join('pontos_entrega', 'pontos_entrega.id = saida_itens.ponto_entrega_id')
			->where('saidas.motorista_id', $this->usuario_logado->id)
			->where('saida_itens.status_item', 'em_uso')
			->group_by('saidas.id, pontos_entrega.id')
			->order_by('saidas.data_hora_saida', 'DESC')
			->order_by('pontos_entrega.ordem', 'ASC')
			->get()
			->result();

		render_page('motorista/index', array('destinos' => $destinos));
	}
}
