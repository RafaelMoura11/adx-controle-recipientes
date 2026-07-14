<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CI3 so carrega automaticamente application/core/MY_Controller.php (este
 * arquivo). Outras classes base ficam em arquivos separados por clareza,
 * mas precisam ser explicitamente requeridas aqui.
 */
require_once __DIR__.'/API_Controller.php';

/**
 * Base para qualquer tela do painel que exija usuario autenticado e ativo.
 */
class Auth_Controller extends CI_Controller {

	protected $usuario_logado;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Usuario_model');

		$usuario_id = $this->session->userdata('usuario_id');

		if ( ! $usuario_id)
		{
			redirect('login');
		}

		$usuario = $this->Usuario_model->find($usuario_id);

		if ( ! $usuario || $usuario->situacao !== 'ativo')
		{
			$this->session->sess_destroy();
			redirect('login');
		}

		$this->usuario_logado = $usuario;

		$this->load->vars(array('usuario_logado' => $usuario));
	}

	protected function exigir_tipo(array $tipos_permitidos)
	{
		if ( ! in_array($this->usuario_logado->tipo_usuario, $tipos_permitidos, TRUE))
		{
			show_error('Voce nao tem permissao para acessar esta pagina.', 403, 'Acesso negado');
		}
	}
}

/**
 * Apenas administrador (usuarios, relatorios).
 */
class Admin_Controller extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->exigir_tipo(array('administrador'));
	}
}

/**
 * Administrador ou operador (recipientes, saidas, entradas, rotas).
 */
class Operador_Controller extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->exigir_tipo(array('administrador', 'operador'));
	}
}

/**
 * Apenas motorista (tela somente leitura de destino/quantidade).
 */
class Motorista_Controller extends Auth_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->exigir_tipo(array('motorista'));
	}
}
