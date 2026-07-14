<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rotas extends Operador_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Rota_model');
		$this->load->model('PontoEntrega_model');
	}

	public function index()
	{
		render_page('rotas/index', array(
			'rotas' => $this->Rota_model->all(),
		));
	}

	public function novo()
	{
		render_page('rotas/form', array('rota' => NULL));
	}

	public function criar()
	{
		$this->form_validation->set_rules('nome', 'Nome', 'required|max_length[150]');

		if ($this->form_validation->run() === FALSE)
		{
			render_page('rotas/form', array('rota' => NULL));
			return;
		}

		$id = $this->Rota_model->create(array(
			'nome' => $this->input->post('nome', TRUE),
			'descricao' => $this->input->post('descricao', TRUE),
			'ativa' => 1,
		));

		$this->session->set_flashdata('sucesso', 'Rota cadastrada. Agora adicione os pontos de entrega.');
		redirect('rotas/editar/'.$id);
	}

	public function editar($id)
	{
		$rota = $this->Rota_model->find($id);

		if ( ! $rota)
		{
			show_404();
		}

		render_page('rotas/form', array(
			'rota' => $rota,
			'pontos' => $this->PontoEntrega_model->por_rota($id),
		));
	}

	public function atualizar($id)
	{
		$rota = $this->Rota_model->find($id);

		if ( ! $rota)
		{
			show_404();
		}

		$this->form_validation->set_rules('nome', 'Nome', 'required|max_length[150]');

		if ($this->form_validation->run() === FALSE)
		{
			render_page('rotas/form', array('rota' => $rota, 'pontos' => $this->PontoEntrega_model->por_rota($id)));
			return;
		}

		$this->Rota_model->update($id, array(
			'nome' => $this->input->post('nome', TRUE),
			'descricao' => $this->input->post('descricao', TRUE),
			'ativa' => $this->input->post('ativa', TRUE) ? 1 : 0,
		));

		$this->session->set_flashdata('sucesso', 'Rota atualizada com sucesso.');
		redirect('rotas/editar/'.$id);
	}

	public function adicionar_ponto($rota_id)
	{
		$rota = $this->Rota_model->find($rota_id);

		if ( ! $rota)
		{
			show_404();
		}

		$this->form_validation->set_rules('nome', 'Nome do ponto', 'required|max_length[150]');

		if ($this->form_validation->run() === TRUE)
		{
			$ordem = count($this->PontoEntrega_model->por_rota($rota_id)) + 1;

			$this->PontoEntrega_model->create(array(
				'rota_id' => $rota_id,
				'nome' => $this->input->post('nome', TRUE),
				'endereco' => $this->input->post('endereco', TRUE),
				'ordem' => $ordem,
				'ativo' => 1,
			));

			$this->session->set_flashdata('sucesso', 'Ponto de entrega adicionado.');
		}
		else
		{
			$this->session->set_flashdata('erro', 'Informe um nome valido para o ponto de entrega.');
		}

		redirect('rotas/editar/'.$rota_id);
	}

	public function editar_ponto($ponto_id)
	{
		$ponto = $this->PontoEntrega_model->find($ponto_id);

		if ( ! $ponto)
		{
			show_404();
		}

		$this->form_validation->set_rules('nome', 'Nome do ponto', 'required|max_length[150]');

		if ($this->form_validation->run() === TRUE)
		{
			$this->PontoEntrega_model->update($ponto_id, array(
				'nome' => $this->input->post('nome', TRUE),
				'endereco' => $this->input->post('endereco', TRUE),
			));

			$this->session->set_flashdata('sucesso', 'Ponto de entrega atualizado.');
		}
		else
		{
			$this->session->set_flashdata('erro', 'Informe um nome valido para o ponto de entrega.');
		}

		redirect('rotas/editar/'.$ponto->rota_id);
	}

	public function desativar_ponto($ponto_id)
	{
		$ponto = $this->PontoEntrega_model->find($ponto_id);

		if ( ! $ponto)
		{
			show_404();
		}

		$this->PontoEntrega_model->set_ativo($ponto_id, FALSE);
		redirect('rotas/editar/'.$ponto->rota_id);
	}

	public function ativar_ponto($ponto_id)
	{
		$ponto = $this->PontoEntrega_model->find($ponto_id);

		if ( ! $ponto)
		{
			show_404();
		}

		$this->PontoEntrega_model->set_ativo($ponto_id, TRUE);
		redirect('rotas/editar/'.$ponto->rota_id);
	}
}
