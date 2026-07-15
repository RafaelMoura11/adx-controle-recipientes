<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Base para os endpoints REST (application/controllers/api/*).
 * Autentica via HTTP Basic Auth contra a tabela usuarios e responde
 * sempre em JSON, no formato {"sucesso": bool, "dados": ..., "mensagem": "..."}.
 */
class API_Controller extends CI_Controller
{
    protected $current_user;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Usuario_model');

        $usuario = $this->_autenticar();

        if (! $usuario) {
            $this->responder(array(), false, 'Credenciais invalidas.', 401);
            exit;
        }

        if ($usuario->situacao !== 'ativo') {
            $this->responder(array(), false, 'Usuario bloqueado.', 403);
            exit;
        }

        $this->current_user = $usuario;
    }

    private function _autenticar()
    {
        $headers = function_exists('getallheaders') ? getallheaders() : array();
        $authorization = $headers['Authorization'] ?? $headers['authorization'] ?? ($_SERVER['HTTP_AUTHORIZATION'] ?? null);

        $usuario_input = $_SERVER['PHP_AUTH_USER'] ?? null;
        $senha_input = $_SERVER['PHP_AUTH_PW'] ?? null;

        if (($usuario_input === null || $senha_input === null) && $authorization && stripos($authorization, 'Basic ') === 0) {
            $decodificado = base64_decode(substr($authorization, 6));
            if ($decodificado !== false && strpos($decodificado, ':') !== false) {
                list($usuario_input, $senha_input) = explode(':', $decodificado, 2);
            }
        }

        if ($usuario_input === null || $senha_input === null) {
            return null;
        }

        $usuario = $this->Usuario_model->find_by_email($usuario_input);

        if (! $usuario || ! password_verify($senha_input, $usuario->senha_hash)) {
            return null;
        }

        return $usuario;
    }

    /**
     * Le e decodifica o corpo JSON da requisicao (o Input::post() do CI3
     * nao entende application/json nativamente).
     */
    protected function input_json()
    {
        $corpo = file_get_contents('php://input');
        $dados = json_decode($corpo, true);

        return is_array($dados) ? $dados : array();
    }

    protected function responder($dados, $sucesso = true, $mensagem = '', $http_code = 200)
    {
        $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode(array(
                'sucesso' => $sucesso,
                'dados' => $dados,
                'mensagem' => $mensagem,
            )));
    }
}
