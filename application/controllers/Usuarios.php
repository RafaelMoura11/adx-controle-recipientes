<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Usuario_model');
	}

	public function index()
	{
		render_page('usuarios/index', array(
			'usuarios' => $this->Usuario_model->all(),
		));
	}

	public function novo()
	{
		render_page('usuarios/form', array(
			'usuario' => NULL,
			'erro' => NULL,
		));
	}

	public function criar()
	{
		$this->form_validation->set_rules('nome', 'Nome', 'required|max_length[150]');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('senha', 'Senha', 'required|min_length[6]');
		$this->form_validation->set_rules('tipo_usuario', 'Tipo de usuario', 'required|in_list[administrador,operador,motorista]');

		if ($this->form_validation->run() === FALSE)
		{
			render_page('usuarios/form', array('usuario' => NULL, 'erro' => NULL));
			return;
		}

		$email = $this->input->post('email', TRUE);

		if ($this->Usuario_model->email_em_uso($email))
		{
			render_page('usuarios/form', array('usuario' => NULL, 'erro' => 'Ja existe um usuario com este e-mail.'));
			return;
		}

		$this->Usuario_model->create(array(
			'nome' => $this->input->post('nome', TRUE),
			'email' => $email,
			'senha_hash' => password_hash($this->input->post('senha', TRUE), PASSWORD_DEFAULT),
			'tipo_usuario' => $this->input->post('tipo_usuario', TRUE),
			'telefone' => $this->input->post('telefone', TRUE) ?: NULL,
			'cnh' => $this->input->post('cnh', TRUE) ?: NULL,
			'situacao' => 'ativo',
		));

		$this->session->set_flashdata('sucesso', 'Usuario cadastrado com sucesso.');
		redirect('usuarios');
	}

	public function editar($id)
	{
		$usuario = $this->Usuario_model->find($id);

		if ( ! $usuario)
		{
			show_404();
		}

		render_page('usuarios/form', array('usuario' => $usuario, 'erro' => NULL));
	}

	public function atualizar($id)
	{
		$usuario = $this->Usuario_model->find($id);

		if ( ! $usuario)
		{
			show_404();
		}

		$this->form_validation->set_rules('nome', 'Nome', 'required|max_length[150]');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('tipo_usuario', 'Tipo de usuario', 'required|in_list[administrador,operador,motorista]');

		if ($this->form_validation->run() === FALSE)
		{
			render_page('usuarios/form', array('usuario' => $usuario, 'erro' => NULL));
			return;
		}

		$email = $this->input->post('email', TRUE);

		if ($this->Usuario_model->email_em_uso($email, $id))
		{
			render_page('usuarios/form', array('usuario' => $usuario, 'erro' => 'Ja existe um usuario com este e-mail.'));
			return;
		}

		$dados = array(
			'nome' => $this->input->post('nome', TRUE),
			'email' => $email,
			'tipo_usuario' => $this->input->post('tipo_usuario', TRUE),
			'telefone' => $this->input->post('telefone', TRUE) ?: NULL,
			'cnh' => $this->input->post('cnh', TRUE) ?: NULL,
		);

		$senha = $this->input->post('senha', TRUE);
		if ( ! empty($senha))
		{
			$dados['senha_hash'] = password_hash($senha, PASSWORD_DEFAULT);
		}

		$this->Usuario_model->update($id, $dados);

		$this->session->set_flashdata('sucesso', 'Usuario atualizado com sucesso.');
		redirect('usuarios');
	}

	public function bloquear($id)
	{
		$this->Usuario_model->set_situacao($id, 'bloqueado');
		$this->session->set_flashdata('sucesso', 'Usuario bloqueado.');
		redirect('usuarios');
	}

	public function desbloquear($id)
	{
		$this->Usuario_model->set_situacao($id, 'ativo');
		$this->session->set_flashdata('sucesso', 'Usuario desbloqueado.');
		redirect('usuarios');
	}
}
