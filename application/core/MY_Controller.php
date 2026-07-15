<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CI3 so carrega automaticamente application/core/MY_Controller.php (este
 * arquivo). Outras classes base ficam em arquivos separados por clareza,
 * mas precisam ser explicitamente requeridas aqui.
 */
require_once __DIR__.'/API_Controller.php';

/**
 * MY_Controller.php e requerido pelo boot do CI3 antes de CI_Model existir
 * (que so e carregado sob demanda via $this->load->model()). Garante a
 * ordem certa antes de MY_Model.php declarar "extends CI_Model".
 */
require_once BASEPATH.'core/Model.php';
require_once __DIR__.'/MY_Model.php';

/**
 * Base para qualquer tela do painel que exija usuario autenticado e ativo.
 *
 * @property CI_DB_mysqli_driver $db
 * @property CI_Loader $load
 * @property CI_Session $session
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Form_validation $form_validation
 * @property CI_Pagination $pagination
 * @property Usuario_model $Usuario_model
 */
class Auth_Controller extends CI_Controller
{
    protected $usuario_logado;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Usuario_model');

        $usuario_id = $this->session->userdata('usuario_id');

        if (! $usuario_id) {
            redirect('login');
        }

        $usuario = $this->Usuario_model->find($usuario_id);

        if (! $usuario || $usuario->situacao !== 'ativo') {
            $this->session->sess_destroy();
            redirect('login');
        }

        $this->usuario_logado = $usuario;

        $this->load->vars(array('usuario_logado' => $usuario));
    }

    protected function exigir_tipo(array $tipos_permitidos)
    {
        if (! in_array($this->usuario_logado->tipo_usuario, $tipos_permitidos, true)) {
            show_error('Voce nao tem permissao para acessar esta pagina.', 403, 'Acesso negado');
        }
    }
}

/**
 * Apenas administrador (usuarios, relatorios).
 */
class Admin_Controller extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->exigir_tipo(array('administrador'));
    }
}

/**
 * Administrador ou operador (recipientes, saidas, entradas, rotas).
 */
class Operador_Controller extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->exigir_tipo(array('administrador', 'operador'));
    }
}

/**
 * Apenas motorista (tela somente leitura de destino/quantidade).
 */
class Motorista_Controller extends Auth_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->exigir_tipo(array('motorista'));
    }
}
