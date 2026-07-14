<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if ( ! $this->input->is_cli_request())
		{
			show_error('Este controller so pode ser executado via linha de comando.', 403);
		}

		$this->load->library('migration');
	}

	public function index()
	{
		if ( ! $this->migration->latest())
		{
			echo 'Erro ao rodar as migrations: '.$this->migration->error_string().PHP_EOL;
			return;
		}

		echo 'Migrations executadas com sucesso.'.PHP_EOL;
	}
}
