<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Usuario_model');
    }

    public function login()
    {
        if ($this->session->userdata('usuario_id')) {
            redirect($this->session->userdata('usuario_tipo') === 'motorista' ? 'motorista' : 'dashboard');
        }

        $erro = null;

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
            $this->form_validation->set_rules('senha', 'Senha', 'required');

            if ($this->form_validation->run() === true) {
                $email = $this->input->post('email', true);
                $senha = $this->input->post('senha', true);

                $usuario = $this->Usuario_model->find_by_email($email);

                if ($usuario && password_verify($senha, $usuario->senha_hash)) {
                    if ($usuario->situacao !== 'ativo') {
                        $erro = 'Este usuario esta bloqueado. Fale com um administrador.';
                    } else {
                        $this->session->set_userdata(array(
                            'usuario_id' => $usuario->id,
                            'usuario_nome' => $usuario->nome,
                            'usuario_tipo' => $usuario->tipo_usuario,
                        ));
                        redirect($usuario->tipo_usuario === 'motorista' ? 'motorista' : 'dashboard');
                    }
                } else {
                    $erro = 'E-mail ou senha invalidos.';
                }
            }
        }

        $this->load->view('auth/login', array('erro' => $erro));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
