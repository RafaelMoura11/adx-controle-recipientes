<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recipientes extends API_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Recipiente_model');
	}

	/**
	 * GET /api/recipientes?status=estoque
	 */
	public function index()
	{
		$status = $this->input->get('status', TRUE) ?: NULL;

		$this->responder($this->Recipiente_model->all($status));
	}

	/**
	 * GET /api/recipientes/{codigo}
	 */
	public function show($codigo)
	{
		$recipiente = $this->Recipiente_model->find_by_codigo($codigo);

		if ( ! $recipiente)
		{
			$this->responder(array(), FALSE, 'Recipiente nao encontrado.', 404);
			return;
		}

		$this->responder(array(
			'recipiente' => $recipiente,
			'historico' => $this->Recipiente_model->historico($recipiente->id),
		));
	}
}
