<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class Recipientes extends Operador_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Recipiente_model');
	}

	const POR_PAGINA = 10;

	public function index()
	{
		$status = $this->input->get('status', TRUE) ?: NULL;
		$pagina_atual = max(1, (int) $this->input->get('pagina', TRUE));
		$total = $this->Recipiente_model->contar($status);

		$this->pagination->initialize(montar_config_paginacao(current_url(), $total, self::POR_PAGINA));

		render_page('recipientes/index', array(
			'recipientes' => $this->Recipiente_model->all($status, self::POR_PAGINA, ($pagina_atual - 1) * self::POR_PAGINA),
			'status_filtro' => $status,
			'links_paginacao' => $this->pagination->create_links(),
		));
	}

	public function novo()
	{
		render_page('recipientes/form', array('recipiente' => NULL));
	}

	public function criar()
	{
		$this->form_validation->set_rules('descricao', 'Descricao', 'max_length[100]');

		if ($this->form_validation->run() === FALSE)
		{
			render_page('recipientes/form', array('recipiente' => NULL));
			return;
		}

		$id = $this->Recipiente_model->create(array(
			'descricao' => $this->input->post('descricao', TRUE) ?: 'Recipiente termico padrao',
		));

		$recipiente = $this->Recipiente_model->find($id);

		$this->session->set_flashdata('sucesso', 'Recipiente '.$recipiente->codigo.' cadastrado com sucesso.');
		redirect('recipientes/detalhe/'.$recipiente->codigo);
	}

	public function editar($id)
	{
		$recipiente = $this->Recipiente_model->find($id);

		if ( ! $recipiente)
		{
			show_404();
		}

		render_page('recipientes/form', array('recipiente' => $recipiente));
	}

	public function atualizar($id)
	{
		$recipiente = $this->Recipiente_model->find($id);

		if ( ! $recipiente)
		{
			show_404();
		}

		$this->form_validation->set_rules('descricao', 'Descricao', 'max_length[100]');
		$this->form_validation->set_rules('status', 'Status', 'required|in_list[estoque,em_uso,manutencao,inativo]');

		if ($this->form_validation->run() === FALSE)
		{
			render_page('recipientes/form', array('recipiente' => $recipiente));
			return;
		}

		$this->Recipiente_model->update($id, array(
			'descricao' => $this->input->post('descricao', TRUE),
			'status' => $this->input->post('status', TRUE),
		));

		$this->session->set_flashdata('sucesso', 'Recipiente atualizado com sucesso.');
		redirect('recipientes/detalhe/'.$recipiente->codigo);
	}

	public function detalhe($codigo)
	{
		$recipiente = $this->Recipiente_model->find_by_codigo($codigo);

		if ( ! $recipiente)
		{
			show_404();
		}

		render_page('recipientes/detalhe', array(
			'recipiente' => $recipiente,
			'historico' => $this->Recipiente_model->historico($recipiente->id),
		));
	}

	/**
	 * Gera a imagem PNG do QR Code sob demanda (sem persistir em disco).
	 */
	public function qrcode($codigo)
	{
		$recipiente = $this->Recipiente_model->find_by_codigo($codigo);

		if ( ! $recipiente)
		{
			show_404();
		}

		$conteudo = base_url('recipientes/detalhe/'.$recipiente->codigo);

		$qrCode = new QrCode($conteudo);
		$writer = new PngWriter();
		$result = $writer->write($qrCode);

		$this->output
			->set_content_type('image/png')
			->set_output($result->getString());
	}
}
