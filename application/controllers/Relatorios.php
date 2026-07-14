<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorios extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Relatorio_model');
	}

	public function movimentacao()
	{
		list($de, $ate, $de_input, $ate_input) = $this->_periodo();

		render_page('relatorios/movimentacao', array(
			'linhas' => $this->Relatorio_model->movimentacao($de, $ate),
			'de' => $de_input,
			'ate' => $ate_input,
		));
	}

	public function motoristas()
	{
		list($de, $ate, $de_input, $ate_input) = $this->_periodo();

		render_page('relatorios/motoristas', array(
			'linhas' => $this->Relatorio_model->devolucoes_por_motorista($de, $ate),
			'de' => $de_input,
			'ate' => $ate_input,
		));
	}

	/**
	 * Resolve o range de data (DE/ATE) a partir da querystring, com
	 * padrao dos ultimos 30 dias quando nao informado.
	 */
	private function _periodo()
	{
		$de_input = $this->input->get('de', TRUE) ?: date('Y-m-d', strtotime('-30 days'));
		$ate_input = $this->input->get('ate', TRUE) ?: date('Y-m-d');

		return array($de_input.' 00:00:00', $ate_input.' 23:59:59', $de_input, $ate_input);
	}
}
