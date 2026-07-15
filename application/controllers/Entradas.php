<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entradas extends Operador_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Entrada_model');
		$this->load->model('Usuario_model');
	}

	public function index()
	{
		render_page('entradas/index', array(
			'entradas' => $this->Entrada_model->all(),
		));
	}

	public function novo()
	{
		render_page('entradas/form', array(
			'motoristas' => $this->Usuario_model->all('motorista'),
			'erro' => NULL,
		));
	}

	public function criar()
	{
		$this->form_validation->set_rules('data_hora_entrada', 'Data e hora da entrada', 'required');

		if ($this->form_validation->run() === FALSE)
		{
			render_page('entradas/form', array(
				'motoristas' => $this->Usuario_model->all('motorista'),
				'erro' => validation_errors(),
			));
			return;
		}

		$motorista_id = $this->input->post('motorista_id', TRUE) ?: NULL;
		$data_hora_entrada = str_replace('T', ' ', $this->input->post('data_hora_entrada', TRUE)).':00';
		$observacoes = $this->input->post('observacoes', TRUE);
		$codigos = parse_codigos_recipientes($this->input->post('recipientes', TRUE));

		$resultado = $this->Entrada_model->registrar_entrada(
			$motorista_id,
			$data_hora_entrada,
			$observacoes,
			$codigos,
			$this->usuario_logado->id,
			'painel'
		);

		if ( ! $resultado['sucesso'])
		{
			render_page('entradas/form', array(
				'motoristas' => $this->Usuario_model->all('motorista'),
				'erro' => $resultado['erro'],
			));
			return;
		}

		$this->session->set_flashdata('sucesso', 'Entrada registrada com sucesso.');
		redirect('entradas/detalhe/'.$resultado['entrada_id']);
	}

	public function detalhe($id)
	{
		$entrada = $this->Entrada_model->find($id);

		if ( ! $entrada)
		{
			show_404();
		}

		render_page('entradas/detalhe', array(
			'entrada' => $entrada,
			'itens' => $this->Entrada_model->itens($id),
		));
	}

}
